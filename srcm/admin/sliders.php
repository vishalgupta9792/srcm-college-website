<?php
$adminTitle = 'Sliders';
require_once __DIR__ . '/includes/admin_header.php';
$db = getDB();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $imagePath = uploadImage($_FILES['image'], 'sliders');
        if (!$imagePath) {
            $msg = 'error|Image upload failed. Use jpg, png, gif, or webp under 5MB.';
        }
    }

    if (!$msg) {
        $stmt = $db->prepare("INSERT INTO sliders (title, subtitle, btn_text, btn_link, bg_gradient, image_path, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([
            clean($_POST['title']),
            clean($_POST['subtitle']),
            clean($_POST['btn_text']),
            clean($_POST['btn_link']),
            clean($_POST['bg_gradient']),
            $imagePath,
            (int) ($_POST['sort_order'] ?? 0),
            isset($_POST['is_active']) ? 1 : 0,
        ]);
        $msg = 'success|Slider added successfully!';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
    $current = $db->prepare("SELECT image_path FROM sliders WHERE id=?");
    $current->execute([$_POST['id']]);
    $current = $current->fetch();
    $imagePath = $current['image_path'] ?? null;

    if (!empty($_FILES['image']['name'])) {
        $newImage = uploadImage($_FILES['image'], 'sliders');
        if ($newImage) {
            if ($imagePath) {
                @unlink(UPLOAD_PATH . $imagePath);
            }
            $imagePath = $newImage;
        } else {
            $msg = 'error|Image upload failed. Use jpg, png, gif, or webp under 5MB.';
        }
    }

    if (isset($_POST['remove_image'])) {
        if ($imagePath) {
            @unlink(UPLOAD_PATH . $imagePath);
        }
        $imagePath = null;
    }

    if (!$msg) {
        $stmt = $db->prepare("UPDATE sliders SET title=?, subtitle=?, btn_text=?, btn_link=?, bg_gradient=?, image_path=?, sort_order=?, is_active=? WHERE id=?");
        $stmt->execute([
            clean($_POST['title']),
            clean($_POST['subtitle']),
            clean($_POST['btn_text']),
            clean($_POST['btn_link']),
            clean($_POST['bg_gradient']),
            $imagePath,
            (int) ($_POST['sort_order'] ?? 0),
            isset($_POST['is_active']) ? 1 : 0,
            $_POST['id'],
        ]);
        $msg = 'success|Slider updated successfully!';
    }
}

if (isset($_GET['delete'])) {
    $item = $db->prepare("SELECT image_path FROM sliders WHERE id=?");
    $item->execute([$_GET['delete']]);
    $item = $item->fetch();
    if ($item && $item['image_path']) {
        @unlink(UPLOAD_PATH . $item['image_path']);
    }
    $db->prepare("DELETE FROM sliders WHERE id=?")->execute([$_GET['delete']]);
    $msg = 'success|Slider deleted successfully!';
}

if (isset($_GET['toggle'])) {
    $db->prepare("UPDATE sliders SET is_active = NOT is_active WHERE id=?")->execute([$_GET['toggle']]);
    redirect(SITE_URL . '/admin/sliders.php');
}

$editItem = null;
if (isset($_GET['edit'])) {
    $editStmt = $db->prepare("SELECT * FROM sliders WHERE id=?");
    $editStmt->execute([$_GET['edit']]);
    $editItem = $editStmt->fetch();
}

$items = $db->query("SELECT * FROM sliders ORDER BY sort_order ASC, id ASC")->fetchAll();
[$msgType, $msgText] = $msg ? explode('|', $msg, 2) : ['', ''];
?>

