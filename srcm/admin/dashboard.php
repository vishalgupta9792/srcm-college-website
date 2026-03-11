<?php
$adminTitle = 'Dashboard';
require_once __DIR__ . '/includes/admin_header.php';
$db = getDB();

$stats = [
  'announcements' => $db->query("SELECT COUNT(*) FROM announcements WHERE is_active=1")->fetchColumn(),
  'gallery'       => $db->query("SELECT COUNT(*) FROM gallery WHERE is_active=1")->fetchColumn(),
  'enquiries'     => $db->query("SELECT COUNT(*) FROM enquiries WHERE status='new'")->fetchColumn(),
  'messages'      => $db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read=0")->fetchColumn(),
];

$recentEnquiries = $db->query("SELECT * FROM enquiries ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentMessages  = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<div class="admin-main">
  <button id="admin-toggle" style="display:none;margin-bottom:15px;padding:8px 16px;background:var(--primary);color:white;border:none;border-radius:6px;cursor:pointer;">☰ Menu</button>

  <div class="admin-topbar">
    <h1>📊 Dashboard</h1>
    <div style="font-size:13px;color:#6b7280;">Welcome, <?= $_SESSION['admin_name'] ?> | <?= date('d M Y, h:i A') ?></div>
  </div>

  <!-- STATS -->
  <div class="stat-boxes">
    <div class="stat-box"><div class="icon">📢</div><div class="info"><h3><?= $stats['announcements'] ?></h3><p>Announcements</p></div></div>
    <div class="stat-box"><div class="icon">📸</div><div class="info"><h3><?= $stats['gallery'] ?></h3><p>Gallery Photos</p></div></div>
    <div class="stat-box"><div class="icon">📝</div><div class="info"><h3><?= $stats['enquiries'] ?></h3><p>New Enquiries</p></div></div>
    <div class="stat-box"><div class="icon">✉️</div><div class="info"><h3><?= $stats['messages'] ?></h3><p>Unread Messages</p></div></div>
  </div>

  <!-- QUICK ACTIONS -->
  <div class="admin-card">
    <h2>⚡ Quick Actions</h2>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a href="<?= SITE_URL ?>/admin/announcements.php?action=add" class="btn-primary">+ Add Announcement</a>
      <a href="<?= SITE_URL ?>/admin/news.php?action=add" class="btn-primary">+ Add News</a>
      <a href="<?= SITE_URL ?>/admin/gallery.php?action=add" class="btn-primary">+ Upload Photo</a>
      <a href="<?= SITE_URL ?>/admin/sliders.php?action=add" class="btn-primary">+ Add Slider</a>
      <a href="<?= SITE_URL ?>/admin/tc.php?action=add" class="btn-primary">+ Add TC Record</a>
      <a href="<?= SITE_URL ?>/admin/results.php?action=add" class="btn-primary">+ Add Result</a>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:25px;">
    <!-- RECENT ENQUIRIES -->
    <div class="admin-card">
      <h2>📝 Recent Enquiries</h2>
      <table class="admin-table">
        <thead><tr><th>Name</th><th>Phone</th><th>Class</th><th>Status</th></tr></thead>
        <tbody>
          <?php foreach($recentEnquiries as $e): ?>
          <tr>
            <td><?= clean($e['student_name']) ?></td>
            <td><?= clean($e['phone']) ?></td>
            <td><?= clean($e['class_applying']) ?></td>
            <td><span class="badge badge-<?= $e['status']=='new'?'warning':($e['status']=='admitted'?'success':'info') ?>"><?= ucfirst($e['status']) ?></span></td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($recentEnquiries)): ?><tr><td colspan="4" style="text-align:center;color:#9ca3af;">No enquiries yet</td></tr><?php endif; ?>
        </tbody>
      </table>
      <a href="<?= SITE_URL ?>/admin/enquiries.php" style="font-size:13px;color:var(--primary);font-weight:600;display:inline-block;margin-top:12px;">View All →</a>
    </div>

    <!-- RECENT MESSAGES -->
    <div class="admin-card">
      <h2>✉️ Recent Messages</h2>
      <table class="admin-table">
        <thead><tr><th>Name</th><th>Phone</th><th>Subject</th><th>Status</th></tr></thead>
        <tbody>
          <?php foreach($recentMessages as $m): ?>
          <tr>
            <td><?= clean($m['name']) ?></td>
            <td><?= clean($m['phone']) ?></td>
            <td><?= clean(substr($m['subject'],0,20)) ?>...</td>
            <td><span class="badge badge-<?= $m['is_read']?'success':'warning' ?>"><?= $m['is_read']?'Read':'Unread' ?></span></td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($recentMessages)): ?><tr><td colspan="4" style="text-align:center;color:#9ca3af;">No messages yet</td></tr><?php endif; ?>
        </tbody>
      </table>
      <a href="<?= SITE_URL ?>/admin/messages.php" style="font-size:13px;color:var(--primary);font-weight:600;display:inline-block;margin-top:12px;">View All →</a>
    </div>
  </div>
</div>

<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body></html>
