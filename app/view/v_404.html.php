<section class="error-page" aria-labelledby="error-title">
  <p class="error-code" aria-hidden="true">404</p>
  <h1 id="error-title">Page introuvable</h1>
  <p>
    <?php if (!empty($message)): ?>
      <?= htmlspecialchars($message) ?>
    <?php else: ?>
      La page que vous cherchez n'existe pas ou n'est plus disponible.
    <?php endif; ?>
  </p>
  <a href="<?= BASE_URL ?>/" class="btn-primary">Retour à l'accueil</a>
</section>