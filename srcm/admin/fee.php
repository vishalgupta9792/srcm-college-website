<?php
$adminTitle = 'Fee Structure';
require_once __DIR__ . '/includes/admin_header.php';
$db = getDB();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $stmt = $db->prepare("INSERT INTO fee_structure (class, stream, admission_fee, registration_fee, monthly_fee, session_year, is_active) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([
        clean($_POST['class']),
        clean($_POST['stream']),
        (int) $_POST['admission_fee'],
        (int) $_POST['registration_fee'],
        (int) $_POST['monthly_fee'],
        clean($_POST['session_year']),
        isset($_POST['is_active']) ? 1 : 0,
    ]);
    $msg = 'success|Fee row added successfully!';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
    $stmt = $db->prepare("UPDATE fee_structure SET class=?, stream=?, admission_fee=?, registration_fee=?, monthly_fee=?, session_year=?, is_active=? WHERE id=?");
    $stmt->execute([
        clean($_POST['class']),
        clean($_POST['stream']),
        (int) $_POST['admission_fee'],
        (int) $_POST['registration_fee'],
        (int) $_POST['monthly_fee'],
        clean($_POST['session_year']),
        isset($_POST['is_active']) ? 1 : 0,
        $_POST['id'],
    ]);
    $msg = 'success|Fee row updated successfully!';
}

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM fee_structure WHERE id=?")->execute([$_GET['delete']]);
    $msg = 'success|Fee row deleted successfully!';
}

if (isset($_GET['toggle'])) {
    $db->prepare("UPDATE fee_structure SET is_active = NOT is_active WHERE id=?")->execute([$_GET['toggle']]);
    redirect(SITE_URL . '/admin/fee.php');
}

$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM fee_structure WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editItem = $stmt->fetch();
}

$items = $db->query("SELECT * FROM fee_structure ORDER BY class ASC, stream ASC, id ASC")->fetchAll();
[$msgType, $msgText] = $msg ? explode('|', $msg, 2) : ['', ''];
?>

<div class="admin-main">
  <div class="admin-topbar"><h1>Fee Structure</h1><a href="<?= SITE_URL ?>/admin/fee.php?action=add" class="btn-primary">+ Add Fee Row</a></div>

  <?php if ($msgText): ?><div class="form-<?= $msgType ?> alert-auto" style="margin-bottom:20px;padding:12px 18px;border-radius:8px;"><?= $msgText ?></div><?php endif; ?>

  <?php if (isset($_GET['action']) || $editItem): ?>
  <div class="admin-card">
    <h2><?= $editItem ? 'Edit' : 'Add New' ?> Fee Row</h2>
    <form method="POST">
      <input type="hidden" name="action" value="<?= $editItem ? 'edit' : 'add' ?>">
      <?php if ($editItem): ?><input type="hidden" name="id" value="<?= $editItem['id'] ?>"><?php endif; ?>
      <div class="form-row">
        <div class="form-group"><label>Class</label><input type="text" name="class" value="<?= clean($editItem['class'] ?? '') ?>" required placeholder="Class 11"></div>
        <div class="form-group"><label>Stream</label><input type="text" name="stream" value="<?= clean($editItem['stream'] ?? 'All') ?>" required></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Admission Fee</label><input type="number" name="admission_fee" value="<?= clean($editItem['admission_fee'] ?? 0) ?>" required></div>
        <div class="form-group"><label>Registration Fee</label><input type="number" name="registration_fee" value="<?= clean($editItem['registration_fee'] ?? 0) ?>" required></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Monthly Fee</label><input type="number" name="monthly_fee" value="<?= clean($editItem['monthly_fee'] ?? 0) ?>" required></div>
        <div class="form-group"><label>Session Year</label><input type="text" name="session_year" value="<?= clean($editItem['session_year'] ?? '2025-26') ?>" required></div>
      </div>
      <div class="form-group" style="display:flex;align-items:center;gap:10px;">
        <input type="checkbox" name="is_active" id="fee_active" <?= ($editItem['is_active'] ?? 1) ? 'checked' : '' ?> style="width:18px;height:18px;">
        <label for="fee_active" style="margin:0;font-weight:600;">Active row</label>
      </div>
      <button type="submit" class="btn-primary">Save Fee Row</button>
      <a href="<?= SITE_URL ?>/admin/fee.php" class="btn-secondary" style="margin-left:10px;">Cancel</a>
    </form>
  </div>
  <?php endif; ?>

  <div class="admin-card">
    <h2>Fee Structure Rows (<?= count($items) ?>)</h2>
    <table class="admin-table">
      <thead><tr><th>Class</th><th>Stream</th><th>Admission</th><th>Registration</th><th>Monthly</th><th>Session</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
          <td><?= clean($item['class']) ?></td>
          <td><?= clean($item['stream']) ?></td>
          <td>Rs. <?= (int) $item['admission_fee'] ?></td>
          <td>Rs. <?= (int) $item['registration_fee'] ?></td>
          <td>Rs. <?= (int) $item['monthly_fee'] ?></td>
          <td><?= clean($item['session_year']) ?></td>
          <td><span class="badge badge-<?= $item['is_active'] ? 'success' : 'warning' ?>"><?= $item['is_active'] ? 'Active' : 'Hidden' ?></span></td>
          <td><div class="action-btns">
            <a href="?edit=<?= $item['id'] ?>" class="btn-sm btn-edit">Edit</a>
            <a href="?toggle=<?= $item['id'] ?>" class="btn-sm btn-view"><?= $item['is_active'] ? 'Hide' : 'Show' ?></a>
            <a href="?delete=<?= $item['id'] ?>" class="btn-sm btn-delete" data-confirm="Delete this fee row?">Delete</a>
          </div></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body></html>
