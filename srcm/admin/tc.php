<?php
$adminTitle = 'TC Records';
require_once __DIR__ . '/includes/admin_header.php';
$db = getDB();
$msg = '';

if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='add') {
    try {
        $stmt = $db->prepare("INSERT INTO tc_records (tc_number,student_name,father_name,class,stream,admission_year,leaving_year,issue_date) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([clean($_POST['tc_number']),clean($_POST['student_name']),clean($_POST['father_name']),clean($_POST['class']),clean($_POST['stream']),clean($_POST['admission_year']),clean($_POST['leaving_year']),$_POST['issue_date']]);
        $msg = 'success|TC Record added!';
    } catch(Exception $e) { $msg = 'error|TC Number already exists!'; }
}
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='edit') {
    $stmt = $db->prepare("UPDATE tc_records SET student_name=?,father_name=?,class=?,stream=?,admission_year=?,leaving_year=?,issue_date=? WHERE id=?");
    $stmt->execute([clean($_POST['student_name']),clean($_POST['father_name']),clean($_POST['class']),clean($_POST['stream']),clean($_POST['admission_year']),clean($_POST['leaving_year']),$_POST['issue_date'],$_POST['id']]);
    $msg = 'success|TC Record updated!';
}
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM tc_records WHERE id=?")->execute([$_GET['delete']]);
    $msg = 'success|Record deleted!';
}

$editItem = null;
if (isset($_GET['edit'])) {
    $s = $db->prepare("SELECT * FROM tc_records WHERE id=?"); $s->execute([$_GET['edit']]); $editItem = $s->fetch();
}

$search = clean($_GET['search']??'');
if ($search) {
    $stmt = $db->prepare("SELECT * FROM tc_records WHERE is_active=1 AND (tc_number LIKE ? OR student_name LIKE ? OR father_name LIKE ?) ORDER BY created_at DESC");
    $stmt->execute(["%$search%","%$search%","%$search%"]);
    $items = $stmt->fetchAll();
} else {
    $items = $db->query("SELECT * FROM tc_records WHERE is_active=1 ORDER BY created_at DESC")->fetchAll();
}
[$msgType, $msgText] = $msg ? explode('|',$msg,2) : ['',''];
?>

<div class="admin-main">
  <div class="admin-topbar"><h1>📋 TC Records</h1><a href="?action=add" class="btn-primary">+ Add TC Record</a></div>
  <?php if($msgText): ?><div class="form-<?= $msgType ?> alert-auto" style="margin-bottom:20px;padding:12px 18px;border-radius:8px;"><?= $msgType==='success'?'✅':'❌' ?> <?= $msgText ?></div><?php endif; ?>

  <?php if(isset($_GET['action']) || $editItem): ?>
  <div class="admin-card">
    <h2><?= $editItem?'Edit':'Add' ?> TC Record</h2>
    <form method="POST">
      <input type="hidden" name="action" value="<?= $editItem?'edit':'add' ?>">
      <?php if($editItem): ?><input type="hidden" name="id" value="<?= $editItem['id'] ?>"><?php endif; ?>
      <div class="form-row">
        <div class="form-group"><label>TC Number *</label><input type="text" name="tc_number" value="<?= clean($editItem['tc_number']??'') ?>" placeholder="TC-2024-001" <?= $editItem?'readonly':'' ?> required></div>
        <div class="form-group"><label>Issue Date</label><input type="date" name="issue_date" value="<?= $editItem['issue_date']??date('Y-m-d') ?>"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Student Name *</label><input type="text" name="student_name" value="<?= clean($editItem['student_name']??'') ?>" required></div>
        <div class="form-group"><label>Father's Name</label><input type="text" name="father_name" value="<?= clean($editItem['father_name']??'') ?>"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Class</label>
          <select name="class"><option>Class 9</option><option>Class 10</option><option>Class 11</option><option>Class 12</option></select>
        </div>
        <div class="form-group"><label>Stream</label>
          <select name="stream"><option>Science</option><option>Arts</option><option>Commerce</option><option>All</option></select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Admission Year</label><input type="text" name="admission_year" value="<?= clean($editItem['admission_year']??'') ?>" placeholder="2022"></div>
        <div class="form-group"><label>Leaving Year</label><input type="text" name="leaving_year" value="<?= clean($editItem['leaving_year']??'') ?>" placeholder="2024"></div>
      </div>
      <button type="submit" class="btn-primary">Save Record</button>
      <a href="<?= SITE_URL ?>/admin/tc.php" class="btn-secondary" style="margin-left:10px;">Cancel</a>
    </form>
  </div>
  <?php endif; ?>

  <div class="admin-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;flex-wrap:wrap;gap:10px;">
      <h2 style="margin:0;">TC Records (<?= count($items) ?>)</h2>
      <form method="GET" style="display:flex;gap:8px;">
        <input type="text" name="search" value="<?= $search ?>" placeholder="Search TC/Name..." style="padding:8px 12px;border:1.5px solid #e5e7eb;border-radius:6px;font-family:inherit;font-size:13px;">
        <button type="submit" class="btn-sm btn-edit">Search</button>
        <?php if($search): ?><a href="<?= SITE_URL ?>/admin/tc.php" class="btn-sm btn-view">Clear</a><?php endif; ?>
      </form>
    </div>
    <table class="admin-table">
      <thead><tr><th>TC Number</th><th>Student Name</th><th>Father</th><th>Class</th><th>Year</th><th>Issue Date</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($items as $t): ?>
        <tr>
          <td><strong><?= clean($t['tc_number']) ?></strong></td>
          <td><?= clean($t['student_name']) ?></td>
          <td><?= clean($t['father_name']) ?></td>
          <td><?= clean($t['class']) ?> — <?= clean($t['stream']) ?></td>
          <td><?= clean($t['admission_year']) ?>–<?= clean($t['leaving_year']) ?></td>
          <td><?= $t['issue_date'] ? formatDate($t['issue_date']) : '—' ?></td>
          <td><div class="action-btns">
            <a href="?edit=<?= $t['id'] ?>" class="btn-sm btn-edit">Edit</a>
            <a href="?delete=<?= $t['id'] ?>" class="btn-sm btn-delete" data-confirm="Delete this TC record?">Del</a>
          </div></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($items)): ?><tr><td colspan="7" style="text-align:center;color:#9ca3af;padding:30px;">No TC records found.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body></html>
