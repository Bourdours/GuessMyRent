<!DOCTYPE html>
<html lang="fr">
<?php require_once V_SKELETON . "v_head.html.php" ?>

<button class="pill-green" id="play"><i class="fa-solid fa-music"></i></button>
<button class="pill-red" id="stop"><i class="fa-solid fa-volume-xmark"></i></button>

<body>

  <header>
    <div class="header-inner">
      <a href="<?= BASE_URL ?>/" class="header-logo">GuessMy<span>Rent</span></a>

      <!-- Mobile burger -->
      <button class="burger" id="burgerBtn" aria-label="Menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <!-- Desktop nav -->
      <nav class="nav-desktop" aria-label="Navigation principale">
        <a href="<?= BASE_URL ?>/">Accueil</a>
        <a href="<?= BASE_URL ?>/jeu">Jouer</a>
        <a href="<?= BASE_URL ?>/regle">Règles</a>
        <a href="<?= BASE_URL ?>/contact">Contact</a>
        <?php if (!empty($_SESSION['user_id'])): ?>
          <a href="<?= BASE_URL ?>/profil">Mon profil</a>
          <?php if (!empty($_SESSION['is_admin'])): ?>
            <a href="<?= BASE_URL ?>/admin" class="nav-admin-badge">Admin</a>
          <?php endif; ?>
          <a href="<?= BASE_URL ?>/auth?action=logout">Déconnexion</a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>/auth">Connexion</a>
          <a href="<?= BASE_URL ?>/auth?action=register" class="btn-nav-cta">S'inscrire</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- Mobile nav drawer -->
  <nav class="nav-drawer" id="navDrawer" aria-label="Navigation mobile">
    <a href="<?= BASE_URL ?>/">Accueil</a>
    <a href="<?= BASE_URL ?>/jeu">Jouer</a>
    <a href="<?= BASE_URL ?>/regle">Règles</a>
    <a href="<?= BASE_URL ?>/contact">Contact</a>
    <?php if (!empty($_SESSION['user_id'])): ?>
      <a href="<?= BASE_URL ?>/profil">Mon profil</a>
      <?php if (!empty($_SESSION['is_admin'])): ?>
        <a href="<?= BASE_URL ?>/admin">Admin</a>
      <?php endif; ?>
      <a href="<?= BASE_URL ?>/auth?action=logout" class="nav-logout">Déconnexion</a>
    <?php else: ?>
      <a href="<?= BASE_URL ?>/auth">Connexion</a>
      <a href="<?= BASE_URL ?>/auth?action=register">S'inscrire</a>
    <?php endif; ?>
  </nav>

  <main>