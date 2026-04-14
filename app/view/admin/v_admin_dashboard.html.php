<div class="admin-layout">

  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <div class="admin-sidebar-section">
      <a href="<?= BASE_URL ?>/admin" class="active">Dashboard</a>
      <div class="section-label">Contenu</div>
      <a href="<?= BASE_URL ?>/admin/biens">Biens</a>
      <a href="<?= BASE_URL ?>/admin/api">Biens de l'api</a>
    </div>
    <div class="admin-sidebar-section">
      <div class="section-label">Utilisateurs</div>
      <a href="<?= BASE_URL ?>/admin/utilisateurs">Utilisateurs</a>
    </div>
    <div class="admin-sidebar-section">
      <div class="section-label">Navigation</div>
      <a href="<?= BASE_URL ?>/">← Retour au site</a>
    </div>
  </aside>

  <!-- Main -->
  <div class="admin-main">

    <div class="admin-header-bar">
      <h2>Dashboard</h2>
      <span class="pill pill-purple">Admin</span>
    </div>

    <!-- KPIs -->
    <div class="kpi-grid">
      <div class="kpi-card">
        <div class="kpi-label">Biens actifs</div>
        <div class="kpi-value kpi-purple"><?= (int)($stats['active_estates'] ?? 0) ?></div>
      </div>
      <div class="kpi-card">
        <div class="kpi-label">Biens en attente</div>
        <div class="kpi-value kpi-amber"><?= (int)($stats['pending_estates'] ?? 0) ?></div>
      </div>
      <div class="kpi-card">
        <div class="kpi-label">Utilisateurs</div>
        <div class="kpi-value"><?= (int)($stats['total_users'] ?? 0) ?></div>
      </div>
      <div class="kpi-card">
        <div class="kpi-label">Parties jouées</div>
        <div class="kpi-value kpi-green"><?= (int)($stats['total_games'] ?? 0) ?></div>
      </div>
    </div>

    <!-- Quick actions -->
    <div class="admin-table-wrap">
      <div class="admin-table-header">
        <h3>Accès rapides</h3>
      </div>
      <div class="admin-table-btn-group">
        <a href="<?= BASE_URL ?>/admin/biens" class="btn-primary">Gérer les biens</a>
        <a href="<?= BASE_URL ?>/admin/utilisateurs" class="btn-secondary">Gérer les utilisateurs</a>
        <a href="<?= BASE_URL ?>/admin/api" class="btn-secondary">Ajouter des biens depuis l'api</a>
      </div>
    </div>

  </div>
</div>
