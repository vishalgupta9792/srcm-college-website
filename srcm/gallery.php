<?php
require_once __DIR__ . '/includes/config.php';
$db = getDB();
$categories = $db->query("SELECT gc.*, COUNT(g.id) cnt FROM gallery_categories gc LEFT JOIN gallery g ON g.category_id=gc.id AND g.is_active=1 WHERE gc.is_active=1 GROUP BY gc.id HAVING cnt>0")->fetchAll();
$activeCat = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
if ($activeCat) {
    $stmt = $db->prepare("SELECT * FROM gallery WHERE is_active=1 AND category_id=? ORDER BY sort_order");
    $stmt->execute([$activeCat]);
} else {
    $stmt = $db->query("SELECT * FROM gallery WHERE is_active=1 ORDER BY sort_order, created_at DESC");
}
$items = $stmt->fetchAll();
$pageTitle = 'Photo Gallery';
include __DIR__ . '/includes/header.php';
?>
<div class="page-banner"><div class="container"><h1>Photo Gallery</h1><div class="breadcrumb">Home → Events → <span>Photo Gallery</span></div></div></div>
<div class="page-content"><div class="container">
  <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:30px;">
    <a href="gallery.php" class="btn-sm <?= !$activeCat?'btn-edit':'btn-view' ?>" style="padding:8px 16px;border-radius:20px;font-size:13px;">All Photos</a>
    <?php foreach($categories as $c): ?>
    <a href="?cat=<?= $c['id'] ?>" class="btn-sm <?= $activeCat==$c['id']?'btn-edit':'btn-view' ?>" style="padding:8px 16px;border-radius:20px;font-size:13px;"><?= clean($c['name']) ?> (<?= $c['cnt'] ?>)</a>
    <?php endforeach; ?>
  </div>

  <?php if(!empty($items)): ?>
  <div class="gallery-grid" style="grid-template-columns:repeat(4,1fr);">
    <?php foreach($items as $item): ?>
    <div class="gallery-item" <?= $item['image_path']?'data-src="'.UPLOAD_URL.$item['image_path'].'" data-title="'.clean($item['title']).'"':'' ?> style="height:200px;border-radius:8px;overflow:hidden;">
      <?php if($item['image_path']): ?>
        <img src="<?= UPLOAD_URL . $item['image_path'] ?>" alt="<?= clean($item['title']) ?>">
      <?php else: ?><div style="font-size:50px;">📸</div><?php endif; ?>
      <div class="gallery-caption"><?= clean($item['title']) ?></div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div style="text-align:center;padding:60px;color:var(--muted);">
    <div style="font-size:60px;margin-bottom:15px;">📸</div>
    <p>No photos available in this category yet.</p>
  </div>
  <?php endif; ?>
</div></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
