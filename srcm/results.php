<?php
require_once __DIR__ . '/includes/config.php';
$db = getDB();
$results = $db->query("SELECT * FROM results WHERE is_active=1 ORDER BY exam_year DESC, class")->fetchAll();
$toppers = $db->query("SELECT * FROM toppers WHERE is_active=1 ORDER BY exam_year DESC, rank_position")->fetchAll();
$pageTitle = 'Results';
include __DIR__ . '/includes/header.php';
?>
<div class="page-banner"><div class="container"><h1>Exam Results</h1><div class="breadcrumb">Home → <span>Results</span></div></div></div>
<div class="page-content"><div class="container">
  <div class="section-header"><div class="section-tag">Academic Performance</div><h2 class="section-title">U.P. Board Examination Results</h2><p class="section-desc">SRCM Inter College is proud of its consistent excellent performance in U.P. Board examinations year after year.</p></div>

  <?php
  $years = array_unique(array_column($results, 'exam_year'));
  foreach($years as $year):
    $yearResults = array_filter($results, fn($r) => $r['exam_year'] == $year);
    $yearToppers = array_filter($toppers, fn($t) => $t['exam_year'] == $year);
  ?>
  <div class="result-card">
    <h3>🏆 U.P. Board Results — <?= $year ?></h3>
    <?php foreach($yearResults as $r): ?>
    <div style="margin-bottom:20px;">
      <h4 style="color:var(--primary);font-size:15px;margin-bottom:10px;"><?= clean($r['class']) ?> <?= $r['stream']!='All'?'— '.$r['stream']:'' ?></h4>
      <div class="result-stats">
        <div class="rs"><div class="num"><?= $r['total_students'] ?></div><div class="lbl">Total Students</div></div>
        <div class="rs"><div class="num"><?= $r['passed_students'] ?></div><div class="lbl">Passed</div></div>
        <div class="rs"><div class="num"><?= $r['first_div'] ?></div><div class="lbl">First Division</div></div>
        <div class="rs"><div class="num"><?= number_format($r['pass_percent'],1) ?>%</div><div class="lbl">Pass %</div></div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if(!empty($yearToppers)): ?>
    <h4 style="color:var(--primary);font-size:15px;margin:18px 0 10px;">🥇 Toppers <?= $year ?></h4>
    <?php foreach($yearToppers as $t): ?>
    <div class="topper-row">
      <div class="topper-rank"><?= $t['rank_position'] ?></div>
      <div class="topper-info"><h4><?= clean($t['student_name']) ?></h4><p><?= clean($t['class']) ?> <?= $t['stream']!='All'?'| '.$t['stream']:'' ?></p></div>
      <div class="topper-marks"><?= $t['marks_obtained'] ?>/<?= $t['total_marks'] ?></div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>

  <div class="info-box"><p>📌 U.P. Board official results: <a href="https://upmsp.edu.in" target="_blank" style="color:var(--primary);font-weight:600;">upmsp.edu.in</a></p></div>
</div></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
