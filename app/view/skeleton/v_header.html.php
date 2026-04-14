<!DOCTYPE html>
<html lang="fr">
<?php require_once V_SKELETON . "v_head.html.php" ?>

<body>

  <header class="main-header">
    <div class="header-inner">
      <a href="<?= BASE_URL ?>/" class="header-logo" aria-label="GuessMy Rent — Accueil">GuessMy<span aria-hidden="true">Rent</span></a>

      <!-- Mobile burger -->
      <button class="burger" id="burgerBtn" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="navDrawer">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
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