<div class="rules-page">

  <div class="rules-hero">
    <h1>Règles du jeu</h1>
    <p>Estimez le loyer de biens immobiliers réels et marquez un maximum de points !</p>
  </div>

  <!-- How to play -->
  <section class="section">
    <h2>Comment jouer ?</h2>
    <p>
      Observez la fiche complète d'un bien immobilier (photos, surface, localisation, type de logement)
      puis utilisez le curseur pour proposer votre estimation du loyer mensuel.
      Après validation, vous découvrez le loyer réel et votre score.
    </p>

    <div class="steps-grid">
      <div class="step-card">
        <div class="step-number">1</div>
        <h3>Observez</h3>
        <p>Analysez les photos, la surface, le quartier et les caractéristiques du bien.</p>
      </div>
      <div class="step-card">
        <div class="step-number">2</div>
        <h3>Estimez</h3>
        <p>Glissez le curseur jusqu'à votre estimation (entre 300 € et 5 000 €).</p>
      </div>
      <div class="step-card">
        <div class="step-number">3</div>
        <h3>Validez</h3>
        <p>Confirmez votre estimation et découvrez le loyer réel du bien.</p>
      </div>
      <div class="step-card">
        <div class="step-number">4</div>
        <h3>Scorez</h3>
        <p>Plus vous êtes précis, plus vous gagnez de points !</p>
      </div>
    </div>
  </section>

  <!-- Score table -->
  <section class="section">
    <h2>Calcul du score</h2>
    <table class="score-table">
      <thead>
        <tr>
          <th>Écart avec le loyer réel</th>
          <th>Points gagnés</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="pill pill-green">Moins de 5 %</span></td>
          <td class="pts">100 pts</td>
        </tr>
        <tr>
          <td><span class="pill pill-gold">5 % à 10 %</span></td>
          <td class="pts">80 pts</td>
        </tr>
        <tr>
          <td><span class="pill pill-amber">10 % à 20 %</span></td>
          <td class="pts">50 pts</td>
        </tr>
        <tr>
          <td><span class="pill pill-red">Plus de 20 %</span></td>
          <td class="pts">20 pts</td>
        </tr>
      </tbody>
    </table>
  </section>

  <div class="text-center">
    <a href="<?= BASE_URL ?>/jeu" class="btn-primary">Jouer maintenant</a>
  </div>

</div>
