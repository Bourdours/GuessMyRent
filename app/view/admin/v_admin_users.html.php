<div class="admin-layout">

  <?php require __DIR__ . '/v_admin_sidebar.html.php'; ?>

  <!-- Main -->
  <section class="admin-main">

    <header class="admin-header-bar">
      <h1>Gestion des utilisateurs</h1>
    </header>

    <?php require V_SKELETON . 'v_alerts.html.php'; ?>

    <!-- Étape 1 : sélectionner un utilisateur -->
    <section class="admin-table-wrap">
      <header class="admin-table-header">
        <h2>Étape 1 — Sélectionner un utilisateur <span class="pill pill-purple"><?= count($users ?? []) ?></span></h2>
      </header>

      <?php if (empty($users)): ?>
        <p class="admin-empty-state">Aucun utilisateur enregistré.</p>
      <?php else: ?>
        <div class="table-overflow">
          <table class="admin-table user-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Action rapide</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $u): ?>
                <tr data-id="<?= (int)$u['id_user'] ?>">
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
                  <td class="td-actions">
                    <?php if ((int)$u['id_user'] !== (int)$_SESSION['user_id']): ?>
                      <button type="button" class="btn-secondary btn-sm"
                              onclick="selectUser(<?= (int)$u['id_user'] ?>)">Correction</button>
                      <form method="POST" action="<?= BASE_URL ?>/admin/utilisateurs" class="form-inline"
                            onsubmit="return confirm('Supprimer définitivement cet utilisateur ?')">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <input type="hidden" name="user_id" value="<?= (int)$u['id_user'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn-danger btn-sm">Supprimer</button>
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

    <!-- Étape 2 : modifier l'utilisateur -->
    <section class="admin-table-wrap" id="userEdit" style="display:none;">
      <header class="admin-table-header">
        <h2>Étape 2 — Modifier l'utilisateur</h2>
      </header>

      <form method="POST" action="<?= BASE_URL ?>/admin/utilisateurs">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="user_id" id="uedit-user-id" value="">

        <div class="edit-grid">
          <div class="edit-field">
            <label for="uedit-pseudo">Pseudo</label>
            <input type="text" id="uedit-pseudo" name="pseudo" class="form-input" required>
          </div>
          <div class="edit-field">
            <label for="uedit-email">Email</label>
            <input type="email" id="uedit-email" name="email" class="form-input" required>
          </div>
          <div class="edit-field">
            <label for="uedit-avatar">Avatar <small>(nom de fichier)</small></label>
            <input type="text" id="uedit-avatar" name="avatar" class="form-input" placeholder="ex: avatar.png">
          </div>
          <div class="edit-field">
            <label for="uedit-role">Rôle</label>
            <select id="uedit-role" name="is_admin" class="form-select">
              <option value="0">Joueur</option>
              <option value="1">Admin</option>
            </select>
          </div>
          <div class="edit-field">
            <label for="uedit-password">Nouveau mot de passe <small>(laisser vide = inchangé)</small></label>
            <input type="password" id="uedit-password" name="new_password" class="form-input" autocomplete="new-password">
          </div>
        </div>

        <div class="depose-actions">
          <button type="submit" class="btn-primary">Enregistrer les modifications</button>
          <button type="button" class="btn-secondary" onclick="closeUserEditForm()">Annuler</button>
        </div>
      </form>
    </section>

  </section>
</div>

<div id="admin-data" hidden
  data-page="user-list"
  data-users="<?= htmlspecialchars(json_encode(array_values($users ?? []), JSON_HEX_TAG)) ?>"
></div>
