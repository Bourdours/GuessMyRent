<div class="rules-page">

  <div class="rules-hero">
    <h1>Règles du jeu</h1>
    <p>Estimez le loyer de biens immobiliers réels et marquez un maximum de points !</p>
  </div>

  <section aria-label="Présentation vidéo">
    <video
      class="video"
      autoplay
      controls
      loop
      muted
      poster="<?= BASE_URL ?>/public/img/poster.png"
      preload="auto">
      <source src="<?= BASE_URL ?>/public/vid/Renting_Real_Estate_1280x720.mp4" type="video/mp4" />
      <source src="<?= BASE_URL ?>/public/vid/Renting_Real_Estate_1280x720.webm" type="video/webm" />
      <source src="<?= BASE_URL ?>/public/vid/Renting_Real_Estate_1920x1080.mp4" type="video/mp4" />
      <source src="<?= BASE_URL ?>/public/vid/Renting_Real_Estate_1920x1080.webm" type="video/webm" />
      <p>
        Votre navigateur ne supporte pas le format vidéo présent sur ce site.
      </p>
    </video>
  </section>
  <!-- How to play -->
  <section>
    <h2>Comment jouer ?</h2>
    <p>
      Observez la fiche complète d'un bien immobilier (photos, surface, localisation, type de logement)
      puis utilisez le curseur pour proposer votre estimation du loyer mensuel.
      Après validation, vous découvrez le loyer réel et votre score.
    </p>

    <section class="steps-grid">
      <h2 class="visually-hidden">Étapes du jeu</h2>
      <article class="step-card">
        <span class="step-number" aria-hidden="true">1</span>
        <h3>Observez</h3>
        <p>Analysez les photos, la surface, le quartier et les caractéristiques du bien.</p>
      </article>
      <article class="step-card">
        <span class="step-number" aria-hidden="true">2</span>
        <h3>Estimez</h3>
        <p>Glissez le curseur jusqu'à votre estimation (entre 300 € et 5 000 €).</p>
      </article>
      <article class="step-card">
        <span class="step-number" aria-hidden="true">3</span>
        <h3>Validez</h3>
        <p>Confirmez votre estimation et découvrez le loyer réel du bien.</p>
      </article>
      <article class="step-card">
        <span class="step-number" aria-hidden="true">4</span>
        <h3>Scorez</h3>
        <p>Plus vous êtes précis, plus vous gagnez de points !</p>
      </article>
    </section>

  </section>

  <!-- Score table -->
  <section>
    <h2>Calcul du score</h2>
    <p>Le score est calculé en soustrayant votre écart en % au loyer réel de 100 points. Plus vous êtes précis, plus vous marquez de points !</p>
    <p class="score-formula"><strong>Score = 100 − écart %</strong> &nbsp;(minimum 0)</p>
    <table class="score-table">
      <thead>
        <tr>
          <th>Écart avec le loyer réel</th>
          <th>Points gagnés</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="pill pill-green">0 %</span></td>
          <td class="pts">100 pts</td>
        </tr>
        <tr>
          <td><span class="pill pill-green">5 %</span></td>
          <td class="pts">95 pts</td>
        </tr>
        <tr>
          <td><span class="pill pill-gold">10 %</span></td>
          <td class="pts">90 pts</td>
        </tr>
        <tr>
          <td><span class="pill pill-gold">20 %</span></td>
          <td class="pts">80 pts</td>
        </tr>
        <tr>
          <td><span class="pill pill-amber">50 %</span></td>
          <td class="pts">50 pts</td>
        </tr>
        <tr>
          <td><span class="pill pill-red">100 % et plus</span></td>
          <td class="pts">0 pt</td>
        </tr>
      </tbody>
    </table>
  </section>

  <p class="text-center">
    <a href="<?= BASE_URL ?>/jeu" class="btn-primary">Jouer maintenant</a>
  </p>

</div>