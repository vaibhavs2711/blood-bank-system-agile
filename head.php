<?php
require_once __DIR__ . '/helpers.php';
$active = $active ?? '';
?>
<header class="site-header">
  <div class="nav-wrap">
    <a class="brand" href="home.php"><span class="brand-mark" aria-hidden="true">+</span><span>LifeLine <span style="font-weight:500;color:#687775">Blood Network</span></span></a>
    <button class="mobile-toggle" type="button" aria-label="Open navigation" aria-expanded="false" aria-controls="site-navigation" onclick="const n=document.getElementById('site-navigation');const open=n.classList.toggle('open');this.setAttribute('aria-expanded',open);this.setAttribute('aria-label',open?'Close navigation':'Open navigation')">☰</button>
    <nav class="nav-links" id="site-navigation" aria-label="Main navigation">
      <a href="home.php" class="<?= $active === 'home' ? 'active' : '' ?>">Home</a>
      <a href="about_us.php" class="<?= $active === 'about' ? 'active' : '' ?>">About</a>
      <a href="why_donate_blood.php" class="<?= $active === 'why' ? 'active' : '' ?>">Why donate</a>
      <a href="need_blood.php" class="<?= $active === 'need' ? 'active' : '' ?>">Find donors</a>
      <a href="contact_us.php" class="<?= $active === 'contact' ? 'active' : '' ?>">Contact</a>
      <a href="donate_blood.php" class="nav-cta <?= $active === 'donate' ? 'active' : '' ?>">Become a donor</a>
    </nav>
  </div>
</header>
