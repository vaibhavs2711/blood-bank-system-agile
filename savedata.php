<?php
// Keep old bookmarks working while routing new submissions through validation.
header('Location: donate_blood.php', true, 303);
exit;
