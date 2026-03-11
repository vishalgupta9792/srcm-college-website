<?php
require_once __DIR__ . '/config.php';
$pageTitle = $pageTitle ?? 'Page';
$placeholderTitle = $placeholderTitle ?? $pageTitle;
$placeholderMessage = $placeholderMessage ?? 'This page content will be updated soon.';
include __DIR__ . '/header.php';
?>
<div class="page-banner">
  <div class="container">
    <h1><?= clean($placeholderTitle) ?></h1>
    <div class="breadcrumb">Home → <span><?= clean($placeholderTitle) ?></span></div>
  </div>
</div>

<div class="page-content">
  <div class="container">
    <div class="content-box">
      <h2><?= clean($placeholderTitle) ?></h2>
      <div class="info-box">
        <p><?= clean($placeholderMessage) ?></p>
      </div>
      <p>Please use the main menu to continue browsing available sections of the website.</p>
    </div>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
