<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php'); exit;
}
verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id && $id > 0) {
    $stmt = db()->prepare('DELETE FROM registrations WHERE id = ?');
    $stmt->execute([$id]);
}
header('Location: dashboard.php');
exit;
