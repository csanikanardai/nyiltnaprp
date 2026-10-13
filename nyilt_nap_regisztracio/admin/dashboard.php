<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
require_admin();
$pdo = db();
$total = (int)$pdo->query('SELECT COUNT(*) FROM registrations')->fetchColumn();
$graduates = (int)$pdo->query('SELECT COUNT(*) FROM registrations WHERE is_graduating = 1')->fetchColumn();
$dorm = (int)$pdo->query("SELECT COUNT(*) FROM registrations WHERE residence = 'koleszos'")->fetchColumn();
$gradDorm = (int)$pdo->query("SELECT COUNT(*) FROM registrations WHERE residence = 'koleszos' AND is_graduating = 1")->fetchColumn();
$tradeStats = $pdo->query('SELECT t.name, COUNT(rt.registration_id) AS total,
 SUM(CASE WHEN r.is_graduating = 1 THEN 1 ELSE 0 END) AS graduates,
 SUM(CASE WHEN r.residence = "koleszos" THEN 1 ELSE 0 END) AS dorms,
 SUM(CASE WHEN r.residence = "koleszos" AND r.is_graduating = 1 THEN 1 ELSE 0 END) AS grad_dorms
 FROM trades t LEFT JOIN registration_trades rt ON rt.trade_id = t.id
 LEFT JOIN registrations r ON r.id = rt.registration_id
 WHERE t.active = 1 GROUP BY t.id, t.name ORDER BY total DESC, t.name')->fetchAll();
$registrations = $pdo->query('SELECT r.id, r.full_name, r.previous_school, r.residence, r.is_graduating, r.created_at,
 GROUP_CONCAT(t.name ORDER BY t.name SEPARATOR ", ") AS trade_names
 FROM registrations r LEFT JOIN registration_trades rt ON rt.registration_id = r.id
 LEFT JOIN trades t ON t.id = rt.trade_id
 GROUP BY r.id ORDER BY r.created_at DESC, r.id DESC')->fetchAll();
$pageTitle = 'Admin dashboard';
require __DIR__ . '/../includes/header.php';
?>
<main class="admin-shell">
  <nav class="topbar admin-topbar"><a class="brand" href="../index.php"><span class="brand-mark">N</span><span><?= e(SCHOOL_NAME) ?><small>ADMIN FELÜLET</small></span></a><div class="admin-nav"><span class="admin-pill"><span class="online-dot"></span> Admin munkamenet</span><a class="nav-link" href="logout.php">Kilépés ↗</a></div></nav>
  <section class="admin-heading"><div><span class="eyebrow">ÁTTEKINTÉS / 2026</span><h1>Regisztrációk</h1><p>Minden jelentkezés és a legfontosabb számok egy helyen.</p></div><a class="button button-primary" href="../index.php" target="_blank">Űrlap megnyitása ↗</a></section>
  <section class="stats-grid">
    <article class="stat-card stat-dark"><span>ÖSSZES JELENTKEZŐ</span><strong><?= $total ?></strong><small>regisztráció</small><span class="stat-decoration">↗</span></article>
    <article class="stat-card"><span>VÉGZŐSÖK</span><strong><?= $graduates ?></strong><small>fő</small><span class="stat-icon">✳</span></article>
    <article class="stat-card"><span>KOLESZOSOK</span><strong><?= $dorm ?></strong><small>fő összesen</small><span class="stat-icon">⌂</span></article>
    <article class="stat-card"><span>VÉGZŐS + KOLESZ</span><strong><?= $gradDorm ?></strong><small>fő</small><span class="stat-icon">✓</span></article>
  </section>
  <section class="admin-panel">
    <div class="panel-heading"><div><span class="eyebrow">KÉPZÉSEK</span><h2>Érdeklődés szakmánként</h2></div><span class="panel-count"><?= count($tradeStats) ?> szakma</span></div>
    <div class="table-wrap"><table class="data-table"><thead><tr><th>Szakma</th><th>Jelentkező</th><th>Végzős</th><th>Koleszos</th><th>Végzős koleszos</th></tr></thead><tbody>
    <?php foreach ($tradeStats as $row): ?><tr><td class="strong-cell"><?= e($row['name']) ?></td><td><span class="number-chip"><?= (int)$row['total'] ?></span></td><td><?= (int)$row['graduates'] ?></td><td><?= (int)$row['dorms'] ?></td><td><?= (int)$row['grad_dorms'] ?></td></tr><?php endforeach; ?>
    <?php if (!$tradeStats): ?><tr><td colspan="5" class="empty-state">Még nincs megjeleníthető szakma.</td></tr><?php endif; ?>
    </tbody></table></div>
  </section>
  <section class="admin-panel">
    <div class="panel-heading"><div><span class="eyebrow">JELENTKEZŐK</span><h2>Összes regisztráció</h2></div><span class="panel-count"><?= $total ?> rekord</span></div>
    <div class="table-wrap"><table class="data-table registration-table"><thead><tr><th>Név / iskola</th><th>Érdekli</th><th>Bejárás</th><th>Végzős</th><th>Időpont</th><th>Művelet</th></tr></thead><tbody>
    <?php foreach ($registrations as $r): ?><tr><td><div class="person-cell"><span class="avatar"><?= e(mb_strtoupper(mb_substr($r['full_name'], 0, 1))) ?></span><span><strong><?= e($r['full_name']) ?></strong><small><?= e($r['previous_school']) ?></small></span></div></td><td class="trade-names"><?= e($r['trade_names'] ?: '—') ?></td><td><span class="status-tag <?= $r['residence'] === 'koleszos' ? 'status-purple' : 'status-blue' ?>"><?= e(ucfirst($r['residence'])) ?></span></td><td><?= (int)$r['is_graduating'] === 1 ? '<span class="yes-tag">Igen</span>' : 'Nem' ?></td><td class="date-cell"><?= e(date('Y.m.d. H:i', strtotime($r['created_at']))) ?></td><td><form method="post" action="delete.php" onsubmit="return confirm('Biztosan törlöd ezt a jelentkezést?');"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="delete-button" type="submit" aria-label="Jelentkezés törlése">Törlés</button></form></td></tr><?php endforeach; ?>
    <?php if (!$registrations): ?><tr><td colspan="6" class="empty-state">Még nincs jelentkezés. Az első kitöltés itt fog megjelenni.</td></tr><?php endif; ?>
    </tbody></table></div>
  </section>
  <p class="admin-footnote">Az azonos nevű jelentkezők külön rekordként szerepelnek, minden jelentkezés saját azonosítót kap.</p>
  <?php require __DIR__ . '/../includes/footer.php'; ?>
</main>
