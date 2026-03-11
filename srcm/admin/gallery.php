<?php
$adminTitle = 'Gallery';
require_once __DIR__ . '/includes/admin_header.php';
$db = getDB();
$msg = '';

// ADD
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='add') {
    if (!empty($_FILES['image']['name'])) {
        $path = uploadImage($_FILES['image'], 'gallery');
        if ($path) {
            $stmt = $db->prepare("INSERT INTO gallery (category_id,title,image_path,sort_order) VALUES (?,?,?,?)");
            $stmt->execute([$_POST['category_id'], clean($_POST['title']), $path, $_POST['sort_order']??0]);
            $msg = 'success|Photo uploaded successfully!';
        } else { $msg = 'error|Upload failed. Check file type/size (max 5MB, jpg/png/webp).'; }
    } else { $msg = 'error|Please select an image.'; }
}
// DELETE
if (isset($_GET['delete'])) {
    $item = $db->prepare("SELECT image_path FROM gallery WHERE id=?");
    $item->execute([$_GET['delete']]);
    $item = $item->fetch();
    if ($item && $item['image_path']) @unlink(UPLOAD_PATH . $item['image_path']);
    $db->prepare("DELETE FROM gallery WHERE id=?")->execute([$_GET['delete']]);
    $msg = 'success|Photo deleted!';
}

$categories = $db->query("SELECT * FROM gallery_categories WHERE is_active=1")->fetchAll();
$items = $db->query("SELECT g.*, gc.name cat_name FROM gallery g LEFT JOIN gallery_categories gc ON g.category_id=gc.id WHERE g.is_active=1 ORDER BY g.created_at DESC")->fetchAll();
[$msgType, $msgText] = $msg ? explode('|',$msg,2) : ['',''];
?>

<div class="admin-main">
  <div class="admin-topbar"><h1>📸 Gallery</h1><a href="?action=add" class="btn-primary">+ Upload Photo</a></div>

  <?php if($msgText): ?><div class="form-<?= $msgType ?> alert-auto" style="margin-bottom:20px;padding:12px 18px;border-radius:8px;"><?= $msgText ?></div><?php endif; ?>

  <?php if(isset($_GET['action'])): ?>
  <div class="admin-card">
    <h2>Upload New Photo</h2>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="action" value="add">
      <div class="form-row">
        <div class="form-group"><label>Category *</label>
          <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php foreach($categories as $c): ?><option value="<?= $c['id'] ?>"><?= clean($c['name']) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="form-group"><label>Photo Title</label><input type="text" name="title" placeholder="e.g. Annual Function 2024"></div>
      </div>
      <div class="form-group"><label>Select Image * (JPG/PNG/WebP, max 5MB)</label>
        <input type="file" name="image" accept="image/*" required style="padding:8px;">
      </div>
      <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="0" style="width:120px;"></div>
      <button type="submit" class="btn-primary">Upload Photo</button>
      <a href="<?= SITE_URL ?>/admin/gallery.php" class="btn-secondary" style="margin-left:10px;">Cancel</a>
    </form>
  </div>
  <?php endif; ?>

  <div class="admin-card">
    <h2>All Photos (<?= count($items) ?>)</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;">
      <?php foreach($items as $item): ?>
      <div style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;background:white;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
        <div style="height:150px;overflow:hidden;background:var(--light);display:flex;align-items:center;justify-content:center;">
          <?php if($item['image_path']): ?>
          <img src="<?= UPLOAD_URL . $item['image_path'] ?>" style="width:100%;height:100%;object-fit:cover;">
          <?php else: ?><span style="font-size:40px;">📸</span><?php endif; ?>
        </div>
        <div style="padding:12px;">
          <div style="font-size:13px;font-weight:600;color:var(--primary);margin-bottom:3px;"><?= clean($item['title']??'') ?></div>
          <div style="font-size:11px;color:var(--muted);margin-bottom:10px;"><?= clean($item['cat_name']) ?></div>
          <a href="?delete=<?= $item['id'] ?>" class="btn-sm btn-delete" data-confirm="Delete this photo?" style="font-size:12px;">🗑️ Delete</a>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if(empty($items)): ?><div style="grid-column:1/-1;text-align:center;color:#9ca3af;padding:40px;">No photos uploaded yet. Click "Upload Photo" to add.</div><?php endif; ?>
    </div>
  </div>
</div>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body></html>
