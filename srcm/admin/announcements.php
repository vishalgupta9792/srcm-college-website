<?php
$adminTitle = 'Announcements';
require_once __DIR__ . '/includes/admin_header.php';
$db = getDB();
$msg = '';

// ADD
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='add') {
    $stmt = $db->prepare("INSERT INTO announcements (title,detail,ann_date,is_new,is_active) VALUES (?,?,?,?,1)");
    $stmt->execute([clean($_POST['title']), clean($_POST['detail']), $_POST['ann_date'], isset($_POST['is_new'])?1:0]);
    $msg = 'success|Announcement added successfully!';
}
// EDIT
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='edit') {
    $stmt = $db->prepare("UPDATE announcements SET title=?,detail=?,ann_date=?,is_new=? WHERE id=?");
    $stmt->execute([clean($_POST['title']), clean($_POST['detail']), $_POST['ann_date'], isset($_POST['is_new'])?1:0, $_POST['id']]);
    $msg = 'success|Announcement updated!';
}
// DELETE
if (isset($_GET['delete'])) {
    $db->prepare("UPDATE announcements SET is_active=0 WHERE id=?")->execute([$_GET['delete']]);
    $msg = 'success|Deleted successfully!';
}
// TOGGLE
if (isset($_GET['toggle'])) {
    $db->prepare("UPDATE announcements SET is_new = NOT is_new WHERE id=?")->execute([$_GET['toggle']]);
    redirect(SITE_URL.'/admin/announcements.php');
}

$editItem = null;
if (isset($_GET['edit'])) {
    $editItem = $db->prepare("SELECT * FROM announcements WHERE id=?");
    $editItem->execute([$_GET['edit']]);
    $editItem = $editItem->fetch();
}

$items = $db->query("SELECT * FROM announcements WHERE is_active=1 ORDER BY created_at DESC")->fetchAll();
[$msgType, $msgText] = $msg ? explode('|',$msg,2) : ['',''];
?>

<div class="admin-main">
  <div class="admin-topbar"><h1>📢 Announcements</h1><a href="<?= SITE_URL ?>/admin/announcements.php?action=add" class="btn-primary">+ Add New</a></div>

  <?php if($msgText): ?><div class="form-<?= $msgType ?> alert-auto" style="margin-bottom:20px;padding:12px 18px;border-radius:8px;"><?= $msgText ?></div><?php endif; ?>

  <!-- FORM -->
  <?php if(isset($_GET['action']) || $editItem): ?>
  <div class="admin-card">
    <h2><?= $editItem ? 'Edit' : 'Add New' ?> Announcement</h2>
    <form method="POST">
      <input type="hidden" name="action" value="<?= $editItem ? 'edit' : 'add' ?>">
      <?php if($editItem): ?><input type="hidden" name="id" value="<?= $editItem['id'] ?>"><?php endif; ?>
      <div class="form-group"><label>Title / Announcement Text *</label><input type="text" name="title" value="<?= clean($editItem['title']??'') ?>" required placeholder="e.g. Annual Examination starts from March 2025"></div>
      <div class="form-group"><label>Detail (Optional)</label><textarea name="detail" rows="3" placeholder="Additional details..."><?= clean($editItem['detail']??'') ?></textarea></div>
      <div class="form-row">
        <div class="form-group"><label>Date</label><input type="date" name="ann_date" value="<?= $editItem['ann_date']??date('Y-m-d') ?>"></div>
        <div class="form-group" style="display:flex;align-items:center;gap:10px;padding-top:28px;">
          <input type="checkbox" name="is_new" id="is_new" <?= ($editItem['is_new']??1)?'checked':'' ?> style="width:18px;height:18px;">
          <label for="is_new" style="margin:0;font-weight:600;">Mark as NEW</label>
        </div>
      </div>
      <button type="submit" class="btn-primary">Save Announcement</button>
      <a href="<?= SITE_URL ?>/admin/announcements.php" class="btn-secondary" style="margin-left:10px;">Cancel</a>
    </form>
  </div>
  <?php endif; ?>

  <!-- LIST -->
  <div class="admin-card">
    <h2>All Announcements (<?= count($items) ?>)</h2>
    <table class="admin-table">
      <thead><tr><th>#</th><th>Title</th><th>Date</th><th>NEW Badge</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($items as $i => $item): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><?= clean($item['title']) ?></td>
          <td><?= $item['ann_date'] ? formatDate($item['ann_date']) : '—' ?></td>
          <td><span class="badge badge-<?= $item['is_new']?'warning':'success' ?>"><?= $item['is_new']?'NEW':'Normal' ?></span></td>
          <td><div class="action-btns">
            <a href="?edit=<?= $item['id'] ?>" class="btn-sm btn-edit">Edit</a>
            <a href="?toggle=<?= $item['id'] ?>" class="btn-sm btn-view"><?= $item['is_new']?'Remove NEW':'Mark NEW' ?></a>
            <a href="?delete=<?= $item['id'] ?>" class="btn-sm btn-delete" data-confirm="Delete this announcement?">Delete</a>
          </div></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body></html>
