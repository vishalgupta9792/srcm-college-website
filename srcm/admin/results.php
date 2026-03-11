<?php
$adminTitle = 'Results';
require_once __DIR__ . '/includes/admin_header.php';
$db = getDB();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_result') {
    $stmt = $db->prepare("INSERT INTO results (exam_year, class, stream, total_students, passed_students, first_div, second_div, distinctions, pass_percent, is_active) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        clean($_POST['exam_year']),
        clean($_POST['class']),
        clean($_POST['stream']),
        (int) $_POST['total_students'],
        (int) $_POST['passed_students'],
        (int) $_POST['first_div'],
        (int) $_POST['second_div'],
        (int) $_POST['distinctions'],
        (float) $_POST['pass_percent'],
        isset($_POST['is_active']) ? 1 : 0,
    ]);
    $msg = 'success|Result record added successfully!';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_result') {
    $stmt = $db->prepare("UPDATE results SET exam_year=?, class=?, stream=?, total_students=?, passed_students=?, first_div=?, second_div=?, distinctions=?, pass_percent=?, is_active=? WHERE id=?");
    $stmt->execute([
        clean($_POST['exam_year']),
        clean($_POST['class']),
        clean($_POST['stream']),
        (int) $_POST['total_students'],
        (int) $_POST['passed_students'],
        (int) $_POST['first_div'],
        (int) $_POST['second_div'],
        (int) $_POST['distinctions'],
        (float) $_POST['pass_percent'],
        isset($_POST['is_active']) ? 1 : 0,
        $_POST['id'],
    ]);
    $msg = 'success|Result record updated successfully!';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_topper') {
    $stmt = $db->prepare("INSERT INTO toppers (exam_year, student_name, class, stream, marks_obtained, total_marks, rank_position, is_active) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->execute([
        clean($_POST['topper_exam_year']),
        clean($_POST['student_name']),
        clean($_POST['topper_class']),
        clean($_POST['topper_stream']),
        (int) $_POST['marks_obtained'],
        (int) $_POST['total_marks'],
        (int) $_POST['rank_position'],
        isset($_POST['topper_active']) ? 1 : 0,
    ]);
    $msg = 'success|Topper added successfully!';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_topper') {
    $stmt = $db->prepare("UPDATE toppers SET exam_year=?, student_name=?, class=?, stream=?, marks_obtained=?, total_marks=?, rank_position=?, is_active=? WHERE id=?");
    $stmt->execute([
        clean($_POST['topper_exam_year']),
        clean($_POST['student_name']),
        clean($_POST['topper_class']),
        clean($_POST['topper_stream']),
        (int) $_POST['marks_obtained'],
        (int) $_POST['total_marks'],
        (int) $_POST['rank_position'],
        isset($_POST['topper_active']) ? 1 : 0,
        $_POST['id'],
    ]);
    $msg = 'success|Topper updated successfully!';
}

if (isset($_GET['delete_result'])) {
    $db->prepare("DELETE FROM results WHERE id=?")->execute([$_GET['delete_result']]);
    $msg = 'success|Result record deleted successfully!';
}

if (isset($_GET['delete_topper'])) {
    $db->prepare("DELETE FROM toppers WHERE id=?")->execute([$_GET['delete_topper']]);
    $msg = 'success|Topper deleted successfully!';
}

if (isset($_GET['toggle_result'])) {
    $db->prepare("UPDATE results SET is_active = NOT is_active WHERE id=?")->execute([$_GET['toggle_result']]);
    redirect(SITE_URL . '/admin/results.php');
}

if (isset($_GET['toggle_topper'])) {
    $db->prepare("UPDATE toppers SET is_active = NOT is_active WHERE id=?")->execute([$_GET['toggle_topper']]);
    redirect(SITE_URL . '/admin/results.php');
}

$editResult = null;
if (isset($_GET['edit_result'])) {
    $stmt = $db->prepare("SELECT * FROM results WHERE id=?");
    $stmt->execute([$_GET['edit_result']]);
    $editResult = $stmt->fetch();
}

