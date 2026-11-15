<?php
require_once __DIR__ . '/helpers.php';
$active = 'home';
$groups = blood_groups();
$donors = [];
$connection = db();
if ($connection) {
    $result = mysqli_query($connection, 'SELECT d.donor_name, b.blood_group FROM donor_details d JOIN blood b ON b.blood_id = d.donor_blood ORDER BY d.donor_id DESC LIMIT 6');
    if ($result) $donors = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="description" content="Find a blood donor or register to help someone in your community."><title>LifeLine — Blood donors, closer</title><link rel="stylesheet" href="assets/site.css"></head><body>
<?php include __DIR__ . '/head.php'; ?>
<main class="main"><div class="container">
  <?php if (!$connection): ?><div class="alert alert-error" role="status">The donor database is not connected yet. Import <code>sql/blood_bank_database.sql</code> and confirm the credentials in <code>conn.php</code> to enable donor registration and search.</div><?php endif; ?>
  <section class="hero"><div><span class="eyebrow">A community that shows up</span><h1>One small act.<br><em>A lifeline for someone.</em></h1><p class="lead">Find willing blood donors near you, or sign up to be there for someone when every minute matters.</p><div class="actions"><a class="btn btn-primary" href="need_blood.php">Find a blood donor <span aria-hidden="true">→</span></a><a class="btn btn-secondary" href="donate_blood.php">Become a donor</a></div></div><img class="hero-art" src="image/blood_donationcover.jpeg" alt="Blood donation helps patients get the care they need"></section>
  <section class="section"><div class="grid"><article class="card"><div class="card-icon" aria-hidden="true">⌕</div><h3>Search by blood group</h3><p>Browse donor contacts for the blood group you need and reach out directly.</p></article><article class="card"><div class="card-icon" aria-hidden="true">♡</div><h3>Register in minutes</h3><p>Share your blood group and contact details so people can find your offer to help.</p></article><article class="card"><div class="card-icon" aria-hidden="true">✚</div><h3>Help responsibly</h3><p>Eligibility and final donor matching should always be confirmed by medical professionals.</p></article></div></section>
  <section class="section"><div class="section-head"><div><span class="eyebrow">Here to help</span><h2>Our donor community</h2></div><a href="need_blood.php">Search by blood group →</a></div><div class="grid">
  <?php if ($donors): foreach ($donors as $donor): ?><article class="card donor-card"><span class="blood-badge"><?= e($donor['blood_group']) ?></span><h3 style="margin-top:14px"><?= e($donor['donor_name']) ?></h3><p>Registered donor</p></article><?php endforeach; else: ?><div class="empty-state">New donors will appear here as they join the community. <a href="donate_blood.php">Be the first to register.</a></div><?php endif; ?>
  </div></section>
  <section class="section"><div class="callout"><div><h2>Ready to make a difference?</h2><p>It takes a few minutes to register as a donor.</p></div><a class="btn btn-primary" href="donate_blood.php">Register as a donor →</a></div></section>
</div></main><?php include __DIR__ . '/footer.php'; ?></body></html>
