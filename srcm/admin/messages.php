<?php
// messages.php
$adminTitle = 'Contact Messages';
require_once __DIR__ . '/includes/admin_header.php';
$db = getDB();

if (isset($_GET['read'])) { $db->prepare("UPDATE contact_messages SET is_read=1 WHERE id=?")->execute([$_GET['read']]); }
if (isset($_GET['delete'])) { $db->prepare("DELETE FROM contact_messages WHERE id=?")->execute([$_GET['delete']]); redirect(SITE_URL.'/admin/messages.php'); }

$view = null;
if (isset($_GET['view'])) {
    $s = $db->prepare("SELECT * FROM contact_messages WHERE id=?"); $s->execute([$_GET['view']]); $view = $s->fetch();
    if ($view && !$view['is_read']) { $db->prepare("UPDATE contact_messages SET is_read=1 WHERE id=?")->execute([$_GET['view']]); }
}

$items = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
?>

<div class="admin-main">
  <div class="admin-topbar"><h1>✉️ Contact Messages</h1></div>

  <?php if($view): ?>
  <div class="admin-card">
    <h2>Message from: <?= clean($view['name']) ?></h2>
    <div style="font-size:14px;line-height:2.2;color:var(--text);">
      <b>Name:</b> <?= clean($view['name']) ?><br>
      <b>Phone:</b> <?= clean($view['phone']) ?><br>
      <b>Email:</b> <?= clean($view['email']) ?><br>
      <b>Subject:</b> <?= clean($view['subject']) ?><br>
      <b>Date:</b> <?= date('d M Y h:i A', strtotime($view['created_at'])) ?><br><br>
      <b>Message:</b><br>
      <div style="background:var(--light);padding:16px;border-radius:8px;margin-top:8px;"><?= nl2br(clean($view['message'])) ?></div>
    </div>
    <div style="margin-top:18px;display:flex;gap:10px;">
      <a href="mailto:<?= $view['email'] ?>" class="btn-primary">Reply via Email</a>
      <a href="tel:<?= $view['phone'] ?>" class="btn-secondary">Call</a>
      <a href="<?= SITE_URL ?>/admin/messages.php" class="btn-sm btn-view">← Back</a>
    </div>
  </div>
  <?php endif; ?>

  <div class="admin-card">
    <h2>All Messages (<?= count($items) ?>)</h2>
    <table class="admin-table">
      <thead><tr><th>Date</th><th>Name</th><th>Phone</th><th>Subject</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($items as $m): ?>
        <tr style="<?= !$m['is_read']?'font-weight:600;':'' ?>">
          <td><?= date('d M Y', strtotime($m['created_at'])) ?></td>
          <td><?= clean($m['name']) ?></td>
          <td><?= clean($m['phone']) ?></td>
          <td><?= clean(substr($m['subject'],0,25)) ?></td>
          <td><span class="badge badge-<?= $m['is_read']?'success':'warning' ?>"><?= $m['is_read']?'Read':'Unread' ?></span></td>
          <td><div class="action-btns">
            <a href="?view=<?= $m['id'] ?>" class="btn-sm btn-view">View</a>
            <a href="?delete=<?= $m['id'] ?>" class="btn-sm btn-delete" data-confirm="Delete?">Del</a>
          </div></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($items)): ?><tr><td colspan="6" style="text-align:center;color:#9ca3af;padding:30px;">No messages yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body></html>
