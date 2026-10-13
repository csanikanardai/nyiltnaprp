<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
$pageTitle = 'Regisztráció';
$success = isset($_GET['siker']);
$error = $_GET['hiba'] ?? '';
$pdo = db();
$trades = $pdo->query('SELECT id, name FROM trades WHERE active = 1 ORDER BY sort_order, name')->fetchAll();
require __DIR__ . '/includes/header.php';
?>
<main class="page-shell">
  <nav class="topbar">
    <a class="brand" href="index.php"><img src="medium_Nadasdy_Logo_2020_Technikum_KOZEPES_bec1a30946.png" width="60"></span><span><?= e(SCHOOL_NAME) ?><small>NYÍLT NAP 2026</small></span></a>
    <a class="nav-link" href="admin/login.php">Admin belépés <span aria-hidden="true">↗</span></a>
  </nav>

  <section class="hero">
    <div class="hero-copy">
      <span class="eyebrow"><span class="eyebrow-dot"></span> ISMERD MEG A LEHETŐSÉGEID</span>
      <h1>A következő<br>fejezet <em>itt kezdődik.</em></h1>
      <p>Regisztrálj nyílt napunkra, nézd meg a képzéseinket, és találd meg azt az utat, ami igazán neked való.</p>
      <div class="hero-actions">
        <a href="#regisztracio" class="button button-primary">Jelentkezem a nyílt napra <span>↗</span></a>
        <span class="hero-note"><span class="hero-note-dot"></span> A jelentkezés csak egy perc</span>
      </div>
      <div class="hero-points">
        <span><b>01</b> Fedezd fel a szakmákat</span>
        <span><b>02</b> Kérdezz bátran</span>
        <span><b>03</b> Tervezd a jövőd</span>
      </div>
    </div>
    <div class="hero-art" aria-label="Nyílt napi illusztráció">
      <div class="art-glow"></div><div class="art-circle circle-one"></div><div class="art-circle circle-two"></div>
      <div class="art-card">
        <div class="art-card-top"><span class="art-icon">✳</span><span>OPEN DAY</span></div>
        <div class="art-number">NYÍLT<br><span>NAP</span></div>
        <div class="art-line"></div>
        <div class="art-bottom"><span>Új utak.<br>Új lehetőségek.</span><span class="art-arrow">↗</span></div>
      </div>
      <span class="floating-tag tag-top">A TE JÖVŐD</span>
      <span class="floating-tag tag-bottom">#NYÍLTNAP</span>
    </div>
  </section>

  <section class="form-section" id="regisztracio">
    <div class="section-heading">
      <div><span class="eyebrow">ELSŐ LÉPÉS</span><h2>Regisztráció</h2></div>
      <span class="required-note"><span class="required-star">*</span> Kötelező mezők</span>
    </div>
    <?php if ($success): ?>
      <div class="alert success"><strong>Sikeres regisztráció!</strong> Köszönjük, hogy jelentkeztél a nyílt napra.</div>
    <?php endif; ?>
    <?php if ($error === 'ures'): ?>
      <div class="alert error">Kérjük, tölts ki minden kötelező mezőt, és válassz legalább egy szakmát.</div>
    <?php elseif ($error === 'ervenytelen'): ?>
      <div class="alert error">Ellenőrizd a megadott adatokat, majd próbáld újra.</div>
    <?php elseif ($error === 'hiba'): ?>
      <div class="alert error">A mentés most nem sikerült. Próbáld meg később.</div>
    <?php endif; ?>

    <form class="registration-card" action="submit.php" method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <div class="form-grid">
        <label class="field field-wide"><span>Teljes név <i>*</i></span><input type="text" name="full_name" maxlength="150" placeholder="Pl. Kiss Anna" autocomplete="name" required></label>
        <label class="field field-wide"><span>Melyik iskolából érkezel? <i>*</i></span><input type="text" name="previous_school" maxlength="200" placeholder="Iskola neve, település" required></label>
        <fieldset class="field field-wide trade-field">
          <legend>Mely szakmák érdekelnek? <i>*</i><small>Többet is megjelölhetsz.</small></legend>
          <?php if (!$trades): ?>
            <p class="muted">A szakmák még nincsenek feltöltve. Importáld a database.sql fájlt.</p>
          <?php else: ?>
            <div class="trade-grid">
              <?php foreach ($trades as $trade): ?>
                <label class="trade-option"><input type="checkbox" name="trade_ids[]" value="<?= (int)$trade['id'] ?>"><span class="trade-check">✓</span><span><?= e($trade['name']) ?></span><span class="trade-plus">+</span></label>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </fieldset>
        <fieldset class="field">
          <legend>Hogyan járnál be? <i>*</i></legend>
          <div class="choice-row">
            <label class="choice"><input type="radio" name="residence" value="bejárós" required><span class="choice-icon">↗</span><span>Bejárós</span></label>
            <label class="choice"><input type="radio" name="residence" value="koleszos" required><span class="choice-icon">⌂</span><span>Koleszos</span></label>
          </div>
        </fieldset>
        <fieldset class="field">
          <legend>Végzős vagy? <i>*</i></legend>
          <div class="choice-row">
            <label class="choice"><input type="radio" name="is_graduating" value="1" required><span class="choice-icon">✓</span><span>Igen</span></label>
            <label class="choice"><input type="radio" name="is_graduating" value="0" required><span class="choice-icon">→</span><span>Nem</span></label>
          </div>
        </fieldset>
      </div>
      <div class="form-bottom"><p><span class="privacy-dot">i</span> Az adatokat kizárólag a nyílt napi jelentkezések kezelésére használjuk.</p><button class="button button-primary" type="submit">Jelentkezés elküldése <span>↗</span></button></div>
    </form>
  </section>
  <section class="bottom-cta"><span class="cta-symbol">✳</span><div><h3>Találkozzunk a nyílt napon!</h3><p>Hozd magaddal a kérdéseidet — a többit bízd ránk.</p></div><a href="#regisztracio" class="button button-light">Regisztrálok ↑</a></section>
  <?php require __DIR__ . '/includes/footer.php'; ?>
</main>
