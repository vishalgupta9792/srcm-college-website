<?php
require_once __DIR__ . '/includes/config.php';
$db = getDB();

// Sliders
$sliders = $db->query("SELECT * FROM sliders WHERE is_active=1 ORDER BY sort_order")->fetchAll();

// Announcements
$announcements = $db->query("SELECT * FROM announcements WHERE is_active=1 ORDER BY created_at DESC LIMIT 7")->fetchAll();

// Thoughts
$thoughts = $db->query("SELECT thought_text FROM thoughts WHERE is_active=1")->fetchAll(PDO::FETCH_COLUMN);

// News
$collegeNews = $db->query("SELECT * FROM news WHERE is_active=1 AND category='college' ORDER BY news_date DESC LIMIT 5")->fetchAll();
$upNews = $db->query("SELECT * FROM news WHERE is_active=1 AND category='upboard' ORDER BY news_date DESC LIMIT 5")->fetchAll();

// Gallery preview
$galleryItems = $db->query("SELECT g.*, gc.name as cat_name FROM gallery g LEFT JOIN gallery_categories gc ON g.category_id=gc.id WHERE g.is_active=1 ORDER BY g.sort_order LIMIT 8")->fetchAll();

// Staff messages (top 3)
$staff = $db->query("SELECT * FROM staff WHERE is_active=1 ORDER BY sort_order LIMIT 3")->fetchAll();

$pageTitle = 'Home';
include __DIR__ . '/includes/header.php';
?>

<!-- SLIDER -->
<div class="slider">
  <div class="slides" id="slides">
    <?php foreach($sliders as $sl): ?>
    <div class="slide">
      <?php if($sl['image_path']): ?>
        <div class="slide-bg" style="background-image:url('<?= UPLOAD_URL . $sl['image_path'] ?>')"></div>
        <div class="slide-overlay"></div>
      <?php else: ?>
        <div class="slide-bg" style="<?= $sl['bg_gradient'] ? 'background:'.$sl['bg_gradient'] : 'background:var(--primary)' ?>"></div>
      <?php endif; ?>
      <div class="slide-content">
        <h2><?= clean($sl['title']) ?></h2>
        <p><?= clean($sl['subtitle']) ?></p>
        <?php if($sl['btn_text']): ?>
        <a href="<?= SITE_URL ?>/<?= $sl['btn_link'] ?>" class="btn"><?= clean($sl['btn_text']) ?></a>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <button class="slider-btn prev" onclick="changeSlide(-1)">&#8249;</button>
  <button class="slider-btn next" onclick="changeSlide(1)">&#8250;</button>
  <div class="dots">
    <?php foreach($sliders as $i => $sl): ?>
    <button class="dot <?= $i==0?'active':'' ?>" onclick="goToSlide(<?= $i ?>)"></button>
    <?php endforeach; ?>
  </div>
</div>

<!-- TICKER -->
<div class="ticker">
  <div class="container">
    <div class="ticker-inner">
      <div class="ticker-label">📢 LATEST NEWS</div>
      <div class="ticker-track">
        <span class="ticker-text">
          <?php foreach($announcements as $a): ?>
          🔔 <?= clean($a['title']) ?> &nbsp;&nbsp;|&nbsp;&nbsp;
          <?php endforeach; ?>
        </span>
      </div>
    </div>
  </div>
</div>

<!-- ADMISSION BANNER -->
<?php if(getSetting('admission_status') == 'open'): ?>
<div class="admission-banner">
  <div class="container">
    <div class="admission-inner">
      <div>
        <h3>🎓 Admissions Open — Session 2025-26</h3>
        <p>Class 9th, 10th, 11th & 12th | Science, Arts & Commerce | U.P. Board Affiliated | School Code: 1326</p>
      </div>
      <a href="<?= SITE_URL ?>/admission.php" class="btn-white">Register Now →</a>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- MAIN CONTENT -->
