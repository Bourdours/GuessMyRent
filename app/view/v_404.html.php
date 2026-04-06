<div class="error-page">
  <div class="error-code">404</div>
  <h2>Page introuvable</h2>
  <p>
    <?php if (!empty($message)): ?>
      <?= htmlspecialchars($message) ?>
    <?php else: ?>
      La page que vous cherchez n'existe pas ou n'est plus disponible.
    <?php endif; ?>
  </p>
  <a href="<?= BASE_URL ?>/" class="btn-primary">Retour à l'accueil</a>
</div>
