<section class="auth-page">
  <h2 class="visually-hidden">Page d'authentification</h2>
  <article class="auth-card">

    <!-- Logo -->
    <div class="auth-logo">
      <h1>GuessMy<span>Rent</span></h1>
      <p>Le jeu d'estimation de loyers</p>
    </div>

    <!-- Tab switcher -->
    <div class="tab-switcher">
      <a href="<?= BASE_URL ?>/auth" class="active">Connexion</a>
      <a href="<?= BASE_URL ?>/auth?action=register">Inscription</a>
    </div>

    <?php require V_SKELETON . 'v_alerts.html.php'; ?>

    <form method="POST" action="<?= BASE_URL ?>/auth" class="auth-form" novalidate>
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

      <div class="form-group">
        <label class="form-label" for="email">Adresse e-mail</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-input"
          required
          autocomplete="email"
          placeholder="exemple@mail.com">
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Mot de passe</label>
        <input
          type="password"
          id="password"
          name="password"
          class="form-input"
          required
          autocomplete="current-password"
          placeholder="••••••••">
      </div>

      <p class="text-center">
        <a href="<?= BASE_URL ?>/auth?action=reset" class="auth-back-link">Mot de passe oublié ?</a>
      </p>

      <button type="submit" class="btn-primary btn-full">Se connecter</button>
    </form>

    <p class="auth-legal mt-lg">
      En vous connectant, vous acceptez nos <a href="<?= BASE_URL ?>/info">CGU</a> et notre <a href="<?= BASE_URL ?>/info">politique de confidentialité</a>.
    </p>

  </article>
</section>