<div class="container">
  <div class="main-grid">

    <!-- MAIN COLUMN -->
    <div>
      <!-- WELCOME -->
      <div class="welcome-section">
        <div class="welcome-img">
          <div class="welcome-img-placeholder">
            <div style="font-size:85px;">🏫</div>
            <div style="color:white;font-size:20px;font-weight:700;font-family:'Playfair Display',serif;"><?= getSetting('college_name') ?></div>
            <div style="color:#d4e6ff;font-size:14px;">Jainpur, Gorakhpur, U.P.</div>
            <div style="color:var(--secondary);font-size:13px;font-weight:600;">School Code: <?= getSetting('college_school_code') ?></div>
          </div>
          <div class="welcome-badge">U.P. Board | Code: <?= getSetting('college_school_code') ?></div>
        </div>
        <div class="welcome-text">
          <div class="welcome-tag">Welcome to</div>
          <h2 class="welcome-title"><?= getSetting('college_name') ?></h2>
          <p><?= getSetting('about_text') ?></p>
          <p>We offer classes from 9th to 12th standard in Science, Arts and Commerce streams. Our dedicated faculty and disciplined environment ensure that every student achieves their academic potential and excels in U.P. Board examinations.</p>
          <ul class="feature-list">
            <li>U.P. Board Affiliated — School Code <?= getSetting('college_school_code') ?></li>
            <li>Science, Arts & Commerce Streams</li>
            <li>Experienced & Dedicated Teaching Staff</li>
            <li>Well-Equipped Science & Computer Laboratory</li>
            <li>Spacious Library with Reference Books</li>
            <li>Safe, Peaceful & Disciplined Campus</li>
          </ul>
          <a href="<?= SITE_URL ?>/about.php" class="btn-primary" style="margin-top:15px;">Read More →</a>
        </div>
      </div>
    </div>

    <!-- SIDEBAR -->
    <div>
      <!-- THOUGHT -->
      <div class="sidebar-card">
        <div class="card-header">💡 Thought of the Day</div>
        <div class="thought-box">
          <div class="thought-text" id="thought-text"
               data-thoughts='<?= json_encode($thoughts) ?>'>
            <?= $thoughts[0] ?? '"ज्ञान ही सबसे बड़ा धन है।"' ?>
          </div>
        </div>
      </div>

      <!-- ANNOUNCEMENTS -->
      <div class="sidebar-card">
        <div class="card-header">📢 Announcements</div>
        <ul class="ann-list">
          <?php foreach($announcements as $a): ?>
          <li>
            <span class="ann-date"><?= formatDate($a['ann_date']) ?></span>
            <?= clean($a['title']) ?>
            <?php if($a['is_new']): ?><span class="ann-new">NEW</span><?php endif; ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- QUICK LINKS -->
      <div class="sidebar-card">
        <div class="card-header">🔗 Quick Links</div>
        <div style="padding:14px;">
          <a href="<?= SITE_URL ?>/admission.php" class="ql-link">📄 Admission Form</a>
          <a href="<?= SITE_URL ?>/fee.php" class="ql-link">💰 Fee Structure</a>
          <a href="<?= SITE_URL ?>/exam.php" class="ql-link">📅 Exam Schedule</a>
          <a href="<?= SITE_URL ?>/results.php" class="ql-link">🏆 View Results</a>
          <a href="<?= SITE_URL ?>/tc-search.php" class="ql-link">📋 TC Search</a>
          <a href="<?= SITE_URL ?>/timing.php" class="ql-link">⏰ College Timing</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- STATS -->
<div class="stats-section">
  <div class="container">
    <div class="stats-row">
      <div class="stat-card" data-count="500" data-suffix="+">
        <div class="stat-num"><span class="count-num">500+</span></div>
        <div class="stat-label">Students Enrolled</div>
      </div>
      <div class="stat-card" data-count="30" data-suffix="+">
        <div class="stat-num"><span class="count-num">30+</span></div>
        <div class="stat-label">Teachers & Staff</div>
      </div>
      <div class="stat-card" data-count="100" data-suffix="%">
        <div class="stat-num"><span class="count-num">100%</span></div>
        <div class="stat-label">Board Result</div>
      </div>
      <div class="stat-card" data-count="3">
        <div class="stat-num"><span class="count-num">3</span></div>
        <div class="stat-label">Streams Available</div>
      </div>
    </div>
  </div>
</div>

<!-- FACILITIES -->
<div class="section section-bg">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Our Infrastructure</div>
      <h2 class="section-title">College Facilities</h2>
      <p class="section-desc">Modern facilities to support quality education and holistic student development at SRCM Inter College, Jainpur.</p>
    </div>
    <div class="facilities-grid">
      <div class="facility-card">
        <div class="facility-img" style="background:linear-gradient(135deg,#1a3a5c,#2d6a8a);">🖥️</div>
        <div class="facility-body">
          <h3>Computer Lab</h3>
          <p>Fully functional lab with internet connectivity for practical IT training and digital literacy.</p>
          <a href="<?= SITE_URL ?>/facilities.php">Read More →</a>
        </div>
      </div>
      <div class="facility-card">
        <div class="facility-img" style="background:linear-gradient(135deg,#1a5c2a,#2d8a4a);">🔬</div>
        <div class="facility-body">
          <h3>Science Laboratory</h3>
          <p>Physics, Chemistry and Biology labs for hands-on U.P. Board practical experiments.</p>
          <a href="<?= SITE_URL ?>/facilities.php">Read More →</a>
        </div>
      </div>
      <div class="facility-card">
        <div class="facility-img" style="background:linear-gradient(135deg,#5c1a1a,#8a2d2d);">📚</div>
        <div class="facility-body">
          <h3>Library</h3>
          <p>Spacious library with textbooks, reference books and study material for all streams.</p>
          <a href="<?= SITE_URL ?>/facilities.php">Read More →</a>
        </div>
      </div>
      <div class="facility-card">
        <div class="facility-img" style="background:linear-gradient(135deg,#3d1a5c,#5c2d8a);">⚽</div>
        <div class="facility-body">
          <h3>Sports Ground</h3>
          <p>Large ground for cricket, volleyball, kabaddi, athletics and other sports activities.</p>
          <a href="<?= SITE_URL ?>/facilities.php">Read More →</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MESSAGES -->
