<?php
require_once __DIR__ . '/includes/config.php';
$db = getDB();
$result = null;
$searched = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['tc'])) {
    $tc_num = clean($_POST['tc_number'] ?? $_GET['tc'] ?? '');
    $searched = true;
    if ($tc_num) {
        $stmt = $db->prepare("SELECT * FROM tc_records WHERE tc_number = ? AND is_active = 1");
        $stmt->execute([$tc_num]);
        $result = $stmt->fetch();
        if (!$result) $error = "TC Number <strong>$tc_num</strong> ke liye koi record nahi mila. Kripya college office se sampark karein.";
    } else {
        $error = "Kripya TC Number enter karein.";
    }
}

$pageTitle = 'TC Search';
include __DIR__ . '/includes/header.php';
?>

<div class="page-banner">
  <div class="container">
    <h1>Transfer Certificate Search</h1>
    <div class="breadcrumb">Home → Academic → <span>TC Search</span></div>
  </div>
</div>

<div class="page-content">
  <div class="container">
    <div class="content-grid">
      <div>
        <div class="content-box">
          <h2>Search Your TC</h2>
          <p>Apna TC Number enter karke apna Transfer Certificate record dekh sakte hain. TC Number college office se prapt karein.</p>
          <form method="POST" style="margin:25px 0;">
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
              <div class="form-group" style="flex:1;margin:0;">
                <input type="text" name="tc_number" placeholder="Enter TC Number (e.g. TC-2024-001)" value="<?= clean($_POST['tc_number'] ?? $_GET['tc'] ?? '') ?>" style="width:100%;padding:12px 16px;border:2px solid var(--border);border-radius:6px;font-size:15px;font-family:inherit;">
              </div>
              <button type="submit" class="btn-primary" style="padding:12px 28px;white-space:nowrap;">🔍 Search TC</button>
            </div>
          </form>

          <?php if($error): ?>
          <div class="form-error"><?= $error ?></div>
          <?php endif; ?>

          <?php if($result): ?>
          <div class="tc-result">
            <h3 style="color:var(--primary);font-family:'Playfair Display',serif;margin-bottom:15px;">✅ TC Record Found</h3>
            <table>
              <tr><td>TC Number</td><td><strong><?= clean($result['tc_number']) ?></strong></td></tr>
              <tr><td>Student Name</td><td><?= clean($result['student_name']) ?></td></tr>
              <tr><td>Father's Name</td><td><?= clean($result['father_name']) ?></td></tr>
              <tr><td>Class</td><td><?= clean($result['class']) ?></td></tr>
              <tr><td>Stream</td><td><?= clean($result['stream']) ?></td></tr>
              <tr><td>Admission Year</td><td><?= clean($result['admission_year']) ?></td></tr>
              <tr><td>Leaving Year</td><td><?= clean($result['leaving_year']) ?></td></tr>
              <tr><td>Issue Date</td><td><?= $result['issue_date'] ? formatDate($result['issue_date']) : 'N/A' ?></td></tr>
            </table>
            <div class="info-box" style="margin-top:15px;"><p>📌 Original TC prapt karne ke liye college office mein sampark karein.</p></div>
          </div>
          <?php endif; ?>

          <div class="info-box" style="margin-top:20px;">
            <p>📞 TC ke liye sampark karein: <strong><?= getSetting('college_phone') ?></strong><br>
            Office Timing: Monday–Saturday, 8:00 AM – 1:00 PM</p>
          </div>
        </div>
      </div>
      <div>
        <div class="sidebar-card">
          <div class="card-header">📋 TC Related Info</div>
          <div style="padding:16px;font-size:13.5px;line-height:2;">
            <b>TC Fee:</b> ₹50<br>
            <b>Processing Time:</b> 3-5 Working Days<br>
            <b>Required:</b> Application Form<br><br>
            <b>Documents:</b><br>
            • Admission Receipt<br>
            • Last Fee Receipt<br>
            • ID Proof
          </div>
        </div>
        <div class="sidebar-card">
          <div class="card-header">🔗 Quick Links</div>
          <div style="padding:14px;">
            <a href="<?= SITE_URL ?>/admission.php" class="ql-link">📝 Admission</a>
            <a href="<?= SITE_URL ?>/results.php" class="ql-link">🏆 Results</a>
            <a href="<?= SITE_URL ?>/contact.php" class="ql-link">📞 Contact</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
