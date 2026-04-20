<section class="error-page" aria-labelledby="error-title">
  <p class="error-code" aria-hidden="true">403</p>
  <h1 id="error-title">Accès refusé</h1>
  <p>
    <?php if (!empty($message)): ?>
      <?= htmlspecialchars($message) ?>
    <?php else: ?>
      Votre requête n'a pas pu être vérifiée. Veuillez réessayer.
    <?php endif; ?>
  </p>
  <a href="<?= BASE_URL ?>/" class="btn-primary">Retour à l'accueil</a>
</section>
