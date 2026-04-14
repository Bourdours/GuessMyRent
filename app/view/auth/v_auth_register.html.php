<section class="auth-page">
  <article class="auth-card">

    <!-- Logo -->
    <div class="auth-logo">
      <h1>GuessMy<span>Rent</span></h1>
      <p>Créez votre compte gratuitement</p>
    </div>

    <!-- Tab switcher -->
    <div class="tab-switcher" role="tablist">
      <a href="<?= BASE_URL ?>/auth" role="tab" aria-selected="false">Connexion</a>
      <a href="<?= BASE_URL ?>/auth?action=register" class="active" role="tab" aria-selected="true">Inscription</a>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-error mb-md" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/auth?action=register" class="auth-form" novalidate>
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

      <div class="form-group">
        <label class="form-label" for="pseudo">Pseudo</label>
        <input
          type="text"
          id="pseudo"
          name="pseudo"
          class="form-input"
          required
          maxlength="50"
          autocomplete="username"
          placeholder="Votre Pseudo">
      </div>

      <div class="form-group">
        <label class="form-label" for="email">Adresse e-mail</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-input"
          required
          maxlength="50"
          autocomplete="email"
          placeholder="exemple@mail.com">
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Mot de passe <small>(8 caractères min.)</small></label>
        <input
          type="password"
          id="pwdInput"
          name="password"
          class="form-input"
          required
          minlength="8"
          autocomplete="new-password"
          placeholder="••••••••">
        <div class="pwd-strength" aria-label="Force du mot de passe">
          <div class="pwd-bar" id="pwdBar1"></div>
          <div class="pwd-bar" id="pwdBar2"></div>
          <div class="pwd-bar" id="pwdBar3"></div>
        </div>
      </div>

      <button type="submit" class="btn-primary btn-full">Créer mon compte</button>
    </form>

    <p class="auth-legal mt-lg">
      En créant un compte, vous acceptez nos <a href="<?= BASE_URL ?>/info">CGU</a> et notre <a href="<?= BASE_URL ?>/info">politique de confidentialité</a>.
    </p>

  </article>
</section>