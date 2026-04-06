<div class="admin-layout">

  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <div class="admin-sidebar-section">
      <div class="section-label">Contenu</div>
      <a href="<?= BASE_URL ?>/admin">Dashboard</a>
      <a href="<?= BASE_URL ?>/admin/biens">Biens</a>
    </div>
    <div class="admin-sidebar-section">
      <div class="section-label">Utilisateurs</div>
      <a href="<?= BASE_URL ?>/admin/utilisateurs" class="active">Utilisateurs</a>
    </div>
    <div class="admin-sidebar-section">
      <div class="section-label">Navigation</div>
      <a href="<?= BASE_URL ?>/">← Retour au site</a>
    </div>
  </aside>

  <!-- Main -->
  <div class="admin-main">

    <div class="admin-header-bar">
      <h2>Gestion des utilisateurs</h2>
    </div>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success mb-md" role="status"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert-error mb-md" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="admin-table-wrap">
      <div class="admin-table-header">
        <h3>Tous les utilisateurs <span class="pill pill-purple"><?= count($users ?? []) ?></span></h3>
      </div>

      <?php if (empty($users)): ?>
        <div class="admin-empty-state">
          Aucun utilisateur enregistré.
        </div>
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
                        <input type="hidden" name="user_id"   value="<?= (int)$u['id_user'] ?>">
                        <input type="hidden" name="action"    value="delete">
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
    </div>

  </div>
</div>
