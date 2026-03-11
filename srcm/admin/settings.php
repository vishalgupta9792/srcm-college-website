<?php
$adminTitle = 'Settings';
require_once __DIR__ . '/includes/admin_header.php';
$db = getDB();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['college_name','college_tagline','college_address','college_phone','college_email',
               'college_school_code','college_board','college_facebook','college_instagram',
               'college_youtube','college_timing','about_text','admission_status',
               'meta_title','meta_description'];
    foreach ($fields as $f) {
        if (isset($_POST[$f])) {
            $stmt = $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value=?");
            $stmt->execute([$f, clean($_POST[$f]), clean($_POST[$f])]);
        }
    }
    $msg = 'Settings saved successfully!';
}

// Fetch all settings
$allSettings = [];
foreach ($db->query("SELECT setting_key, setting_value FROM settings") as $row) {
    $allSettings[$row['setting_key']] = $row['setting_value'];
}
$s = fn($k) => $allSettings[$k] ?? '';
?>

<div class="admin-main">
  <div class="admin-topbar"><h1>⚙️ Site Settings</h1></div>

  <?php if($msg): ?><div class="form-success alert-auto" style="margin-bottom:20px;padding:12px 18px;border-radius:8px;">✅ <?= $msg ?></div><?php endif; ?>

  <form method="POST">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:25px;">

      <div class="admin-card">
        <h2>🏫 College Information</h2>
        <div class="form-group"><label>College Name</label><input type="text" name="college_name" value="<?= $s('college_name') ?>"></div>
        <div class="form-group"><label>Tagline</label><input type="text" name="college_tagline" value="<?= $s('college_tagline') ?>"></div>
        <div class="form-group"><label>School Code (U.P. Board)</label><input type="text" name="college_school_code" value="<?= $s('college_school_code') ?>"></div>
        <div class="form-group"><label>Board Affiliation</label><input type="text" name="college_board" value="<?= $s('college_board') ?>"></div>
        <div class="form-group"><label>Address</label><textarea name="college_address" rows="2"><?= $s('college_address') ?></textarea></div>
        <div class="form-group"><label>Phone</label><input type="text" name="college_phone" value="<?= $s('college_phone') ?>"></div>
        <div class="form-group"><label>Email</label><input type="email" name="college_email" value="<?= $s('college_email') ?>"></div>
        <div class="form-group"><label>Office Timing</label><input type="text" name="college_timing" value="<?= $s('college_timing') ?>"></div>
      </div>

      <div>
        <div class="admin-card">
          <h2>📱 Social Media</h2>
          <div class="form-group"><label>Facebook URL</label><input type="url" name="college_facebook" value="<?= $s('college_facebook') ?>"></div>
          <div class="form-group"><label>Instagram URL</label><input type="url" name="college_instagram" value="<?= $s('college_instagram') ?>"></div>
          <div class="form-group"><label>YouTube URL</label><input type="url" name="college_youtube" value="<?= $s('college_youtube') ?>"></div>
        </div>

        <div class="admin-card">
          <h2>📝 Admission Status</h2>
          <div class="form-group"><label>Admission Status</label>
            <select name="admission_status">
              <option value="open" <?= $s('admission_status')=='open'?'selected':'' ?>>🟢 Open — Show Admission Banner</option>
              <option value="closed" <?= $s('admission_status')=='closed'?'selected':'' ?>>🔴 Closed — Hide Banner</option>
            </select>
          </div>
        </div>

        <div class="admin-card">
          <h2>🔍 SEO Settings</h2>
          <div class="form-group"><label>Meta Title</label><input type="text" name="meta_title" value="<?= $s('meta_title') ?>"></div>
          <div class="form-group"><label>Meta Description</label><textarea name="meta_description" rows="3"><?= $s('meta_description') ?></textarea></div>
        </div>
      </div>
    </div>

    <div class="admin-card">
      <h2>📄 About College Text (Homepage)</h2>
      <div class="form-group"><textarea name="about_text" rows="4"><?= $s('about_text') ?></textarea></div>
    </div>

    <div style="text-align:center;padding:10px 0 30px;">
      <button type="submit" class="btn-primary" style="padding:14px 40px;font-size:16px;">💾 Save All Settings</button>
    </div>
  </form>
</div>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body></html>
