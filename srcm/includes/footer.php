<?php require_once __DIR__ . '/config.php'; ?>
<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-about">
        <h3><?= getSetting('college_name') ?></h3>
        <p><?= getSetting('about_text') ?></p>
        <div class="footer-social">
          <a href="<?= getSetting('college_facebook') ?>" target="_blank">📘 Facebook</a>
          <a href="<?= getSetting('college_instagram') ?>">📷 Instagram</a>
          <a href="<?= getSetting('college_youtube') ?>">▶️ YouTube</a>
        </div>
      </div>
      <div class="footer-col">
        <h4>About</h4>
        <ul>
          <li><a href="<?= SITE_URL ?>/about.php">About SRCM</a></li>
          <li><a href="<?= SITE_URL ?>/vision.php">Vision & Mission</a></li>
          <li><a href="<?= SITE_URL ?>/principal.php">Principal's Message</a></li>
          <li><a href="<?= SITE_URL ?>/chairman.php">Chairman's Message</a></li>
          <li><a href="<?= SITE_URL ?>/honours.php">Honours & Awards</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Academics</h4>
        <ul>
          <li><a href="<?= SITE_URL ?>/curriculum.php">Curriculum</a></li>
          <li><a href="<?= SITE_URL ?>/exam.php">Examination</a></li>
          <li><a href="<?= SITE_URL ?>/calendar.php">Academic Calendar</a></li>
          <li><a href="<?= SITE_URL ?>/timing.php">College Timing</a></li>
          <li><a href="<?= SITE_URL ?>/results.php">Results</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="<?= SITE_URL ?>/admission.php">Admission</a></li>
          <li><a href="<?= SITE_URL ?>/fee.php">Fee Structure</a></li>
          <li><a href="<?= SITE_URL ?>/gallery.php">Photo Gallery</a></li>
          <li><a href="<?= SITE_URL ?>/tc-search.php">TC Search</a></li>
          <li><a href="<?= SITE_URL ?>/contact.php">Contact Us</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© <?= date('Y') ?> <?= getSetting('college_name') ?>, Jainpur, Gorakhpur, UP | U.P. Board Affiliated | School Code: <?= getSetting('college_school_code') ?></p>
    </div>
  </div>
</footer>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body>
</html>
