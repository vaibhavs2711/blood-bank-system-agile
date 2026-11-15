<?php
require_once __DIR__ . '/helpers.php';
$active = 'need';
$groups = blood_groups();
$selected = filter_var($_GET['blood'] ?? '', FILTER_VALIDATE_INT);
$searched = $selected !== false && $selected !== null && $selected > 0;
$donors = [];
$groupName = '';
$connection = db();
if ($searched && $connection) {
    $statement = mysqli_prepare($connection, 'SELECT d.donor_name, d.donor_number, d.donor_age, d.donor_gender, d.donor_address, b.blood_group FROM donor_details d JOIN blood b ON b.blood_id = d.donor_blood WHERE d.donor_blood = ? ORDER BY d.donor_id DESC LIMIT 50');
    mysqli_stmt_bind_param($statement, 'i', $selected);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    if ($result) $donors = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($statement);
    foreach ($groups as $group) if ((int)$group['blood_id'] === $selected) $groupName = $group['blood_group'];
    if ($groupName === '') { $searched = false; $selected = null; }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="description" content="Find registered blood donors by blood group."><title>Find donors — LifeLine</title><link rel="stylesheet" href="assets/site.css"></head><body>
<?php include __DIR__ . '/head.php'; ?><main class="main"><div class="container"><span class="eyebrow">Find help in the community</span><h1>Find a blood donor</h1><p class="lead">Choose the blood group you are looking for to see registered donors and their contact details.</p>
  <form class="search-bar" method="get" action="need_blood.php"><div class="field"><label for="blood">Blood group</label><select id="blood" name="blood" required><option value="">Select a blood group</option><?php foreach ($groups as $group): ?><option value="<?= e($group['blood_id']) ?>" <?= (string)$selected === (string)$group['blood_id'] ? 'selected' : '' ?>><?= e($group['blood_group']) ?></option><?php endforeach; ?></select></div><button class="btn btn-primary" type="submit">Search donors →</button></form>
  <?php if ($searched): ?><section class="section" aria-live="polite"><div class="section-head"><div><span class="eyebrow">Search results</span><h2><?= e($groupName) ?> donors</h2></div><span class="result-count"><?= count($donors) ?> listed</span></div><div class="grid">
    <?php if ($donors): foreach ($donors as $donor): ?><article class="card donor-card"><span class="blood-badge"><?= e($donor['blood_group']) ?></span><h3 style="margin-top:14px"><?= e($donor['donor_name']) ?></h3><p><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $donor['donor_number'])) ?>"><?= e($donor['donor_number']) ?></a></p><p><?= e($donor['donor_age']) ?> years · <?= e($donor['donor_gender']) ?></p><p><?= e($donor['donor_address']) ?></p><p class="disclosure">Please confirm availability and eligibility directly.</p></article><?php endforeach; else: ?><div class="empty-state">No donors with this blood group are listed yet. You can <a href="contact_us.php">contact us</a> or ask a local hospital about other options.</div><?php endif; ?>
  </div></section><?php elseif (isset($_GET['blood'])): ?><div class="alert alert-error" role="alert">Please select a valid blood group.</div><?php endif; ?>
  <p class="disclosure">This directory contains contact information shared by registered donors. Please use it respectfully. Matching and transfusion decisions must be handled by qualified medical professionals.</p>
</div></main><?php include __DIR__ . '/footer.php'; ?></body></html>
