<?php
require_once __DIR__ . '/../../includes/config.php';
requireAdminLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= isset($adminTitle) ? $adminTitle.' — Admin' : 'Admin Panel' ?> | SRCM Inter College</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body class="admin-body">

<div class="admin-sidebar" id="adminSidebar">
  <div class="logo">
    <h2>SRCM Admin Panel</h2>
    <p><?= $_SESSION['admin_name'] ?></p>
  </div>
  <nav class="admin-nav">
    <a href="<?= SITE_URL ?>/admin/dashboard.php" <?= strpos($_SERVER['PHP_SELF'],'dashboard')!==false?'class="active"':'' ?>>📊 Dashboard</a>
    <a href="<?= SITE_URL ?>/admin/sliders.php" <?= strpos($_SERVER['PHP_SELF'],'sliders')!==false?'class="active"':'' ?>>🖼️ Sliders</a>
    <a href="<?= SITE_URL ?>/admin/announcements.php" <?= strpos($_SERVER['PHP_SELF'],'announcements')!==false?'class="active"':'' ?>>📢 Announcements</a>
    <a href="<?= SITE_URL ?>/admin/news.php" <?= strpos($_SERVER['PHP_SELF'],'news')!==false?'class="active"':'' ?>>📰 News & Updates</a>
    <a href="<?= SITE_URL ?>/admin/gallery.php" <?= strpos($_SERVER['PHP_SELF'],'gallery')!==false?'class="active"':'' ?>>📸 Gallery</a>
    <a href="<?= SITE_URL ?>/admin/staff.php" <?= strpos($_SERVER['PHP_SELF'],'staff')!==false?'class="active"':'' ?>>👨‍🏫 Staff</a>
    <a href="<?= SITE_URL ?>/admin/results.php" <?= strpos($_SERVER['PHP_SELF'],'results')!==false?'class="active"':'' ?>>🏆 Results</a>
    <a href="<?= SITE_URL ?>/admin/fee.php" <?= strpos($_SERVER['PHP_SELF'],'fee')!==false?'class="active"':'' ?>>💰 Fee Structure</a>
    <a href="<?= SITE_URL ?>/admin/tc.php" <?= strpos($_SERVER['PHP_SELF'],'/tc')!==false?'class="active"':'' ?>>📋 TC Records</a>
    <a href="<?= SITE_URL ?>/admin/enquiries.php" <?= strpos($_SERVER['PHP_SELF'],'enquiries')!==false?'class="active"':'' ?>>📝 Enquiries</a>
    <a href="<?= SITE_URL ?>/admin/messages.php" <?= strpos($_SERVER['PHP_SELF'],'messages')!==false?'class="active"':'' ?>>✉️ Messages</a>
    <a href="<?= SITE_URL ?>/admin/settings.php" <?= strpos($_SERVER['PHP_SELF'],'settings')!==false?'class="active"':'' ?>>⚙️ Settings</a>
    <a href="<?= SITE_URL ?>/index.php" target="_blank">🌐 View Website</a>
    <a href="<?= SITE_URL ?>/admin/logout.php" style="margin-top:20px;color:#fca5a5;">🚪 Logout</a>
  </nav>
</div>
