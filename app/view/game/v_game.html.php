<section class="game-page">
  <h1 class="visually-hidden">Page de jeu du site GuessMyRent</h1>

  <?php if (!$estate): ?>

    <div class="game-empty container">
      <div class="alert alert-info">
        Aucun bien disponible pour le moment. Revenez bientôt !
      </div>
      <p class="mt-lg">
        <a href="<?= BASE_URL ?>/" class="btn-secondary">Retour à l'accueil</a>
      </p>
    </div>

  <?php else: ?>

    <?php
    $images = array_values(array_filter([
      $estate['image1'] ?? null,
      $estate['image2'] ?? null,
      $estate['image3'] ?? null,
      $estate['image4'] ?? null,
    ]));
    ?>

    <div class="game-layout container">

      <!-- Colonne : galerie -->
      <figure class="game-col-photo">

        <div class="game-main-photo" onclick="openLightbox(document.getElementById('mainPhoto').src)">
          <img
            id="mainPhoto"
            src="<?= BASE_URL ?>/public/img/estates/<?= htmlspecialchars($images[0]) ?>"
            alt="Photo principale du bien">
          <span class="game-photo-zoom" aria-hidden="true"><i class="fa-solid fa-magnifying-glass-plus"></i></span>
        </div>

        <?php if (count($images) > 1): ?>
          <div class="game-thumbs">
            <?php foreach ($images as $i => $img): ?>
              <button
                class="game-thumb <?= $i === 0 ? 'active' : '' ?>"
                onclick="switchPhoto(this, '<?= BASE_URL ?>/public/img/estates/<?= htmlspecialchars($img) ?>')"
                aria-label="Photo <?= $i + 1 ?>">
                <img src="<?= BASE_URL ?>/public/img/estates/<?= htmlspecialchars($img) ?>" alt="Photo <?= $i + 1 ?>">
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </figure>

      <!-- Colonne : infos + devinette -->
      <div class="game-col-side">

        <!-- Infos du bien -->
        <article class="game-info">
          <h2 class="visually-hidden">Informations du bien</h2>
            <div class="game-info-header">
              <span class="pill pill-purple"><?= htmlspecialchars($estate['type_label']) ?></span>
              <span class="game-city"><?= htmlspecialchars($estate['city']) ?> (<?= htmlspecialchars(str_pad($estate['postcode'], 5, '0', STR_PAD_LEFT)) ?>)</span>
            </div>

            <ul class="game-specs">
              <li class="game-spec">
                <span class="game-spec-icon" aria-hidden="true"><i class="fa-solid fa-ruler-combined"></i></span>
                <span><?= htmlspecialchars($estate['square_meters']) ?> m²</span>
              </li>
              <?php if (!empty($estate['room'])): ?>
                <li class="game-spec">
                  <span class="game-spec-icon" aria-hidden="true"><i class="fa-solid fa-door-open"></i></span>
                  <span><?= (int)$estate['room'] ?> pièce<?= $estate['room'] > 1 ? 's' : '' ?></span>
                </li>
              <?php endif; ?>
              <?php if (!empty($estate['chamber'])): ?>
                <li class="game-spec">
                  <span class="game-spec-icon" aria-hidden="true"><i class="fa-solid fa-bed"></i></span>
                  <span><?= (int)$estate['chamber'] ?> chambre<?= $estate['chamber'] > 1 ? 's' : '' ?></span>
                </li>
              <?php endif; ?>
              <?php if (!empty($estate['floor']) && $estate['floor'] !== '0'): ?>
                <li class="game-spec">
                  <span class="game-spec-icon" aria-hidden="true"><i class="fa-solid fa-building"></i></span>
                  <span>Étage <?= htmlspecialchars($estate['floor']) ?></span>
                </li>
              <?php endif; ?>
              <?php if (!empty($estate['is_charges_included'])): ?>
                <li class="game-spec">
                  <span class="game-spec-icon" aria-hidden="true"><i class="fa-solid fa-circle-check"></i></span>
                  <span>Charges comprises</span>
                </li>
              <?php endif; ?>
            </ul>

            <?php if (!empty($estate['description'])): ?>
              <p class="game-description"><?= nl2br(htmlspecialchars($estate['description'])) ?></p>
            <?php endif; ?>
        </article>

        <!-- Zone de devinette -->
        <section class="game-guess-zone">
          <h2>Votre estimation</h2>
          <p class="game-guess-hint">Glissez le curseur où utiliser les boutons +/- pour proposer votre loyer mensuel.<span><br>
              Vous pouvez utiliser les flèches du clavier une fois un premier clique réalisé sur la barre.</span></p>

          <form method="POST" action="<?= BASE_URL ?>/jeu" class="game-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            <input type="hidden" name="estate_id" value="<?= (int)$estate['id_estate'] ?>">

            <div class="guess-display">
              <button type="button" class="guess-step-btn" id="guessMinus" aria-label="Diminuer de 10"><i class="fa-solid fa-minus"></i></button>
              <span id="guessValue"><?= $avgRent ?></span> €<span class="guess-unit">/mois</span>
              <button type="button" class="guess-step-btn" id="guessPlus" aria-label="Augmenter de 10"><i class="fa-solid fa-plus"></i></button>
            </div>

            <div class="guess-slider-wrap">
              <span class="slider-bound">300 €</span>
              <input
                type="range"
                id="guessRange"
                name="guess"
                min="300"
                max="5000"
                step="10"
                value="<?= $avgRent ?>"
                class="guess-slider"
                aria-label="Estimation du loyer mensuel">
              <span class="slider-bound">5 000 €</span>
            </div>

            <button type="submit" class="btn-primary btn-full game-submit">
              Valider mon estimation
            </button>
          </form>
        </section>

      </div>

    </div>

    <!-- Lightbox -->
    <div id="lightbox" class="lightbox" onclick="closeLightbox()" aria-hidden="true" aria-modal="true" role="dialog">
      <button class="lightbox-close" aria-label="Fermer"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
      <img id="lightboxImg" src="#" alt="Photo agrandie">
    </div>

  <?php endif; ?>

</section>