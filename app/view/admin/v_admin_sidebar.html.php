<?php
$_base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$_uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($_base !== '' && str_starts_with($_uri, $_base)) {
  $_uri = substr($_uri, strlen($_base));
}
$_current = rtrim($_uri, '/') ?: '/';
$_active  = fn(string $path): string => $_current === $path ? ' class="active"' : '';
?>

<!-- Sidebar -->
<aside class="admin-sidebar">
  <nav class="admin-sidebar-section" aria-label="Menu Admin">
    <a href="<?= BASE_URL ?>/admin" <?= $_active('/admin') ?>>Dashboard</a>
  </nav>
  <nav class="admin-sidebar-section" aria-label="Contenu">
    <p class="section-label">Contenu</p>
    <a href="<?= BASE_URL ?>/admin/biens" <?= $_active('/admin/biens') ?>>Biens</a>
    <a href="<?= BASE_URL ?>/admin/biens/en-attente" <?= $_active('/admin/biens/en-attente') ?>>Biens déposés</a>
    <a href="<?= BASE_URL ?>/admin/api" <?= $_active('/admin/api') ?>>Biens de l'api</a>
  </nav>
  <nav class="admin-sidebar-section" aria-label="Jeu">
    <p class="section-label">Jeu</p>
    <a href="<?= BASE_URL ?>/admin/parties" <?= $_active('/admin/parties') ?>>Parties</a>
  </nav>
  <nav class="admin-sidebar-section" aria-label="Utilisateurs">
    <p class="section-label">Utilisateurs</p>
    <a href="<?= BASE_URL ?>/admin/utilisateurs" <?= $_active('/admin/utilisateurs') ?>>Utilisateurs</a>
  </nav>
  <nav class="admin-sidebar-section" arira-label="Messagerie">
    <p class="section-label">Messagerie</p>
    <a href="<?= BASE_URL ?>/admin/messagerie" <?= $_active('/admin/message') ?>>Messages</a>
  </nav>
  <nav class="admin-sidebar-section" aria-label="Navigation retour">
    <p class="section-label">Navigation</p>
    <a href="<?= BASE_URL ?>/">← Retour au site</a>
  </nav>
</aside>