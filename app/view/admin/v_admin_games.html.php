<div class="admin-layout">

  <?php require __DIR__ . '/v_admin_sidebar.html.php'; ?>

  <!-- Main -->
  <section class="admin-main">

    <header class="admin-header-bar">
      <h1>Gestion des parties</h1>
    </header>

    <?php require V_SKELETON . 'v_alerts.html.php'; ?>

    <!-- Liste des parties -->
    <section class="admin-table-wrap">
      <header class="admin-table-header">
        <h2>Parties <span class="pill pill-purple"><?= count($games ?? []) ?></span></h2>
      </header>

      <?php if (empty($games)): ?>
        <p class="admin-empty-state">Aucune partie enregistrée.</p>
      <?php else: ?>
        <div class="table-overflow">
          <table class="admin-table game-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Joueur</th>
                <th>Bien</th>
                <th>Estimation</th>
                <th>Score</th>
                <th>Action rapide</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($games as $g): ?>
                <tr onclick="selectGame(<?= (int)$g['id_game'] ?>)" data-id="<?= (int)$g['id_game'] ?>">
                  <td><?= (int)$g['id_game'] ?></td>
                  <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($g['date']))) ?></td>
                  <td><?= $g['pseudo'] !== null ? htmlspecialchars($g['pseudo']) : '<em>Anonyme</em>' ?></td>
                  <td><?= $g['city'] !== null ? htmlspecialchars($g['city']) : '—' ?></td>
                  <td><?= number_format((int)$g['guess'], 0, ',', ' ') ?> €</td>
                  <td>
                    <?php if ($g['game_result'] !== null): ?>
                      <span class="pill <?= (int)$g['game_result'] >= 80 ? 'pill-green' : ((int)$g['game_result'] >= 50 ? 'pill-amber' : 'pill-red') ?>">
                        <?= (int)$g['game_result'] ?> pts
                      </span>
                    <?php else: ?>
                      —
                    <?php endif; ?>
                  </td>
                  <td class="td-actions" onclick="event.stopPropagation()">
                    <form method="POST" action="<?= BASE_URL ?>/admin/parties" class="form-inline"
                          onsubmit="return confirm('Supprimer définitivement cette partie ?')">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                      <input type="hidden" name="game_id" value="<?= (int)$g['id_game'] ?>">
                      <input type="hidden" name="action" value="delete">
                      <button type="submit" class="btn-danger btn-sm">Supprimer</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>

    <!-- Détail d'une partie -->
    <section class="admin-table-wrap" id="gameDetail" style="display:none;">
      <header class="admin-table-header">
        <h2>Détail de la partie</h2>
      </header>

      <div class="depose-detail-grid">
        <div class="depose-field">
          <span class="depose-field-label">ID</span>
          <span class="depose-field-value" id="gdet-id">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Date</span>
          <span class="depose-field-value" id="gdet-date">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Joueur</span>
          <span class="depose-field-value" id="gdet-pseudo">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Bien (ville)</span>
          <span class="depose-field-value" id="gdet-city">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Estimation</span>
          <span class="depose-field-value" id="gdet-guess">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Score</span>
          <span class="depose-field-value" id="gdet-score">—</span>
        </div>
      </div>

      <div class="depose-actions">
        <form method="POST" action="<?= BASE_URL ?>/admin/parties" class="form-inline" id="gform-delete"
              onsubmit="return confirm('Supprimer définitivement cette partie ?')" style="display:none;">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
          <input type="hidden" name="game_id" id="gact-id-delete" value="">
          <input type="hidden" name="action" value="delete">
          <button type="submit" class="btn-danger">Supprimer</button>
        </form>
      </div>
    </section>

  </section>
</div>

<div id="admin-data" hidden
  data-page="game-list"
  data-games="<?= htmlspecialchars(json_encode(array_values($games ?? []), JSON_HEX_TAG)) ?>"
></div>
