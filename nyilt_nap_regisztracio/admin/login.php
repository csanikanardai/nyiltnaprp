<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
if (!empty($_SESSION['is_admin'])) {
    header('Location: dashboard.php'); exit;
}
$error = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = (string)($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    if (hash_equals(ADMIN_USERNAME, $username) && hash_equals(ADMIN_PASSWORD, $password)) {
        session_regenerate_id(true);
        $_SESSION['is_admin'] = true;
        header('Location: dashboard.php'); exit;
    }
    $error = true;
}
$pageTitle = 'Admin belépés';
require __DIR__ . '/../includes/header.php';
?>
<main class="admin-login-wrap">
  <a class="brand login-brand" href="../index.php"><span class="brand-mark">N</span><span><?= e(SCHOOL_NAME) ?><small>NYÍLT NAP 2026</small></span></a>
  <section class="login-card">
    <span class="eyebrow">ADMINISZTRÁCIÓ</span><h1>Jó újra látni.</h1><p>Lépj be a regisztrációk kezeléséhez.</p>
    <?php if ($error): ?><div class="alert error">Hibás felhasználónév vagy jelszó.</div><?php endif; ?>
    <form method="post" class="login-form">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <label class="field"><span>Felhasználónév</span><input name="username" autocomplete="username" required></label>
      <label class="field"><span>Jelszó</span><input type="password" name="password" autocomplete="current-password" required></label>
      <button class="button button-primary full-width" type="submit">Belépés <span>↗</span></button>
    </form>
    <a class="back-link" href="../index.php">← Vissza a regisztrációhoz</a>
  </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
