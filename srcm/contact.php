<?php
require_once __DIR__ . '/includes/config.php';
$db = getDB();
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = clean($_POST['name'] ?? '');
    $phone   = clean($_POST['phone'] ?? '');
    $email   = clean($_POST['email'] ?? '');
    $subject = clean($_POST['subject'] ?? '');
    $message = clean($_POST['message'] ?? '');

    if ($name && $phone && $message) {
        $stmt = $db->prepare("INSERT INTO contact_messages (name,phone,email,subject,message) VALUES (?,?,?,?,?)");
        $stmt->execute([$name, $phone, $email, $subject, $message]);
        $success = "✅ आपका संदेश सफलतापूर्वक भेज दिया गया है! हम जल्द ही आपसे संपर्क करेंगे।";
    } else {
        $error = "❌ कृपया सभी आवश्यक जानकारी भरें।";
    }
}

$pageTitle = 'Contact Us';
include __DIR__ . '/includes/header.php';
?>

<div class="page-banner">
  <div class="container">
    <h1>Contact Us</h1>
    <div class="breadcrumb">Home → <span>Contact Us</span></div>
  </div>
</div>

<div class="page-content">
  <div class="container">
    <div class="content-grid">
      <div>
        <div class="contact-form-box">
          <h2 style="font-family:'Playfair Display',serif;color:var(--primary);margin-bottom:20px;font-size:24px;">Send Us a Message</h2>
          <?php if($success): ?><div class="form-success"><?= $success ?></div><?php endif; ?>
          <?php if($error): ?><div class="form-error"><?= $error ?></div><?php endif; ?>
          <form method="POST">
            <div class="form-row">
              <div class="form-group"><label>Your Name *</label><input type="text" name="name" placeholder="Full Name" required></div>
              <div class="form-group"><label>Phone Number *</label><input type="tel" name="phone" placeholder="+91-XXXXXXXXXX" required></div>
            </div>
            <div class="form-row">
              <div class="form-group"><label>Email Address</label><input type="email" name="email" placeholder="your@email.com"></div>
              <div class="form-group"><label>Subject</label>
                <select name="subject">
                  <option>Admission Enquiry</option>
                  <option>Fee Related</option>
                  <option>Result Query</option>
                  <option>TC Request</option>
                  <option>Other</option>
                </select>
              </div>
            </div>
            <div class="form-group"><label>Message *</label><textarea name="message" rows="5" placeholder="Type your message..." required></textarea></div>
            <button type="submit" class="btn-primary">Send Message →</button>
          </form>
        </div>
      </div>
      <div>
        <div class="sidebar-card">
          <div class="card-header">📍 College Address</div>
          <div style="padding:18px;font-size:14px;line-height:2;">
            <strong><?= getSetting('college_name') ?></strong><br>
            <?= getSetting('college_address') ?><br><br>
            📞 <a href="tel:<?= getSetting('college_phone') ?>" style="color:var(--primary);"><?= getSetting('college_phone') ?></a><br>
            ✉️ <a href="mailto:<?= getSetting('college_email') ?>" style="color:var(--primary);"><?= getSetting('college_email') ?></a><br><br>
            ⏰ <?= getSetting('college_timing') ?>
          </div>
        </div>
        <div class="sidebar-card">
          <div class="card-header">📱 Follow Us</div>
          <div style="padding:14px;">
            <a href="<?= getSetting('college_facebook') ?>" target="_blank" class="ql-link">📘 Facebook Page</a>
            <a href="<?= getSetting('college_instagram') ?>" class="ql-link">📷 Instagram</a>
            <a href="<?= getSetting('college_youtube') ?>" class="ql-link">▶️ YouTube</a>
          </div>
        </div>
        <div class="sidebar-card">
          <div class="card-header">🗺️ Location</div>
          <div style="padding:15px;">
            <div style="background:var(--light);height:180px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;margin-bottom:12px;">
              <div style="font-size:36px;">📍</div>
              <div style="font-size:13px;font-weight:700;color:var(--primary);">SRCM Inter College</div>
              <div style="font-size:12px;color:var(--muted);">Jainpur, Gorakhpur, UP</div>
            </div>
            <a href="https://maps.google.com/?q=Jainpur+Gorakhpur+UP" target="_blank" class="btn-primary" style="display:block;text-align:center;">📍 View on Google Maps</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
