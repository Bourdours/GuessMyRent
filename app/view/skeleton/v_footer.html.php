</main>

<!-- Floating music button (global) -->
<audio id="bgMusic" loop>
  <source src="<?= BASE_URL ?>/public/audio/boucle25.ogg" type="audio/ogg">
  <source src="<?= BASE_URL ?>/public/audio/boucle25.mp3" type="audio/mpeg">
</audio>
<button id="musicBtn" class="music-btn music-btn--off" aria-label="Activer la musique">
  <i class="fa-solid fa-music" aria-hidden="true"></i>
</button>

<footer>
  <p>&copy; <?= date('Y') ?> <strong>GuessMyRent</strong></p>
  <nav aria-label="Liens légaux">
    <a href="<?= BASE_URL ?>/info">Mentions légales</a>
    <a href="<?= BASE_URL ?>/contact">Contact</a>
  </nav>
</footer>

<!-- Cookie banner -->
<div id="cookieBanner" class="cookie-banner" role="dialog" aria-modal="false" aria-label="Gestion des cookies" hidden>
  <p class="cookie-banner__text">
    Ce site utilise uniquement des cookies de session nécessaires à son fonctionnement (authentification). Aucun cookie publicitaire ou de tracking n'est utilisé.
    <a href="<?= BASE_URL ?>/info#cookies">En savoir plus</a>
  </p>
  <button id="cookieAccept" class="cookie-banner__btn">J'ai compris</button>
</div>

<script src="<?= BASE_URL ?>/public/js/main.js"></script>
<?php if (!empty($pageScript)): ?>
  <script src="<?= htmlspecialchars($pageScript) ?>" defer></script>
<?php endif; ?>

</body>

</html>