<div class="admin-main">
  <div class="admin-topbar"><h1>Sliders</h1><a href="<?= SITE_URL ?>/admin/sliders.php?action=add" class="btn-primary">+ Add Slider</a></div>

  <?php if ($msgText): ?><div class="form-<?= $msgType ?> alert-auto" style="margin-bottom:20px;padding:12px 18px;border-radius:8px;"><?= $msgText ?></div><?php endif; ?>

  <?php if (isset($_GET['action']) || $editItem): ?>
  <div class="admin-card">
    <h2><?= $editItem ? 'Edit' : 'Add New' ?> Slider</h2>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="action" value="<?= $editItem ? 'edit' : 'add' ?>">
      <?php if ($editItem): ?><input type="hidden" name="id" value="<?= $editItem['id'] ?>"><?php endif; ?>

      <div class="form-group"><label>Title *</label><input type="text" name="title" value="<?= clean($editItem['title'] ?? '') ?>" required placeholder="Slider heading"></div>
      <div class="form-group"><label>Subtitle</label><textarea name="subtitle" rows="3" placeholder="Slider short description"><?= clean($editItem['subtitle'] ?? '') ?></textarea></div>

      <div class="form-row">
        <div class="form-group"><label>Button Text</label><input type="text" name="btn_text" value="<?= clean($editItem['btn_text'] ?? '') ?>" placeholder="Know More"></div>
        <div class="form-group"><label>Button Link</label><input type="text" name="btn_link" value="<?= clean($editItem['btn_link'] ?? '') ?>" placeholder="about.php"></div>
      </div>

      <div class="form-row">
        <div class="form-group"><label>Background Gradient</label><input type="text" name="bg_gradient" value="<?= clean($editItem['bg_gradient'] ?? 'linear-gradient(135deg,#1a3a5c,#2d6a8a)') ?>" placeholder="linear-gradient(135deg,#1a3a5c,#2d6a8a)"></div>
        <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="<?= (int) ($editItem['sort_order'] ?? 0) ?>"></div>
      </div>

      <div class="form-group">
        <label>Slider Image</label>
        <input type="file" name="image" accept="image/*" style="padding:8px;">
        <small style="display:block;margin-top:6px;color:#6b7280;">Optional. If no image is uploaded, the gradient background will be used.</small>
      </div>

      <?php if (!empty($editItem['image_path'])): ?>
      <div class="form-group">
        <div style="margin-bottom:10px;">
          <img src="<?= UPLOAD_URL . $editItem['image_path'] ?>" alt="Current slider image" style="max-width:240px;border-radius:10px;border:1px solid #e5e7eb;">
        </div>
        <label style="display:flex;align-items:center;gap:8px;">
          <input type="checkbox" name="remove_image" value="1" style="width:16px;height:16px;">
          Remove current image
        </label>
      </div>
      <?php endif; ?>

      <div class="form-group" style="display:flex;align-items:center;gap:10px;">
        <input type="checkbox" name="is_active" id="is_active" <?= ($editItem['is_active'] ?? 1) ? 'checked' : '' ?> style="width:18px;height:18px;">
        <label for="is_active" style="margin:0;font-weight:600;">Slider active</label>
      </div>

      <button type="submit" class="btn-primary">Save Slider</button>
      <a href="<?= SITE_URL ?>/admin/sliders.php" class="btn-secondary" style="margin-left:10px;">Cancel</a>
    </form>
  </div>
  <?php endif; ?>

  <div class="admin-card">
    <h2>All Sliders (<?= count($items) ?>)</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:18px;">
      <?php foreach ($items as $item): ?>
      <div style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;background:#fff;box-shadow:0 4px 14px rgba(0,0,0,0.06);">
        <div style="height:170px;<?= $item['image_path'] ? '' : 'background:' . clean($item['bg_gradient']) . ';display:flex;align-items:center;justify-content:center;color:#fff;padding:20px;text-align:center;' ?>">
          <?php if ($item['image_path']): ?>
          <img src="<?= UPLOAD_URL . $item['image_path'] ?>" alt="<?= clean($item['title']) ?>" style="width:100%;height:100%;object-fit:cover;">
          <?php else: ?>
          <div>
            <div style="font-size:28px;font-weight:700;font-family:'Playfair Display',serif;"><?= clean($item['title']) ?></div>
            <div style="font-size:13px;opacity:0.9;margin-top:6px;"><?= clean($item['subtitle']) ?></div>
          </div>
          <?php endif; ?>
        </div>
        <div style="padding:14px;">
          <div style="font-size:16px;font-weight:700;color:var(--primary);margin-bottom:6px;"><?= clean($item['title']) ?></div>
          <div style="font-size:13px;color:#6b7280;line-height:1.6;margin-bottom:10px;min-height:42px;"><?= clean($item['subtitle']) ?></div>
          <div style="font-size:12px;color:#6b7280;margin-bottom:12px;">
            Button: <strong><?= clean($item['btn_text'] ?: 'None') ?></strong>
            <?php if ($item['btn_link']): ?> | Link: <?= clean($item['btn_link']) ?><?php endif; ?>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <span class="badge badge-<?= $item['is_active'] ? 'success' : 'warning' ?>"><?= $item['is_active'] ? 'Active' : 'Hidden' ?></span>
            <span style="font-size:12px;color:#6b7280;">Sort: <?= (int) $item['sort_order'] ?></span>
          </div>
          <div class="action-btns">
            <a href="?edit=<?= $item['id'] ?>" class="btn-sm btn-edit">Edit</a>
            <a href="?toggle=<?= $item['id'] ?>" class="btn-sm btn-view"><?= $item['is_active'] ? 'Hide' : 'Show' ?></a>
            <a href="?delete=<?= $item['id'] ?>" class="btn-sm btn-delete" data-confirm="Delete this slider?">Delete</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($items)): ?><div style="grid-column:1/-1;text-align:center;color:#9ca3af;padding:40px;">No sliders found. Click "Add Slider" to create one.</div><?php endif; ?>
    </div>
  </div>
</div>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body></html>
