<?php
  $pillCls = match (true) {
    $gap <= 5  => 'pill-green',
    $gap <= 15 => 'pill-gold',
    $gap <= 30 => 'pill-amber',
    default    => 'pill-red',
  };
  $scoreMsg = match (true) {
    $score >= 95 => 'Parfait ! 🎯',
    $score >= 80 => 'Excellent !',
    $score >= 60 => 'Bien joué !',
    $score >= 40 => 'Pas mal…',
    default      => 'Encore un effort !',
  };
?>

<div class="game-page">

  <div class="game-hero container">
    <h1><?= $scoreMsg ?></h1>
    <p>Voici le résultat de votre estimation.</p>
  </div>

  <div class="game-result-layout container">

    <!-- Score card -->
    <div class="result-score-card">
      <div class="result-score-value"><?= $score ?></div>
      <div class="result-score-label">points</div>

      <div class="result-row">
        <span>Votre estimation</span>
        <strong><?= number_format($guess, 0, ',', ' ') ?> €</strong>
      </div>
      <div class="result-row">
        <span>Loyer réel</span>
        <strong><?= number_format((int)$estate['rent'], 0, ',', ' ') ?> €</strong>
      </div>
      <div class="result-row">
        <span>Écart</span>
        <span class="pill <?= $pillCls ?>"><?= $gap ?> %</span>
      </div>
    </div>

    <!-- Estate recap -->
    <div class="result-estate-recap">
      <?php if (!empty($estate['image1'])): ?>
        <img
          src="<?= BASE_URL ?>/public/img/estates/<?= htmlspecialchars($estate['image1']) ?>"
          alt="Photo du bien"
          class="result-photo"
        >
      <?php endif; ?>

      <div class="result-estate-info">
        <span class="pill pill-purple"><?= htmlspecialchars($estate['type_label'] ?? '') ?></span>
        <p class="result-city"><?= htmlspecialchars($estate['city']) ?> (<?= htmlspecialchars(str_pad($estate['postcode'], 5, '0', STR_PAD_LEFT)) ?>)</p>
        <p><?= htmlspecialchars($estate['square_meters']) ?> m²</p>
      </div>
    </div>

  </div>

  <!-- Actions -->
  <div class="game-result-actions container">
    <a href="<?= BASE_URL ?>/jeu" class="btn-primary">Jouer encore</a>
    <?php if (empty($_SESSION['user_id'])): ?>
      <a href="<?= BASE_URL ?>/auth?action=register" class="btn-secondary">Créer un compte pour sauvegarder</a>
    <?php else: ?>
      <a href="<?= BASE_URL ?>/profil" class="btn-secondary">Voir mon profil</a>
    <?php endif; ?>
  </div>

</div>
