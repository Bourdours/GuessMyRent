</main>

<!-- Bouton musique flottant (global) -->
<audio id="bgMusic" loop>
  <source src="<?= BASE_URL ?>/public/audio/boucle25.ogg" type="audio/ogg">
  <source src="<?= BASE_URL ?>/public/audio/boucle25.mp3" type="audio/mpeg">
</audio>
<button id="musicBtn" class="music-btn music-btn--off" aria-label="Activer la musique">
  <i class="fa-solid fa-music"></i>
</button>

<footer>
    <p>
        &copy; <?= date('Y') ?> <strong>GuessMyRent</strong> &nbsp;·&nbsp;
        <a href="<?= BASE_URL ?>/info">Mentions légales</a> &nbsp;·&nbsp;
        <a href="<?= BASE_URL ?>/contact">Contact</a>
    </p>
</footer>

<script src="<?= BASE_URL ?>/public/js/main.js"></script>
<?php if (!empty($pageScript)): ?>
<script src="<?= htmlspecialchars($pageScript) ?>" defer></script>
<?php endif; ?>

</body>

</html>