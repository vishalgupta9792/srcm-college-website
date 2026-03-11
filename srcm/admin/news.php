<?php
$adminTitle = 'News & Updates';
require_once __DIR__ . '/includes/admin_header.php';
$db = getDB();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $stmt = $db->prepare("INSERT INTO news (title, detail, link, news_date, category, is_active) VALUES (?,?,?,?,?,?)");
    $stmt->execute([
        clean($_POST['title']),
        clean($_POST['detail']),
        clean($_POST['link']),
        $_POST['news_date'],
        clean($_POST['category']),
        isset($_POST['is_active']) ? 1 : 0,
    ]);
    $msg = 'success|News item added successfully!';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
    $stmt = $db->prepare("UPDATE news SET title=?, detail=?, link=?, news_date=?, category=?, is_active=? WHERE id=?");
    $stmt->execute([
        clean($_POST['title']),
        clean($_POST['detail']),
        clean($_POST['link']),
        $_POST['news_date'],
        clean($_POST['category']),
        isset($_POST['is_active']) ? 1 : 0,
        $_POST['id'],
    ]);
    $msg = 'success|News item updated successfully!';
}

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM news WHERE id=?")->execute([$_GET['delete']]);
    $msg = 'success|News item deleted successfully!';
}

if (isset($_GET['toggle'])) {
    $db->prepare("UPDATE news SET is_active = NOT is_active WHERE id=?")->execute([$_GET['toggle']]);
    redirect(SITE_URL . '/admin/news.php');
}

$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM news WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editItem = $stmt->fetch();
}

$filter = $_GET['filter'] ?? 'all';
$where = $filter === 'all' ? '' : "WHERE category=" . $db->quote($filter);
$items = $db->query("SELECT * FROM news $where ORDER BY news_date DESC, id DESC")->fetchAll();
[$msgType, $msgText] = $msg ? explode('|', $msg, 2) : ['', ''];
?>

<div class="admin-main">
  <div class="admin-topbar">
    <h1>News & Updates</h1>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <a href="?filter=all" class="btn-sm <?= $filter === 'all' ? 'btn-edit' : 'btn-view' ?>">All</a>
      <a href="?filter=college" class="btn-sm <?= $filter === 'college' ? 'btn-edit' : 'btn-view' ?>">College</a>
      <a href="?filter=upboard" class="btn-sm <?= $filter === 'upboard' ? 'btn-edit' : 'btn-view' ?>">UP Board</a>
      <a href="<?= SITE_URL ?>/admin/news.php?action=add" class="btn-primary">+ Add News</a>
    </div>
  </div>

  <?php if ($msgText): ?><div class="form-<?= $msgType ?> alert-auto" style="margin-bottom:20px;padding:12px 18px;border-radius:8px;"><?= $msgText ?></div><?php endif; ?>

  <?php if (isset($_GET['action']) || $editItem): ?>
  <div class="admin-card">
    <h2><?= $editItem ? 'Edit' : 'Add New' ?> News Item</h2>
    <form method="POST">
      <input type="hidden" name="action" value="<?= $editItem ? 'edit' : 'add' ?>">
      <?php if ($editItem): ?><input type="hidden" name="id" value="<?= $editItem['id'] ?>"><?php endif; ?>
      <div class="form-group"><label>Title *</label><input type="text" name="title" value="<?= clean($editItem['title'] ?? '') ?>" required></div>
      <div class="form-group"><label>Details</label><textarea name="detail" rows="3"><?= clean($editItem['detail'] ?? '') ?></textarea></div>
      <div class="form-row">
        <div class="form-group"><label>Date</label><input type="date" name="news_date" value="<?= $editItem['news_date'] ?? date('Y-m-d') ?>"></div>
        <div class="form-group"><label>Category</label>
          <select name="category">
            <option value="college" <?= ($editItem['category'] ?? 'college') === 'college' ? 'selected' : '' ?>>College</option>
            <option value="upboard" <?= ($editItem['category'] ?? '') === 'upboard' ? 'selected' : '' ?>>UP Board</option>
          </select>
        </div>
      </div>
      <div class="form-group"><label>External Link</label><input type="text" name="link" value="<?= clean($editItem['link'] ?? '') ?>" placeholder="https://example.com"></div>
      <div class="form-group" style="display:flex;align-items:center;gap:10px;">
        <input type="checkbox" name="is_active" id="news_active" <?= ($editItem['is_active'] ?? 1) ? 'checked' : '' ?> style="width:18px;height:18px;">
        <label for="news_active" style="margin:0;font-weight:600;">Active item</label>
      </div>
      <button type="submit" class="btn-primary">Save News</button>
      <a href="<?= SITE_URL ?>/admin/news.php" class="btn-secondary" style="margin-left:10px;">Cancel</a>
    </form>
  </div>
  <?php endif; ?>

  <div class="admin-card">
    <h2>All News Items (<?= count($items) ?>)</h2>
    <table class="admin-table">
      <thead><tr><th>Date</th><th>Title</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
          <td><?= $item['news_date'] ? formatDate($item['news_date']) : 'N/A' ?></td>
          <td><?= clean($item['title']) ?></td>
          <td><span class="badge badge-<?= $item['category'] === 'college' ? 'info' : 'warning' ?>"><?= strtoupper(clean($item['category'])) ?></span></td>
          <td><span class="badge badge-<?= $item['is_active'] ? 'success' : 'warning' ?>"><?= $item['is_active'] ? 'Active' : 'Hidden' ?></span></td>
          <td><div class="action-btns">
            <a href="?edit=<?= $item['id'] ?>" class="btn-sm btn-edit">Edit</a>
            <a href="?toggle=<?= $item['id'] ?>" class="btn-sm btn-view"><?= $item['is_active'] ? 'Hide' : 'Show' ?></a>
            <a href="?delete=<?= $item['id'] ?>" class="btn-sm btn-delete" data-confirm="Delete this news item?">Delete</a>
          </div></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($items)): ?><tr><td colspan="5" style="text-align:center;color:#9ca3af;padding:30px;">No news items found.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body></html>
