<?php
require_once __DIR__ . '/helpers.php';
$active = 'contact';
$errors = [];
$success = isset($_GET['sent']);
$contact = [];
$connection = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['contactno'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name === '' || text_length($name) > 100) $errors[] = 'Enter your name (up to 100 characters).';
    $phoneDigits = preg_replace('/\D/', '', $phone);
    if (!preg_match('/^[0-9+() .-]{7,20}$/', $phone) || strlen($phoneDigits) < 10 || strlen($phoneDigits) > 11) $errors[] = 'Enter a phone number with 10 or 11 digits.';
    else $phone = $phoneDigits;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || text_length($email) > 120) $errors[] = 'Enter a valid email address.';
    if ($message === '' || text_length($message) > 5000) $errors[] = 'Enter a message of up to 5,000 characters.';
    if (!$connection) $errors[] = 'The database is unavailable. Please try again later.';
    if (!$errors) {
        $statement = mysqli_prepare($connection, 'INSERT INTO contact_query (query_name, query_mail, query_number, query_message, query_status) VALUES (?, ?, ?, ?, 2)');
        mysqli_stmt_bind_param($statement, 'ssss', $name, $email, $phone, $message);
        if (mysqli_stmt_execute($statement)) { mysqli_stmt_close($statement); header('Location: contact_us.php?sent=1'); exit; }
        $errors[] = 'We could not send your message. Please try again.';
        mysqli_stmt_close($statement);
    }
}
if ($connection) {
    $result = mysqli_query($connection, 'SELECT contact_address, contact_phone, contact_mail FROM contact_info ORDER BY contact_id LIMIT 1');
    if ($result) $contact = mysqli_fetch_assoc($result) ?: [];
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="description" content="Contact the LifeLine blood donor community."><title>Contact — LifeLine</title><link rel="stylesheet" href="assets/site.css"></head><body>
<?php include __DIR__ . '/head.php'; ?><main class="main"><div class="container"><span class="eyebrow">We’re here to help</span><h1>Get in touch</h1><p class="lead">Send a question to the team and we’ll get back to you.</p>
  <div class="form-layout section" style="margin-top:30px"><section class="form-card"><h2>Send us a message</h2><?php if ($success): ?><div class="alert" role="status">Your message has been sent. Thank you for reaching out.</div><?php endif; ?><?php if ($errors): ?><div class="alert alert-error" role="alert"><strong>Please review:</strong><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
  <form method="post" action="contact_us.php" class="form-grid"><div class="field"><label for="fullname">Full name</label><input id="fullname" name="fullname" maxlength="100" autocomplete="name" required value="<?= e($_POST['fullname'] ?? '') ?>"></div><div class="field"><label for="contactno">Phone number</label><input id="contactno" name="contactno" type="tel" maxlength="20" autocomplete="tel" required value="<?= e($_POST['contactno'] ?? '') ?>"></div><div class="field full"><label for="email">Email address</label><input id="email" name="email" type="email" maxlength="120" autocomplete="email" required value="<?= e($_POST['email'] ?? '') ?>"></div><div class="field full"><label for="message">Message</label><textarea id="message" name="message" maxlength="5000" required><?= e($_POST['message'] ?? '') ?></textarea></div><div class="field full"><button class="btn btn-primary" type="submit">Send message →</button></div></form></section>
  <aside class="info-card contact-details"><div class="card-icon" aria-hidden="true">✉</div><h2>Contact details</h2><?php if ($contact): ?><p><strong>Address</strong><?= e($contact['contact_address']) ?></p><p><strong>Phone</strong><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $contact['contact_phone'])) ?>"><?= e($contact['contact_phone']) ?></a></p><p><strong>Email</strong><a href="mailto:<?= e($contact['contact_mail']) ?>"><?= e($contact['contact_mail']) ?></a></p><?php else: ?><p>Use the message form and our team will get back to you.</p><?php endif; ?></aside></div>
</div></main><?php include __DIR__ . '/footer.php'; ?></body></html>
