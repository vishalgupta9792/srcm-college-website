<?php
$adminTitle = 'Staff';
require_once __DIR__ . '/includes/admin_header.php';
$db = getDB();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $photoPath = null;
    if (!empty($_FILES['photo']['name'])) {
        $photoPath = uploadImage($_FILES['photo'], 'staff');
        if (!$photoPath) {
            $msg = 'error|Photo upload failed. Use jpg, png, gif, or webp under 5MB.';
        }
    }

    if (!$msg) {
        $stmt = $db->prepare("INSERT INTO staff (name, designation, subject, qualification, experience, photo_path, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([
            clean($_POST['name']),
            clean($_POST['designation']),
            clean($_POST['subject']),
            clean($_POST['qualification']),
            clean($_POST['experience']),
            $photoPath,
            (int) ($_POST['sort_order'] ?? 0),
            isset($_POST['is_active']) ? 1 : 0,
        ]);
        $msg = 'success|Staff member added successfully!';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
    $current = $db->prepare("SELECT photo_path FROM staff WHERE id=?");
    $current->execute([$_POST['id']]);
    $current = $current->fetch();
    $photoPath = $current['photo_path'] ?? null;

    if (!empty($_FILES['photo']['name'])) {
        $newPhoto = uploadImage($_FILES['photo'], 'staff');
        if ($newPhoto) {
            if ($photoPath) {
                @unlink(UPLOAD_PATH . $photoPath);
            }
            $photoPath = $newPhoto;
        } else {
            $msg = 'error|Photo upload failed. Use jpg, png, gif, or webp under 5MB.';
        }
    }

    if (isset($_POST['remove_photo'])) {
        if ($photoPath) {
            @unlink(UPLOAD_PATH . $photoPath);
        }
        $photoPath = null;
    }

    if (!$msg) {
        $stmt = $db->prepare("UPDATE staff SET name=?, designation=?, subject=?, qualification=?, experience=?, photo_path=?, sort_order=?, is_active=? WHERE id=?");
        $stmt->execute([
            clean($_POST['name']),
            clean($_POST['designation']),
            clean($_POST['subject']),
            clean($_POST['qualification']),
            clean($_POST['experience']),
            $photoPath,
            (int) ($_POST['sort_order'] ?? 0),
            isset($_POST['is_active']) ? 1 : 0,
            $_POST['id'],
        ]);
        $msg = 'success|Staff member updated successfully!';
    }
}

if (isset($_GET['delete'])) {
    $item = $db->prepare("SELECT photo_path FROM staff WHERE id=?");
    $item->execute([$_GET['delete']]);
    $item = $item->fetch();
    if ($item && $item['photo_path']) {
        @unlink(UPLOAD_PATH . $item['photo_path']);
    }
    $db->prepare("DELETE FROM staff WHERE id=?")->execute([$_GET['delete']]);
    $msg = 'success|Staff member deleted successfully!';
}

if (isset($_GET['toggle'])) {
    $db->prepare("UPDATE staff SET is_active = NOT is_active WHERE id=?")->execute([$_GET['toggle']]);
    redirect(SITE_URL . '/admin/staff.php');
}

$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM staff WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editItem = $stmt->fetch();
}

$items = $db->query("SELECT * FROM staff ORDER BY sort_order ASC, id ASC")->fetchAll();
[$msgType, $msgText] = $msg ? explode('|', $msg, 2) : ['', ''];
?>