<div class="section">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Leadership</div>
      <h2 class="section-title">Messages from Our Leaders</h2>
    </div>
    <div class="messages-grid">
      <div class="msg-card">
        <div class="msg-photo">👨‍💼</div>
        <h3>माननीय प्रबंधक महोदय</h3>
        <div class="role">Manager / Chairman</div>
        <p>"शिक्षा वह शस्त्र है जिससे आप दुनिया बदल सकते हैं। SRCM Inter College में हम विद्यार्थियों का सर्वांगीण विकास सुनिश्चित करते हैं।"</p>
        <a href="<?= SITE_URL ?>/chairman.php">Read More →</a>
      </div>
      <div class="msg-card">
        <div class="msg-photo">👨‍🏫</div>
        <h3>श्रद्धेय प्रधानाचार्य महोदय</h3>
        <div class="role">Principal</div>
        <p>"प्रिय छात्रों, SRCM Inter College में आपका स्वागत है। हमारा लक्ष्य है कि प्रत्येक विद्यार्थी अपनी पूर्ण क्षमता का विकास करे।"</p>
        <a href="<?= SITE_URL ?>/principal.php">Read More →</a>
      </div>
      <div class="msg-card">
        <div class="msg-photo">👨‍🏫</div>
        <h3>शिक्षक परिवार</h3>
        <div class="role">Teaching Staff</div>
        <p>"हम सभी शिक्षकगण विद्यार्थियों के उज्ज्वल भविष्य के लिए समर्पित हैं। बोर्ड परीक्षा में श्रेष्ठ परिणाम हमारा लक्ष्य है।"</p>
        <a href="<?= SITE_URL ?>/staff.php">Meet Faculty →</a>
      </div>
    </div>
  </div>
</div>

<!-- UPDATES -->
<div class="section section-bg">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Updates</div>
      <h2 class="section-title">Latest Notices & UP Board Updates</h2>
    </div>
    <div class="updates-grid">
      <div class="update-list">
        <div class="card-header">📢 College Notices</div>
        <ul>
          <?php foreach($collegeNews as $n): ?>
          <li>
            <span class="udate"><?= date('d M Y', strtotime($n['news_date'])) ?></span>
            <?php if($n['link']): ?><a href="<?= $n['link'] ?>" target="_blank"><?= clean($n['title']) ?></a>
            <?php else: ?><span><?= clean($n['title']) ?></span><?php endif; ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="update-list">
        <div class="card-header" style="background:var(--accent);">📋 U.P. Board Updates</div>
        <ul>
          <?php foreach($upNews as $n): ?>
          <li>
            <span class="udate"><?= date('d M Y', strtotime($n['news_date'])) ?></span>
            <?php if($n['link']): ?><a href="<?= $n['link'] ?>" target="_blank"><?= clean($n['title']) ?></a>
            <?php else: ?><span><?= clean($n['title']) ?></span><?php endif; ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- GALLERY PREVIEW -->
<?php if(!empty($galleryItems)): ?>
<div class="section">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Memories</div>
      <h2 class="section-title">Photo Gallery</h2>
    </div>
    <div class="gallery-grid">
      <?php foreach($galleryItems as $gi): ?>
      <div class="gallery-item" <?= $gi['image_path'] ? 'data-src="'.UPLOAD_URL.$gi['image_path'].'" data-title="'.clean($gi['title']).'"' : '' ?>>
        <?php if($gi['image_path']): ?>
          <img src="<?= UPLOAD_URL . $gi['image_path'] ?>" alt="<?= clean($gi['title']) ?>">
        <?php else: ?>📸<?php endif; ?>
        <div class="gallery-caption"><?= clean($gi['title'] ?? $gi['cat_name']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:25px;">
      <a href="<?= SITE_URL ?>/gallery.php" class="btn-primary">View Full Gallery →</a>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- CONTACT -->
<div class="contact-section">
  <div class="container">
    <div class="section-header" style="margin-bottom:30px;">
      <div class="section-tag" style="color:var(--secondary);">Get in Touch</div>
      <h2 class="section-title" style="color:white;">Contact SRCM Inter College</h2>
    </div>
    <div class="contact-grid">
      <div class="contact-item"><div class="contact-icon">📍</div><h3>Address</h3><p><?= getSetting('college_address') ?></p></div>
      <div class="contact-item"><div class="contact-icon">📞</div><h3>Phone & Email</h3><p><a href="tel:<?= getSetting('college_phone') ?>"><?= getSetting('college_phone') ?></a><br><a href="mailto:<?= getSetting('college_email') ?>"><?= getSetting('college_email') ?></a></p></div>
      <div class="contact-item"><div class="contact-icon">⏰</div><h3>Office Timings</h3><p><?= getSetting('college_timing') ?></p></div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
