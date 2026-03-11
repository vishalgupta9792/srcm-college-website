<?php
// ============================================
// SRCM Inter College - Configuration File
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // Change this
define('DB_PASS', 'your_password'); // Change this
define('DB_NAME', 'srcm_college');

define('SITE_URL', 'http://localhost/srcm'); // Change to your domain
define('SITE_NAME', 'SRCM Inter College');
define('UPLOAD_PATH', __DIR__ . '/../assets/uploads/');
define('UPLOAD_URL', SITE_URL . '/assets/uploads/');

// Database Connection
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER, DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }
    }
    return $pdo;
}

// Get setting value
function getSetting($key) {
    $db = getDB();
    $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $row = $stmt->fetch();
    return $row ? $row['setting_value'] : '';
}

// Session start
if (session_status() === PHP_SESSION_NONE) session_start();

// Helper: sanitize input
function clean($str) {
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

// Helper: check admin login
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: ' . SITE_URL . '/admin/login.php');
        exit;
    }
}

// Helper: redirect
function redirect($url) {
    header("Location: $url");
    exit;
}

// Helper: format date Hindi style
function formatDate($date) {
    return date('d M Y', strtotime($date));
}

// Helper: upload image
function uploadImage($file, $folder = 'general') {
    $uploadDir = UPLOAD_PATH . $folder . '/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp'];
    if (!in_array($ext, $allowed)) return false;
    if ($file['size'] > 5 * 1024 * 1024) return false;
    $filename = uniqid() . '_' . time() . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
        return $folder . '/' . $filename;
    }
    return false;
}
?>