<div class="admin-main">
  <div class="admin-topbar"><h1>Staff</h1><a href="<?= SITE_URL ?>/admin/staff.php?action=add" class="btn-primary">+ Add Staff</a></div>

  <?php if ($msgText): ?><div class="form-<?= $msgType ?> alert-auto" style="margin-bottom:20px;padding:12px 18px;border-radius:8px;"><?= $msgText ?></div><?php endif; ?>

  <?php if (isset($_GET['action']) || $editItem): ?>
  <div class="admin-card">
    <h2><?= $editItem ? 'Edit' : 'Add New' ?> Staff Member</h2>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="action" value="<?= $editItem ? 'edit' : 'add' ?>">
      <?php if ($editItem): ?><input type="hidden" name="id" value="<?= $editItem['id'] ?>"><?php endif; ?>
      <div class="form-row">
        <div class="form-group"><label>Name *</label><input type="text" name="name" value="<?= clean($editItem['name'] ?? '') ?>" required></div>
        <div class="form-group"><label>Designation</label><input type="text" name="designation" value="<?= clean($editItem['designation'] ?? '') ?>"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Subject</label><input type="text" name="subject" value="<?= clean($editItem['subject'] ?? '') ?>"></div>
        <div class="form-group"><label>Experience</label><input type="text" name="experience" value="<?= clean($editItem['experience'] ?? '') ?>" placeholder="10+ Years"></div>
      </div>
      <div class="form-group"><label>Qualification</label><input type="text" name="qualification" value="<?= clean($editItem['qualification'] ?? '') ?>"></div>
      <div class="form-row">
        <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="<?= (int) ($editItem['sort_order'] ?? 0) ?>"></div>
        <div class="form-group"><label>Photo</label><input type="file" name="photo" accept="image/*" style="padding:8px;"></div>
      </div>
      <?php if (!empty($editItem['photo_path'])): ?>
      <div class="form-group">
        <img src="<?= UPLOAD_URL . $editItem['photo_path'] ?>" alt="<?= clean($editItem['name']) ?>" style="max-width:180px;border-radius:10px;border:1px solid #e5e7eb;margin-bottom:10px;">
        <label style="display:flex;align-items:center;gap:8px;">
          <input type="checkbox" name="remove_photo" value="1" style="width:16px;height:16px;">
          Remove current photo
        </label>
      </div>
      <?php endif; ?>
      <div class="form-group" style="display:flex;align-items:center;gap:10px;">
        <input type="checkbox" name="is_active" id="staff_active" <?= ($editItem['is_active'] ?? 1) ? 'checked' : '' ?> style="width:18px;height:18px;">
        <label for="staff_active" style="margin:0;font-weight:600;">Active member</label>
      </div>
      <button type="submit" class="btn-primary">Save Staff Member</button>
      <a href="<?= SITE_URL ?>/admin/staff.php" class="btn-secondary" style="margin-left:10px;">Cancel</a>
    </form>
  </div>
  <?php endif; ?>

  <div class="admin-card">
    <h2>All Staff Members (<?= count($items) ?>)</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:18px;">
      <?php foreach ($items as $item): ?>
      <div style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;background:#fff;box-shadow:0 4px 14px rgba(0,0,0,0.06);">
        <div style="height:190px;background:var(--light);display:flex;align-items:center;justify-content:center;overflow:hidden;">
          <?php if ($item['photo_path']): ?>
          <img src="<?= UPLOAD_URL . $item['photo_path'] ?>" alt="<?= clean($item['name']) ?>" style="width:100%;height:100%;object-fit:cover;">
          <?php else: ?>
          <div style="font-size:54px;color:#94a3b8;">S</div>
          <?php endif; ?>
        </div>
        <div style="padding:14px;">
          <div style="font-size:16px;font-weight:700;color:var(--primary);"><?= clean($item['name']) ?></div>
          <div style="font-size:13px;color:#6b7280;margin:4px 0;"><?= clean($item['designation']) ?></div>
          <div style="font-size:13px;color:#6b7280;line-height:1.6;min-height:54px;"><?= clean($item['subject']) ?><?php if ($item['qualification']): ?> | <?= clean($item['qualification']) ?><?php endif; ?></div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin:10px 0 12px;">
            <span class="badge badge-<?= $item['is_active'] ? 'success' : 'warning' ?>"><?= $item['is_active'] ? 'Active' : 'Hidden' ?></span>
            <span style="font-size:12px;color:#6b7280;">Sort: <?= (int) $item['sort_order'] ?></span>
          </div>
          <div class="action-btns">
            <a href="?edit=<?= $item['id'] ?>" class="btn-sm btn-edit">Edit</a>
            <a href="?toggle=<?= $item['id'] ?>" class="btn-sm btn-view"><?= $item['is_active'] ? 'Hide' : 'Show' ?></a>
            <a href="?delete=<?= $item['id'] ?>" class="btn-sm btn-delete" data-confirm="Delete this staff member?">Delete</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($items)): ?><div style="grid-column:1/-1;text-align:center;color:#9ca3af;padding:40px;">No staff members found.</div><?php endif; ?>
    </div>
  </div>
</div>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body></html>
