<section class="error-page" aria-labelledby="error-title">
  <p class="error-code" aria-hidden="true">500</p>
  <h1 id="error-title">Erreur serveur</h1>
  <p>
    <?php if (!empty($message)): ?>
      <?= htmlspecialchars($message) ?>
    <?php else: ?>
      Une erreur inattendue s'est produite. Veuillez réessayer plus tard.
    <?php endif; ?>
  </p>
  <a href="<?= BASE_URL ?>/" class="btn-primary">Retour à l'accueil</a>
</section>
