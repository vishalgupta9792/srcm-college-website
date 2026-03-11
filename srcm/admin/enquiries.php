<?php
$adminTitle = 'Enquiries';
require_once __DIR__ . '/includes/admin_header.php';
$db = getDB();

// Update status
if (isset($_GET['status']) && isset($_GET['id'])) {
    $db->prepare("UPDATE enquiries SET status=? WHERE id=?")->execute([clean($_GET['status']), $_GET['id']]);
    redirect(SITE_URL.'/admin/enquiries.php');
}
// Delete
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM enquiries WHERE id=?")->execute([$_GET['delete']]);
    redirect(SITE_URL.'/admin/enquiries.php');
}

// Add new (from admin)
$msg = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $stmt = $db->prepare("INSERT INTO enquiries (student_name,father_name,phone,email,class_applying,stream,message) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([clean($_POST['student_name']),clean($_POST['father_name']),clean($_POST['phone']),clean($_POST['email']),clean($_POST['class_applying']),clean($_POST['stream']),clean($_POST['message'])]);
    $msg = 'Enquiry added!';
}

$filter = $_GET['filter'] ?? 'all';
$where = $filter !== 'all' ? "WHERE status='$filter'" : '';
$items = $db->query("SELECT * FROM enquiries $where ORDER BY created_at DESC")->fetchAll();
?>

<div class="admin-main">
  <div class="admin-topbar">
    <h1>📝 Admission Enquiries</h1>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <a href="?filter=all" class="btn-sm <?= $filter=='all'?'btn-edit':'btn-view' ?>">All</a>
      <a href="?filter=new" class="btn-sm <?= $filter=='new'?'btn-edit':'btn-view' ?>">New</a>
      <a href="?filter=contacted" class="btn-sm <?= $filter=='contacted'?'btn-edit':'btn-view' ?>">Contacted</a>
      <a href="?filter=admitted" class="btn-sm <?= $filter=='admitted'?'btn-edit':'btn-view' ?>">Admitted</a>
    </div>
  </div>

  <?php if($msg): ?><div class="form-success alert-auto" style="margin-bottom:20px;padding:12px 18px;border-radius:8px;">✅ <?= $msg ?></div><?php endif; ?>

  <div class="admin-card">
    <h2>Enquiries (<?= count($items) ?>)</h2>
    <table class="admin-table">
      <thead><tr><th>Date</th><th>Student</th><th>Father</th><th>Phone</th><th>Class</th><th>Stream</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($items as $e): ?>
        <tr>
          <td><?= date('d M Y', strtotime($e['created_at'])) ?></td>
          <td><?= clean($e['student_name']) ?></td>
          <td><?= clean($e['father_name']) ?></td>
          <td><?= clean($e['phone']) ?></td>
          <td><?= clean($e['class_applying']) ?></td>
          <td><?= clean($e['stream']) ?></td>
          <td><span class="badge badge-<?= $e['status']=='new'?'warning':($e['status']=='admitted'?'success':($e['status']=='rejected'?'danger':'info')) ?>"><?= ucfirst($e['status']) ?></span></td>
          <td><div class="action-btns">
            <a href="?id=<?= $e['id'] ?>&status=contacted" class="btn-sm btn-view">Contacted</a>
            <a href="?id=<?= $e['id'] ?>&status=admitted" class="btn-sm btn-edit">Admitted</a>
            <a href="?delete=<?= $e['id'] ?>" class="btn-sm btn-delete" data-confirm="Delete?">Del</a>
          </div></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($items)): ?><tr><td colspan="8" style="text-align:center;color:#9ca3af;padding:30px;">No enquiries found.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body></html>
