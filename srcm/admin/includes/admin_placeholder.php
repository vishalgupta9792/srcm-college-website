<?php
require_once __DIR__ . '/admin_header.php';
$adminPageTitle = $adminPageTitle ?? 'Admin Page';
$adminPlaceholderMessage = $adminPlaceholderMessage ?? 'This admin section will be implemented soon.';
?>
<div class="admin-topbar">
  <h1><?= clean($adminPageTitle) ?></h1>
</div>

<div class="admin-card">
  <div class="alert alert-info">
    <?= clean($adminPlaceholderMessage) ?>
  </div>
</div>

</div>
</body>
</html>
