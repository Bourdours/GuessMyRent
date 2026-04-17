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

<section class="game-page">

  <div class="game-hero container">
    <h1><?= $scoreMsg ?></h1>
    <p>Voici le résultat de votre estimation.</p>
  </div>

  <div class="game-result-layout container">

    <!-- Score card -->
    <article class="result-score-card">
      <p class="result-score-value"><?= $score ?></p>
      <p class="result-score-label">points</p>

      <dl>
        <div class="result-row">
          <dt>Votre estimation</dt>
          <dd><strong><?= number_format($guess, 0, ',', ' ') ?> €</strong></dd>
        </div>
        <div class="result-row">
          <dt>Loyer réel</dt>
          <dd><strong><?= number_format((int)$estate['rent'], 0, ',', ' ') ?> €</strong></dd>
        </div>
        <div class="result-row">
          <dt>Écart</dt>
          <dd><span class="pill <?= $pillCls ?>"><?= $gap ?> %</span></dd>
        </div>
      </dl>
    </article>

    <!-- Estate recap -->
    <figure class="result-estate-recap">
      <?php if (!empty($estate['image1'])): ?>
        <img
          src="<?= BASE_URL ?>/public/img/estates/<?= htmlspecialchars($estate['image1']) ?>"
          alt="Photo du bien"
          class="result-photo">
      <?php endif; ?>

      <figcaption class="result-estate-info">
        <span class="pill pill-purple"><?= htmlspecialchars($estate['type_label'] ?? '') ?></span>
        <p class="result-city"><?= htmlspecialchars($estate['city']) ?> (<?= htmlspecialchars(str_pad($estate['postcode'], 5, '0', STR_PAD_LEFT)) ?>)</p>
        <p><?= htmlspecialchars($estate['square_meters']) ?> m²</p>
      </figcaption>
    </figure>

  </div>

  <!-- Actions -->
  <div class="game-result-actions container">
    <?php if ($isGuestLimited ?? false): ?>
      <div class="alert alert-warning alert-full">
        <strong>C'était votre partie gratuite !</strong><br>
        Inscrivez-vous <strong>gratuitement</strong> pour jouer sans limite et <strong>enregistrer toutes vos parties</strong> — le seul plafond, c'est le nombre de biens disponibles, et votre progression vous attend dans votre profil.
      </div>
      <a href="<?= BASE_URL ?>/auth?action=register" class="btn-primary">Créer un compte</a>
      <a href="<?= BASE_URL ?>/auth?action=login" class="btn-secondary">Se connecter</a>
    <?php else: ?>
      <div class="alert alert-info alert-full">
        <strong>Partie enregistrée !</strong> Retrouvez vos statistiques dans votre profil.
      </div>
      <a href="<?= BASE_URL ?>/jeu" class="btn-primary">Jouer encore</a>
      <a href="<?= BASE_URL ?>/profil" class="btn-secondary">Voir mon profil</a>
    <?php endif; ?>
  </div>

</section>