$editTopper = null;
if (isset($_GET['edit_topper'])) {
    $stmt = $db->prepare("SELECT * FROM toppers WHERE id=?");
    $stmt->execute([$_GET['edit_topper']]);
    $editTopper = $stmt->fetch();
}

$results = $db->query("SELECT * FROM results ORDER BY exam_year DESC, class, stream")->fetchAll();
$toppers = $db->query("SELECT * FROM toppers ORDER BY exam_year DESC, rank_position ASC, id ASC")->fetchAll();
[$msgType, $msgText] = $msg ? explode('|', $msg, 2) : ['', ''];
?>

<div class="admin-main">
  <div class="admin-topbar">
    <h1>Results</h1>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <a href="<?= SITE_URL ?>/admin/results.php?action=add_result" class="btn-primary">+ Add Result</a>
      <a href="<?= SITE_URL ?>/admin/results.php?action=add_topper" class="btn-primary">+ Add Topper</a>
    </div>
  </div>

  <?php if ($msgText): ?><div class="form-<?= $msgType ?> alert-auto" style="margin-bottom:20px;padding:12px 18px;border-radius:8px;"><?= $msgText ?></div><?php endif; ?>

  <?php if (($_GET['action'] ?? '') === 'add_result' || $editResult): ?>
  <div class="admin-card">
    <h2><?= $editResult ? 'Edit' : 'Add New' ?> Result Record</h2>
    <form method="POST">
      <input type="hidden" name="action" value="<?= $editResult ? 'edit_result' : 'add_result' ?>">
      <?php if ($editResult): ?><input type="hidden" name="id" value="<?= $editResult['id'] ?>"><?php endif; ?>
      <div class="form-row">
        <div class="form-group"><label>Exam Year</label><input type="text" name="exam_year" value="<?= clean($editResult['exam_year'] ?? date('Y')) ?>" required></div>
        <div class="form-group"><label>Class</label><input type="text" name="class" value="<?= clean($editResult['class'] ?? '') ?>" required placeholder="Class 12"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Stream</label><input type="text" name="stream" value="<?= clean($editResult['stream'] ?? 'All') ?>" required></div>
        <div class="form-group"><label>Pass Percentage</label><input type="number" step="0.01" name="pass_percent" value="<?= clean($editResult['pass_percent'] ?? '') ?>" required></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Total Students</label><input type="number" name="total_students" value="<?= clean($editResult['total_students'] ?? 0) ?>" required></div>
        <div class="form-group"><label>Passed Students</label><input type="number" name="passed_students" value="<?= clean($editResult['passed_students'] ?? 0) ?>" required></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>First Division</label><input type="number" name="first_div" value="<?= clean($editResult['first_div'] ?? 0) ?>" required></div>
        <div class="form-group"><label>Second Division</label><input type="number" name="second_div" value="<?= clean($editResult['second_div'] ?? 0) ?>" required></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Distinctions</label><input type="number" name="distinctions" value="<?= clean($editResult['distinctions'] ?? 0) ?>" required></div>
        <div class="form-group" style="display:flex;align-items:center;gap:10px;padding-top:28px;">
          <input type="checkbox" name="is_active" id="result_active" <?= ($editResult['is_active'] ?? 1) ? 'checked' : '' ?> style="width:18px;height:18px;">
          <label for="result_active" style="margin:0;font-weight:600;">Active result</label>
        </div>
      </div>
      <button type="submit" class="btn-primary">Save Result</button>
      <a href="<?= SITE_URL ?>/admin/results.php" class="btn-secondary" style="margin-left:10px;">Cancel</a>
    </form>
  </div>
  <?php endif; ?>

  <?php if (($_GET['action'] ?? '') === 'add_topper' || $editTopper): ?>
  <div class="admin-card">
    <h2><?= $editTopper ? 'Edit' : 'Add New' ?> Topper</h2>
    <form method="POST">
      <input type="hidden" name="action" value="<?= $editTopper ? 'edit_topper' : 'add_topper' ?>">
      <?php if ($editTopper): ?><input type="hidden" name="id" value="<?= $editTopper['id'] ?>"><?php endif; ?>
      <div class="form-row">
        <div class="form-group"><label>Exam Year</label><input type="text" name="topper_exam_year" value="<?= clean($editTopper['exam_year'] ?? date('Y')) ?>" required></div>
        <div class="form-group"><label>Student Name</label><input type="text" name="student_name" value="<?= clean($editTopper['student_name'] ?? '') ?>" required></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Class</label><input type="text" name="topper_class" value="<?= clean($editTopper['class'] ?? '') ?>" required></div>
        <div class="form-group"><label>Stream</label><input type="text" name="topper_stream" value="<?= clean($editTopper['stream'] ?? 'All') ?>" required></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Marks Obtained</label><input type="number" name="marks_obtained" value="<?= clean($editTopper['marks_obtained'] ?? 0) ?>" required></div>
        <div class="form-group"><label>Total Marks</label><input type="number" name="total_marks" value="<?= clean($editTopper['total_marks'] ?? 500) ?>" required></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Rank Position</label><input type="number" name="rank_position" value="<?= clean($editTopper['rank_position'] ?? 1) ?>" required></div>
        <div class="form-group" style="display:flex;align-items:center;gap:10px;padding-top:28px;">
          <input type="checkbox" name="topper_active" id="topper_active" <?= ($editTopper['is_active'] ?? 1) ? 'checked' : '' ?> style="width:18px;height:18px;">
          <label for="topper_active" style="margin:0;font-weight:600;">Active topper</label>
        </div>
      </div>
      <button type="submit" class="btn-primary">Save Topper</button>
      <a href="<?= SITE_URL ?>/admin/results.php" class="btn-secondary" style="margin-left:10px;">Cancel</a>
    </form>
  </div>
  <?php endif; ?>

  <div class="admin-card">
    <h2>Result Records (<?= count($results) ?>)</h2>
    <table class="admin-table">
      <thead><tr><th>Year</th><th>Class</th><th>Stream</th><th>Students</th><th>Pass %</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($results as $item): ?>
        <tr>
          <td><?= clean($item['exam_year']) ?></td>
          <td><?= clean($item['class']) ?></td>
          <td><?= clean($item['stream']) ?></td>
          <td><?= (int) $item['passed_students'] ?>/<?= (int) $item['total_students'] ?></td>
          <td><?= number_format((float) $item['pass_percent'], 2) ?>%</td>
          <td><span class="badge badge-<?= $item['is_active'] ? 'success' : 'warning' ?>"><?= $item['is_active'] ? 'Active' : 'Hidden' ?></span></td>
          <td><div class="action-btns">
            <a href="?edit_result=<?= $item['id'] ?>" class="btn-sm btn-edit">Edit</a>
            <a href="?toggle_result=<?= $item['id'] ?>" class="btn-sm btn-view"><?= $item['is_active'] ? 'Hide' : 'Show' ?></a>
            <a href="?delete_result=<?= $item['id'] ?>" class="btn-sm btn-delete" data-confirm="Delete this result record?">Delete</a>
          </div></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="admin-card">
    <h2>Toppers (<?= count($toppers) ?>)</h2>
    <table class="admin-table">
      <thead><tr><th>Year</th><th>Name</th><th>Class</th><th>Marks</th><th>Rank</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($toppers as $item): ?>
        <tr>
          <td><?= clean($item['exam_year']) ?></td>
          <td><?= clean($item['student_name']) ?></td>
          <td><?= clean($item['class']) ?> | <?= clean($item['stream']) ?></td>
          <td><?= (int) $item['marks_obtained'] ?>/<?= (int) $item['total_marks'] ?></td>
          <td><?= (int) $item['rank_position'] ?></td>
          <td><span class="badge badge-<?= $item['is_active'] ? 'success' : 'warning' ?>"><?= $item['is_active'] ? 'Active' : 'Hidden' ?></span></td>
          <td><div class="action-btns">
            <a href="?edit_topper=<?= $item['id'] ?>" class="btn-sm btn-edit">Edit</a>
            <a href="?toggle_topper=<?= $item['id'] ?>" class="btn-sm btn-view"><?= $item['is_active'] ? 'Hide' : 'Show' ?></a>
            <a href="?delete_topper=<?= $item['id'] ?>" class="btn-sm btn-delete" data-confirm="Delete this topper?">Delete</a>
          </div></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body></html>
