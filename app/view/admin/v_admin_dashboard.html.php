<div class="admin-layout">

  <?php require __DIR__ . '/v_admin_sidebar.html.php'; ?>

  <!-- Main content -->
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
          <dd class="kpi-value kpi-gold"><?= (int)($stats['pending_estates'] ?? 0) ?></dd>
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
        <a href="<?= BASE_URL ?>/admin/biens/en-attente" class="btn-primary">Gérer les biens en attente</a>
        <a href="<?= BASE_URL ?>/admin/api" class="btn-secondary">Ajouter des biens depuis l'api</a>
        <a href="<?= BASE_URL ?>/admin/biens" class="btn-secondary">Gérer les biens</a>
        <a href="<?= BASE_URL ?>/admin/utilisateurs" class="btn-secondary">Gérer les utilisateurs</a>
        <a href="<?= BASE_URL ?>/admin/parties" class="btn-secondary">Gérer les parties</a>
        <a href="<?= BASE_URL ?>/admin/messagerie" class="btn-secondary">Gérer la messagerie</a>
      </nav>
    </section>

  </section>
</div>