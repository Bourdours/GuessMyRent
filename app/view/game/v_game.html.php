<div class="game-page">

  <?php if (!$estate): ?>

    <div class="game-empty container">
      <div class="alert alert-info">
        Aucun bien disponible pour le moment. Revenez bientôt !
      </div>
      <div class="mt-lg">
        <a href="<?= BASE_URL ?>/" class="btn-secondary">Retour à l'accueil</a>
      </div>
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
      <div class="game-col-photo">

        <div class="game-main-photo" onclick="openLightbox(document.getElementById('mainPhoto').src)">
          <img
            id="mainPhoto"
            src="<?= BASE_URL ?>/public/img/estates/<?= htmlspecialchars($images[0]) ?>"
            alt="Photo principale du bien">
          <span class="game-photo-zoom"><i class="fa-solid fa-magnifying-glass-plus"></i></span>
        </div>

        <?php if (count($images) > 1): ?>
          <div class="game-thumbs">
            <?php foreach ($images as $i => $img): ?>
              <button
                .
                class="game-thumb <?= $i === 0 ? 'active' : '' ?>"
                onclick="switchPhoto(this, '<?= BASE_URL ?>/public/img/estates/<?= htmlspecialchars($img) ?>')"
                aria-label="Photo <?= $i + 1 ?>">
                <img src="<?= BASE_URL ?>/public/img/estates/<?= htmlspecialchars($img) ?>" alt="Photo <?= $i + 1 ?>">
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>

      <!-- Colonne : infos + devinette -->
      <div class="game-col-side">

        <!-- Infos du bien -->
        <div class="game-info">
          <div class="game-info-header">
            <span class="pill pill-purple"><?= htmlspecialchars($estate['type_label']) ?></span>
            <span class="game-city"><?= htmlspecialchars($estate['city']) ?> (<?= htmlspecialchars(str_pad($estate['postcode'], 5, '0', STR_PAD_LEFT)) ?>)</span>
          </div>

          <div class="game-specs">
            <div class="game-spec">
              <span class="game-spec-icon"><i class="fa-solid fa-ruler-combined"></i></span>
              <span><?= htmlspecialchars($estate['square_meters']) ?> m²</span>
            </div>
            <?php if (!empty($estate['room'])): ?>
              <div class="game-spec">
                <span class="game-spec-icon"><i class="fa-solid fa-door-open"></i></span>
                <span><?= (int)$estate['room'] ?> pièce<?= $estate['room'] > 1 ? 's' : '' ?></span>
              </div>
            <?php endif; ?>
            <?php if (!empty($estate['chamber'])): ?>
              <div class="game-spec">
                <span class="game-spec-icon"><i class="fa-solid fa-bed"></i></span>
                <span><?= (int)$estate['chamber'] ?> chambre<?= $estate['chamber'] > 1 ? 's' : '' ?></span>
              </div>
            <?php endif; ?>
            <?php if (!empty($estate['floor']) && $estate['floor'] !== '0'): ?>
              <div class="game-spec">
                <span class="game-spec-icon"><i class="fa-solid fa-building"></i></span>
                <span>Étage <?= htmlspecialchars($estate['floor']) ?></span>
              </div>
            <?php endif; ?>
            <?php if (!empty($estate['is_charges_included'])): ?>
              <div class="game-spec">
                <span class="game-spec-icon"><i class="fa-solid fa-circle-check"></i></span>
                <span>Charges comprises</span>
              </div>
            <?php endif; ?>
          </div>

          <?php if (!empty($estate['description'])): ?>
            <div class="game-description">
              <p><?= nl2br(htmlspecialchars($estate['description'])) ?></p>
            </div>
          <?php endif; ?>
        </div>

        <!-- Zone de devinette -->
        <div class="game-guess-zone">
          <h2>Votre estimation</h2>
          <p class="game-guess-hint">Glissez le curseur pour proposer votre loyer mensuel.</p>

          <form method="POST" action="<?= BASE_URL ?>/jeu" class="game-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            <input type="hidden" name="estate_id" value="<?= (int)$estate['id_estate'] ?>">

            <div class="guess-display">
              <span id="guessValue">800</span> €<span class="guess-unit">/mois</span>
            </div>

            <div class="guess-slider-wrap">
              <span class="slider-bound">300 €</span>
              <input
                type="range"
                id="guessRange"
                name="guess"
                min="300"
                max="2500"
                step="10"
                value="800"
                class="guess-slider"
                aria-label="Estimation du loyer mensuel">
              <span class="slider-bound">2 500 €</span>
            </div>

            <button type="submit" class="btn-primary btn-full game-submit">
              Valider mon estimation
            </button>
          </form>
        </div>

      </div>

    </div>

    <!-- Lightbox -->
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
      <button class="lightbox-close" aria-label="Fermer"><i class="fa-solid fa-xmark"></i></button>
      <img id="lightboxImg" src="" alt="Photo agrandie">
    </div>

  <?php endif; ?>

</div>

