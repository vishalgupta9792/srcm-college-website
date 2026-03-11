<?php
require_once __DIR__ . '/../includes/config.php';
$error = '';

if (isAdminLoggedIn()) redirect(SITE_URL . '/admin/dashboard.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ? AND is_active = 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['full_name'];
        $_SESSION['admin_role'] = $user['role'];
        $db->prepare("UPDATE admin_users SET last_login=NOW() WHERE id=?")->execute([$user['id']]);
        redirect(SITE_URL . '/admin/dashboard.php');
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login — SRCM Inter College</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Noto+Sans:wght@400;600&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { background: linear-gradient(135deg,#1a3a5c,#0d2a44); min-height:100vh; display:flex; align-items:center; justify-content:center; font-family:'Noto Sans',sans-serif; }
.login-box { background:white; border-radius:16px; padding:45px 40px; width:100%; max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.3); }
.login-logo { text-align:center; margin-bottom:30px; }
.login-logo .icon { width:80px; height:80px; background:linear-gradient(135deg,#1a3a5c,#2d6aa0); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-size:32px; font-weight:900; font-family:'Playfair Display',serif; margin:0 auto 14px; }
.login-logo h1 { font-family:'Playfair Display',serif; font-size:22px; color:#1a3a5c; }
.login-logo p { font-size:13px; color:#6b7280; margin-top:5px; }
.form-group { margin-bottom:18px; }
.form-group label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
.form-group input { width:100%; padding:11px 14px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; transition:.2s; }
.form-group input:focus { border-color:#1a3a5c; outline:none; box-shadow:0 0 0 3px rgba(26,58,92,0.1); }
.btn-login { width:100%; padding:13px; background:#1a3a5c; color:white; border:none; border-radius:8px; font-size:15px; font-weight:700; cursor:pointer; font-family:inherit; transition:.2s; margin-top:5px; }
.btn-login:hover { background:#c8960c; }
.error { background:#fee2e2; color:#991b1b; padding:12px 16px; border-radius:8px; font-size:13.5px; margin-bottom:18px; }
.hint { text-align:center; font-size:12px; color:#9ca3af; margin-top:18px; }
</style>
</head>
<body>
<div class="login-box">
  <div class="login-logo">
    <div class="icon">S</div>
    <h1>SRCM Inter College</h1>
    <p>Admin Panel Login</p>
  </div>
  <?php if($error): ?><div class="error">⚠️ <?= $error ?></div><?php endif; ?>
  <form method="POST">
    <div class="form-group"><label>Username</label><input type="text" name="username" placeholder="Enter username" required autofocus></div>
    <div class="form-group"><label>Password</label><input type="password" name="password" placeholder="Enter password" required></div>
    <button type="submit" class="btn-login">Login to Admin Panel →</button>
  </form>
  <div class="hint">Default: admin / srcm@2025</div>
</div>
</body>
</html>
