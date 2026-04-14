<section class="hero">
    <h1>GuessMy<span>Rent</span></h1>
    <p>Observez la fiche d'un bien immobilier et devinez son loyer mensuel. Saurez-vous estimer comme un expert ?</p>
    <div class="hero-actions">
        <a href="<?= BASE_URL ?>/jeu" class="btn-primary">Jouer maintenant</a>
        <a href="<?= BASE_URL ?>/regle" class="btn-secondary">Voir les règles</a>
    </div>
</section>

<section class="stats-band" aria-label="Statistiques du jeu">
    <dl class="stats-inner">
        <div>
            <dd class="stat-value"><?= number_format($stats['biens_disponibles'], 0, ',', ' ') ?></dd>
            <dt class="stat-label">Biens disponibles</dt>
        </div>
        <div>
            <dd class="stat-value"><?= number_format($stats['parties_jouees'], 0, ',', ' ') ?></dd>
            <dt class="stat-label">Parties jouées</dt>
        </div>
        <div>
            <dd class="stat-value"><?= $stats['moyenne_scores'] ?></dd>
            <dt class="stat-label">Moyenne des scores</dt>
        </div>
        <div>
            <dd class="stat-value"><?= $stats['villes_couvertes'] ?></dd>
            <dt class="stat-label">Villes couvertes</dt>
        </div>
    </dl>
</section>

<section class="how-to-play section">
    <h2>Comment jouer ?</h2>
    <div class="steps-grid">
        <article class="step-card">
            <span class="step-number" aria-hidden="true">1</span>
            <h3>Observez</h3>
            <p>Découvrez la fiche complète d'un bien : photos, surface, quartier, type…</p>
        </article>
        <article class="step-card">
            <span class="step-number" aria-hidden="true">2</span>
            <h3>Estimez</h3>
            <p>Utilisez le curseur pour proposer votre estimation du loyer mensuel.</p>
        </article>
        <article class="step-card">
            <span class="step-number" aria-hidden="true">3</span>
            <h3>Découvrez</h3>
            <p>Comparez votre estimation au loyer réel et recevez votre score.</p>
        </article>
        <article class="step-card">
            <span class="step-number" aria-hidden="true">4</span>
            <h3>Progressez</h3>
            <p>Sauvegardez vos scores, suivez votre progression et montez en niveau.</p>
        </article>
    </div>
</section>