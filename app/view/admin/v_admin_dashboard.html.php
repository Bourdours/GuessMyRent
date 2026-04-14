<div class="admin-layout">

  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <nav class="admin-sidebar-section" aria-label="Menu Admin">
      <a href="<?= BASE_URL ?>/admin" class="active">Dashboard</a>
    </nav>
    <nav class="admin-sidebar-section" aria-label="Contenu">
      <p class="section-label">Contenu</p>
      <a href="<?= BASE_URL ?>/admin/biens">Biens</a>
      <a href="<?= BASE_URL ?>/admin/api">Biens de l'api</a>
    </nav>
    <nav class="admin-sidebar-section" aria-label="Utilisateurs">
      <p class="section-label">Utilisateurs</p>
      <a href="<?= BASE_URL ?>/admin/utilisateurs">Utilisateurs</a>
    </nav>
    <nav class="admin-sidebar-section" aria-label="Navigation retour">
      <p class="section-label">Navigation</p>
      <a href="<?= BASE_URL ?>/">← Retour au site</a>
    </nav>
  </aside>

  <!-- Main -->
  <section class="admin-main">

    <header class="admin-header-bar">
      <h1>Dashboard</h1>
      <span class="pill pill-purple">Admin</span>
    </header>

    <!-- KPIs -->
    <section class="kpi-grid" aria-label="Indicateurs clés">
      <article class="kpi-card">
        <dl>
          <dt class="kpi-label">Biens actifs</dt>
          <dd class="kpi-value kpi-purple"><?= (int)($stats['active_estates'] ?? 0) ?></dd>
        </dl>
      </article>
      <article class="kpi-card">
        <dl>
          <dt class="kpi-label">Biens en attente</dt>
          <dd class="kpi-value kpi-amber"><?= (int)($stats['pending_estates'] ?? 0) ?></dd>
        </dl>
      </article>
      <article class="kpi-card">
        <dl>
          <dt class="kpi-label">Utilisateurs</dt>
          <dd class="kpi-value"><?= (int)($stats['total_users'] ?? 0) ?></dd>
        </dl>
      </article>
      <article class="kpi-card">
        <dl>
          <dt class="kpi-label">Parties jouées</dt>
          <dd class="kpi-value kpi-green"><?= (int)($stats['total_games'] ?? 0) ?></dd>
        </dl>
      </article>
    </section>

    <!-- Quick actions -->
    <section class="admin-table-wrap">
      <header class="admin-table-header">
        <h2>Accès rapides</h2>
      </header>
      <nav class="admin-table-btn-group" aria-label="Accès rapides">
        <a href="<?= BASE_URL ?>/admin/biens" class="btn-primary">Gérer les biens</a>
        <a href="<?= BASE_URL ?>/admin/utilisateurs" class="btn-secondary">Gérer les utilisateurs</a>
        <a href="<?= BASE_URL ?>/admin/api" class="btn-secondary">Ajouter des biens depuis l'api</a>
      </nav>
    </section>

  </section>
</div>