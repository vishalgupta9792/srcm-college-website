<?php
require_once __DIR__ . '/includes/config.php';
$db = getDB();
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sname = clean($_POST['student_name']??'');
    $fname = clean($_POST['father_name']??'');
    $phone = clean($_POST['phone']??'');
    $class = clean($_POST['class_applying']??'');
    if ($sname && $fname && $phone && $class) {
        $stmt = $db->prepare("INSERT INTO enquiries (student_name,father_name,phone,email,class_applying,stream,message) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$sname,$fname,$phone,clean($_POST['email']??''),$class,clean($_POST['stream']??''),clean($_POST['message']??'')]);
        $success = "✅ आपकी enquiry सफलतापूर्वक submit हो गई है! हम जल्द ही आपसे संपर्क करेंगे।";
    } else { $error = "❌ कृपया सभी आवश्यक जानकारी भरें।"; }
}

$fees = $db->query("SELECT * FROM fee_structure WHERE is_active=1")->fetchAll();
$pageTitle = 'Admission 2025-26';
include __DIR__ . '/includes/header.php';
?>
<div class="page-banner"><div class="container"><h1>Admission 2025-26</h1><div class="breadcrumb">Home → <span>Admission</span></div></div></div>
<div class="page-content"><div class="container"><div class="content-grid">
  <div>
    <div class="content-box">
      <h2>Admission Rules & Procedure</h2>
      <div class="info-box"><p>🎓 <strong>Admissions Open for Session 2025-26</strong> — Class 9th to 12th | Science, Arts & Commerce</p></div>
      <h3>Eligibility</h3>
      <ul>
        <li>Class 9th: Passed Class 8th from any recognised school</li>
        <li>Class 11th Science: Min. 50% in Class 10th U.P. Board</li>
        <li>Class 11th Arts/Commerce: Passed Class 10th from any board</li>
        <li>TC students must provide Transfer Certificate</li>
      </ul>
      <h3>Documents Required</h3>
      <ul>
        <li>Marksheet of previous class (Original + Photocopy)</li>
        <li>Transfer Certificate from previous school</li>
        <li>Aadhar Card (Student + Father/Mother)</li>
        <li>4 Passport size photographs</li>
        <li>Caste Certificate (if applicable)</li>
      </ul>
      <h3>Fee Structure 2025-26</h3>
      <table class="data-table">
        <thead><tr><th>Class</th><th>Stream</th><th>Admission Fee</th><th>Monthly Fee</th></tr></thead>
        <tbody>
          <?php foreach($fees as $f): ?>
          <tr><td><?= clean($f['class']) ?></td><td><?= clean($f['stream']) ?></td><td>₹ <?= $f['admission_fee'] ?></td><td>₹ <?= $f['monthly_fee'] ?>/month</td></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <h3>Online Enquiry Form</h3>
      <?php if($success): ?><div class="form-success"><?= $success ?></div><?php endif; ?>
      <?php if($error): ?><div class="form-error"><?= $error ?></div><?php endif; ?>
      <form method="POST" style="background:var(--light);padding:22px;border-radius:10px;margin-top:10px;">
        <div class="form-row">
          <div class="form-group"><label>Student Name *</label><input type="text" name="student_name" required placeholder="Full Name"></div>
          <div class="form-group"><label>Father's Name *</label><input type="text" name="father_name" required placeholder="Father's Full Name"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Phone *</label><input type="tel" name="phone" required placeholder="+91-XXXXXXXXXX"></div>
          <div class="form-group"><label>Email</label><input type="email" name="email" placeholder="optional"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Class Applying For *</label>
            <select name="class_applying" required>
              <option value="">-- Select Class --</option>
              <option>Class 9</option><option>Class 10</option><option>Class 11</option><option>Class 12</option>
            </select>
          </div>
          <div class="form-group"><label>Stream</label>
            <select name="stream"><option>Science</option><option>Arts</option><option>Commerce</option></select>
          </div>
        </div>
        <div class="form-group"><label>Message / Query</label><textarea name="message" rows="3" placeholder="Any specific query..."></textarea></div>
        <button type="submit" class="btn-primary">Submit Enquiry →</button>
      </form>
    </div>
  </div>
  <div>
    <div class="sidebar-card"><div class="card-header">📅 Important Dates</div>
      <div style="padding:16px;font-size:13.5px;line-height:2.2;"><b>Admission Start:</b> Jan 2025<br><b>Last Date:</b> July 2025<br><b>Session Start:</b> April 2025<br><b>Board Exam:</b> March 2026</div>
    </div>
    <div class="sidebar-card"><div class="card-header">📞 Contact for Admission</div>
      <div style="padding:16px;font-size:13.5px;line-height:2;">
        <?= getSetting('college_phone') ?><br><?= getSetting('college_email') ?><br>
        Mon–Sat: 8 AM – 1 PM
      </div>
    </div>
  </div>
</div></div></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
