<div class="admin-layout">

  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <nav class="admin-sidebar-section" aria-label="Menu Admin">
      <a href="<?= BASE_URL ?>/admin">Dashboard</a>
    </nav>
    <nav class="admin-sidebar-section" aria-label="Contenu">
      <p class="section-label">Contenu</p>
      <a href="<?= BASE_URL ?>/admin/biens">Biens</a>
      <a href="<?= BASE_URL ?>/admin/api">Biens de l'api</a>
    </nav>
    <nav class="admin-sidebar-section" aria-label="Utilisateurs">
      <p class="section-label">Utilisateurs</p>
      <a href="<?= BASE_URL ?>/admin/utilisateurs" class="active">Utilisateurs</a>
    </nav>
    <nav class="admin-sidebar-section" aria-label="Navigation retour">
      <p class="section-label">Navigation</p>
      <a href="<?= BASE_URL ?>/">← Retour au site</a>
    </nav>
  </aside>

  <!-- Main -->
  <section class="admin-main">

    <header class="admin-header-bar">
      <h1>Gestion des utilisateurs</h1>
    </header>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success mb-md" role="status"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert-error mb-md" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <section class="admin-table-wrap">
      <header class="admin-table-header">
        <h2>Tous les utilisateurs <span class="pill pill-purple"><?= count($users ?? []) ?></span></h2>
      </header>

      <?php if (empty($users)): ?>
        <p class="admin-empty-state">Aucun utilisateur enregistré.</p>
      <?php else: ?>
        <div class="table-overflow">
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $u): ?>
                <tr>
                  <td><?= (int)$u['id_user'] ?></td>
                  <td><?= htmlspecialchars($u['pseudo']) ?></td>
                  <td><?= htmlspecialchars($u['email']) ?></td>
                  <td>
                    <?php if ($u['is_admin']): ?>
                      <span class="pill pill-gold">Admin</span>
                    <?php else: ?>
                      <span class="pill pill-purple">Joueur</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ((int)$u['id_user'] !== (int)$_SESSION['user_id']): ?>
                      <form method="POST" action="<?= BASE_URL ?>/admin/utilisateurs" class="form-inline"
                        onsubmit="return confirm('Supprimer définitivement cet utilisateur ?')">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <input type="hidden" name="user_id" value="<?= (int)$u['id_user'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn-danger">Supprimer</button>
                      </form>
                    <?php else: ?>
                      <em class="admin-self-label">Vous</em>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>

  </section>
</div>