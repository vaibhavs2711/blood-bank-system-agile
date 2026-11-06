<?php
require_once __DIR__ . '/helpers.php';
$active = 'donate';
$groups = blood_groups();
$databaseReady = db() !== null;
$errors = [];
$success = isset($_GET['registered']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['mobileno'] ?? '');
    $email = trim($_POST['emailid'] ?? '');
    $age = filter_var($_POST['age'] ?? '', FILTER_VALIDATE_INT);
    $gender = $_POST['gender'] ?? '';
    $blood = filter_var($_POST['blood'] ?? '', FILTER_VALIDATE_INT);
    $address = trim($_POST['address'] ?? '');
    if ($name === '' || text_length($name) > 50) $errors[] = 'Enter your name (up to 50 characters).';
    if (!preg_match('/^[0-9]{10}$/', $phone)) $errors[] = 'Enter a 10-digit phone number.';
    if ($email !== '' && (!filter_var($email, FILTER_VALIDATE_EMAIL) || text_length($email) > 50)) $errors[] = 'Enter a valid email address.';
    if ($age === false || $age < 18 || $age > 100) $errors[] = 'Donors must be at least 18. Enter a valid age.';
    if (!in_array($gender, ['Male', 'Female', 'Other'], true)) $errors[] = 'Choose a gender option.';
    if ($blood === false || $blood < 1) $errors[] = 'Choose a blood group.';
    if ($address === '' || text_length($address) > 100) $errors[] = 'Enter your address (up to 100 characters).';
    $connection = db();
    if (!$connection) $errors[] = 'The database is unavailable. Please try again later.';
    if (!$errors) {
        $check = mysqli_prepare($connection, 'SELECT blood_id FROM blood WHERE blood_id = ?');
        mysqli_stmt_bind_param($check, 'i', $blood);
        mysqli_stmt_execute($check);
        $validBlood = mysqli_stmt_get_result($check)->num_rows > 0;
        mysqli_stmt_close($check);
        if (!$validBlood) $errors[] = 'Choose a valid blood group.';
        else {
            $statement = mysqli_prepare($connection, 'INSERT INTO donor_details (donor_name, donor_number, donor_mail, donor_age, donor_gender, donor_blood, donor_address) VALUES (?, ?, ?, ?, ?, ?, ?)');
            mysqli_stmt_bind_param($statement, 'sssisis', $name, $phone, $email, $age, $gender, $blood, $address);
            if (mysqli_stmt_execute($statement)) { mysqli_stmt_close($statement); header('Location: donate_blood.php?registered=1'); exit; }
            $errors[] = 'We could not save your registration. Please try again.';
            mysqli_stmt_close($statement);
        }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="description" content="Register to become a blood donor in your community."><title>Become a donor — LifeLine</title><link rel="stylesheet" href="assets/site.css"></head><body>
<?php include __DIR__ . '/head.php'; ?><main class="main"><div class="container"><span class="eyebrow">Give someone another chance</span><h1>Become a donor</h1><p class="lead">Add your details to the community donor list. A few minutes today could help someone tomorrow.</p>
  <div class="form-layout section" style="margin-top:30px"><section class="form-card"><h2>Your details</h2><p class="field-help">Fields marked * are required. Your phone number and address will be visible to people searching for donors.</p>
  <?php if (!$databaseReady): ?><div class="alert alert-error" role="status">Blood group options are shown below, but registrations cannot be saved until MySQL is connected. Import <code>sql/blood_bank_database.sql</code> and check the credentials in <code>conn.php</code>.</div><?php endif; ?>
  <?php if ($success): ?><div class="alert" role="status">Thank you for registering. Your donor profile has been added.</div><?php endif; ?>
  <?php if ($errors): ?><div class="alert alert-error" role="alert"><strong>Please review:</strong><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
  <form method="post" action="donate_blood.php" class="form-grid" autocomplete="on">
    <div class="field"><label for="fullname">Full name *</label><input id="fullname" name="fullname" maxlength="50" autocomplete="name" required value="<?= e($_POST['fullname'] ?? '') ?>"></div>
    <div class="field"><label for="mobileno">Phone number *</label><input id="mobileno" name="mobileno" type="tel" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" autocomplete="tel" required value="<?= e($_POST['mobileno'] ?? '') ?>"><span class="field-help">Enter 10 digits, without spaces or country code.</span></div>
    <div class="field"><label for="emailid">Email address</label><input id="emailid" name="emailid" type="email" maxlength="50" autocomplete="email" value="<?= e($_POST['emailid'] ?? '') ?>"></div>
    <div class="field"><label for="age">Age *</label><input id="age" name="age" type="number" min="18" max="100" required value="<?= e($_POST['age'] ?? '') ?>"></div>
    <div class="field"><label for="gender">Gender *</label><select id="gender" name="gender" required><option value="">Choose one</option><?php foreach (['Female','Male','Other'] as $option): ?><option value="<?= e($option) ?>" <?= ($_POST['gender'] ?? '') === $option ? 'selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?></select></div>
    <div class="field"><label for="blood">Blood group *</label><select id="blood" name="blood" required><option value="">Choose one</option><?php foreach ($groups as $group): ?><option value="<?= e($group['blood_id']) ?>" <?= (string)($_POST['blood'] ?? '') === (string)$group['blood_id'] ? 'selected' : '' ?>><?= e($group['blood_group']) ?></option><?php endforeach; ?></select></div>
    <div class="field full"><label for="address">Address or area *</label><textarea id="address" name="address" maxlength="100" autocomplete="street-address" required><?= e($_POST['address'] ?? '') ?></textarea></div>
    <div class="field full"><button class="btn btn-primary" type="submit">Register as a donor →</button></div>
  </form></section><aside class="info-card"><div class="card-icon" aria-hidden="true">♡</div><h3>Your choice can help</h3><p class="content-copy">Register only if you are comfortable being contacted. A healthcare professional will assess eligibility and suitability before any donation.</p><p class="disclosure">You can contact the site administrator to request removal or correction of your listing.</p></aside></div>
</div></main><?php include __DIR__ . '/footer.php'; ?></body></html>
