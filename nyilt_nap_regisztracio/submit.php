<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
verify_csrf();

$name = trim((string)($_POST['full_name'] ?? ''));
$school = trim((string)($_POST['previous_school'] ?? ''));
$residence = (string)($_POST['residence'] ?? '');
$graduatingRaw = (string)($_POST['is_graduating'] ?? '');
$tradeIds = $_POST['trade_ids'] ?? [];

if ($name === '' || $school === '' || !in_array($residence, ['bejárós', 'koleszos'], true)
    || !in_array($graduatingRaw, ['0', '1'], true) || !is_array($tradeIds) || count($tradeIds) < 1) {
    header('Location: index.php?hiba=ures#regisztracio');
    exit;
}
if (mb_strlen($name) > 150 || mb_strlen($school) > 200) {
    header('Location: index.php?hiba=ervenytelen#regisztracio');
    exit;
}

$tradeIds = array_values(array_unique(array_filter(array_map('intval', $tradeIds), fn($id) => $id > 0)));
if (!$tradeIds) {
    header('Location: index.php?hiba=ures#regisztracio');
    exit;
}

try {
    $pdo = db();
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('INSERT INTO registrations (full_name, previous_school, residence, is_graduating) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $school, $residence, (int)$graduatingRaw]);
    $registrationId = (int)$pdo->lastInsertId();

    $tradeCheck = $pdo->prepare('SELECT id FROM trades WHERE id = ? AND active = 1');
    $linkTrade = $pdo->prepare('INSERT INTO registration_trades (registration_id, trade_id) VALUES (?, ?)');
    foreach ($tradeIds as $tradeId) {
        $tradeCheck->execute([$tradeId]);
        if ($tradeCheck->fetch()) $linkTrade->execute([$registrationId, $tradeId]);
    }
    $count = $pdo->prepare('SELECT COUNT(*) FROM registration_trades WHERE registration_id = ?');
    $count->execute([$registrationId]);
    if ((int)$count->fetchColumn() === 0) {
        $pdo->rollBack();
        header('Location: index.php?hiba=ervenytelen#regisztracio');
        exit;
    }
    $pdo->commit();
    header('Location: index.php?siker=1#regisztracio');
    exit;
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    error_log($e->getMessage());
    header('Location: index.php?hiba=hiba#regisztracio');
    exit;
}
