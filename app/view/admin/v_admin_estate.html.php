<div class="admin-layout">

  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <nav class="admin-sidebar-section" aria-label="Menu Admin">
      <a href="<?= BASE_URL ?>/admin">Dashboard</a>
    </nav>
    <nav class="admin-sidebar-section" aria-label="Contenu">
      <p class="section-label">Contenu</p>
      <a href="<?= BASE_URL ?>/admin/biens" class="active">Biens</a>
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
      <h1>Gestion des biens</h1>
    </header>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success mb-md" role="status"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert-error mb-md" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <section class="admin-table-wrap">
      <header class="admin-table-header">
        <h2>Tous les biens <span class="pill pill-purple"><?= count($estates ?? []) ?></span></h2>
      </header>

      <?php if (empty($estates)): ?>
        <p class="admin-empty-state">Aucun bien enregistré.</p>
      <?php else: ?>
        <div class="table-overflow">
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Ville</th>
                <th>Surface</th>
                <th>Loyer</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($estates as $e): ?>
                <tr>
                  <td><?= (int)$e['id_estate'] ?></td>
                  <td><?= htmlspecialchars($e['city']) ?></td>
                  <td><?= htmlspecialchars($e['square_meters']) ?> m²</td>
                  <td><?= number_format((int)$e['rent'], 0, ',', ' ') ?> €</td>
                  <td><?= htmlspecialchars($e['type_label']) ?></td>
                  <td>
                    <?php if ((int)$e['id_status'] === 1): ?>
                      <span class="pill pill-amber">Déposé</span>
                    <?php elseif ((int)$e['id_status'] === 2): ?>
                      <span class="pill pill-green">Jouable</span>
                    <?php elseif ((int)$e['id_status'] === 3): ?>
                      <span class="pill pill-gold">Archivé</span>
                    <?php elseif ((int)$e['id_status'] === 4): ?>
                      <span class="pill pill-purple">Correction</span>
                    <?php else: ?>
                      <span class="pill pill-gold">Inactif</span>
                    <?php endif; ?>
                  </td>
                  <td class="td-actions">
                    <?php if ((int)$e['id_status'] !== 2): ?>
                      <form method="POST" action="<?= BASE_URL ?>/admin/biens" class="form-inline">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <input type="hidden" name="estate_id" value="<?= (int)$e['id_estate'] ?>">
                        <input type="hidden" name="action" value="activate">
                        <button type="submit" class="btn-primary btn-sm">Activer</button>
                      </form>
                    <?php else: ?>
                      <form method="POST" action="<?= BASE_URL ?>/admin/biens" class="form-inline">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <input type="hidden" name="estate_id" value="<?= (int)$e['id_estate'] ?>">
                        <input type="hidden" name="action" value="deactivate">
                        <button type="submit" class="btn-danger btn-sm">Désactiver</button>
                      </form>
                    <?php endif; ?>
                    <?php if ((int)$e['id_status'] !== 3): ?>
                      <form method="POST" action="<?= BASE_URL ?>/admin/biens" class="form-inline">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <input type="hidden" name="estate_id" value="<?= (int)$e['id_estate'] ?>">
                        <input type="hidden" name="action" value="archive">
                        <button type="submit" class="btn-secondary btn-sm">Archiver</button>
                      </form>
                    <?php endif; ?>
                    <?php if ((int)$e['id_status'] !== 4): ?>
                      <form method="POST" action="<?= BASE_URL ?>/admin/biens" class="form-inline">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <input type="hidden" name="estate_id" value="<?= (int)$e['id_estate'] ?>">
                        <input type="hidden" name="action" value="correction">
                        <button type="submit" class="btn-secondary btn-sm">Correction</button>
                      </form>
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