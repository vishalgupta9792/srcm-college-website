<?php
require_once __DIR__ . '/config.php';
$collegeName = getSetting('college_name');
$collegeTagline = getSetting('college_tagline');
$collegePhone = getSetting('college_phone');
$collegeEmail = getSetting('college_email');
$collegeFacebook = getSetting('college_facebook');
$metaTitle = isset($pageTitle) ? $pageTitle . ' | ' . $collegeName : getSetting('meta_title');
$metaDesc = getSetting('meta_description');
?>
<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $metaTitle ?></title>
<meta name="description" content="<?= $metaDesc ?>">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
  <div class="container">
    <div class="top-left">
      📞 <a href="tel:<?= $collegePhone ?>"><?= $collegePhone ?></a>
      &nbsp;|&nbsp;
      ✉️ <a href="mailto:<?= $collegeEmail ?>"><?= $collegeEmail ?></a>
      &nbsp;|&nbsp;
      📍 Jainpur, Gorakhpur, UP
    </div>
    <div class="socials">
      <a href="<?= $collegeFacebook ?>" target="_blank">📘 Facebook</a>
      <a href="<?= getSetting('college_instagram') ?>">📷 Instagram</a>
      <a href="<?= getSetting('college_youtube') ?>">▶️ YouTube</a>
    </div>
  </div>
</div>

<!-- HEADER -->
<header>
  <div class="container">
    <div class="header-inner">
      <a href="<?= SITE_URL ?>/index.php" class="logo-area">
        <div class="logo-icon">S</div>
        <div class="logo-text">
          <h1><?= $collegeName ?></h1>
          <p>Jainpur, Gorakhpur — Uttar Pradesh</p>
          <p class="est">U.P. Board Affiliated | School Code: <?= getSetting('college_school_code') ?></p>
        </div>
      </a>
      <div>
        <?php if(getSetting('admission_status') == 'open'): ?>
        <a href="<?= SITE_URL ?>/admission.php" class="btn-admission">📝 Admission Open 2025-26</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>

<!-- NAVIGATION -->
<nav>
  <div class="container">
    <button class="mobile-toggle" onclick="document.querySelector('.nav-inner').classList.toggle('open')">☰ Menu</button>
    <div class="nav-inner">
      <a href="<?= SITE_URL ?>/index.php" <?= (basename($_SERVER['PHP_SELF'])=='index.php')?'class="active"':'' ?>>🏠 Home</a>

      <div class="dropdown">
        <span>About Us ▾</span>
        <div class="dropdown-menu">
          <a href="<?= SITE_URL ?>/about.php">About SRCM College</a>
          <a href="<?= SITE_URL ?>/vision.php">Vision & Mission</a>
          <a href="<?= SITE_URL ?>/principal.php">Principal's Message</a>
          <a href="<?= SITE_URL ?>/chairman.php">Chairman's Message</a>
          <a href="<?= SITE_URL ?>/rules.php">Rules & Discipline</a>
          <a href="<?= SITE_URL ?>/honours.php">Honours & Awards</a>
        </div>
      </div>

      <div class="dropdown">
        <span>Admissions ▾</span>
        <div class="dropdown-menu">
          <a href="<?= SITE_URL ?>/admission.php">Admission Rules</a>
          <a href="<?= SITE_URL ?>/fee.php">Fee Structure</a>
          <a href="<?= SITE_URL ?>/uniform.php">Uniform Details</a>
        </div>
      </div>

      <div class="dropdown">
        <span>Academic ▾</span>
        <div class="dropdown-menu">
          <a href="<?= SITE_URL ?>/curriculum.php">Curriculum</a>
          <a href="<?= SITE_URL ?>/timing.php">College Timing</a>
          <a href="<?= SITE_URL ?>/exam.php">Examination</a>
          <a href="<?= SITE_URL ?>/calendar.php">Academic Calendar</a>
          <a href="<?= SITE_URL ?>/activities.php">Co-Curricular Activities</a>
          <a href="<?= SITE_URL ?>/tc-search.php">TC Search</a>
        </div>
      </div>

      <div class="dropdown">
        <span>Facilities ▾</span>
        <div class="dropdown-menu">
          <a href="<?= SITE_URL ?>/facilities.php">All Facilities</a>
          <a href="<?= SITE_URL ?>/staff.php">Our Faculty</a>
        </div>
      </div>

      <div class="dropdown">
        <span>Events ▾</span>
        <div class="dropdown-menu">
          <a href="<?= SITE_URL ?>/gallery.php">Photo Gallery</a>
          <a href="<?= SITE_URL ?>/announcements.php">Announcements</a>
        </div>
      </div>

      <a href="<?= SITE_URL ?>/results.php" <?= (basename($_SERVER['PHP_SELF'])=='results.php')?'class="active"':'' ?>>Results</a>
      <a href="<?= SITE_URL ?>/contact.php" <?= (basename($_SERVER['PHP_SELF'])=='contact.php')?'class="active"':'' ?>>Contact Us</a>
    </div>
  </div>
</nav>
