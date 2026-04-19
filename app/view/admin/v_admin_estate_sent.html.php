<div class="admin-layout">

  <?php require __DIR__ . '/v_admin_sidebar.html.php'; ?>

  <!-- Main content -->
  <section class="admin-main">

    <header class="admin-header-bar">
      <h1>Biens déposés — en attente de validation</h1>
    </header>

    <?php require V_SKELETON . 'v_alerts.html.php'; ?>

    <!-- Step 1: select an estate -->
    <section class="admin-table-wrap">
      <header class="admin-table-header">
        <h2>Étape 1 — Sélectionner un bien <span class="pill pill-amber"><?= count($estates ?? []) ?></span></h2>
      </header>

      <!-- Empty state -->
      <?php if (empty($estates)): ?>
        <p class="admin-empty-state">Aucun bien en attente de validation.</p>
      <?php else: ?>
        <div class="table-overflow">
          <table class="admin-table depose-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Ville</th>
                <th>Surface</th>
                <th>Loyer</th>
                <th>Type</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($estates as $e): ?>
                <tr onclick="selectEstate(<?= (int)$e['id_estate'] ?>)" data-id="<?= (int)$e['id_estate'] ?>">
                  <td><?= (int)$e['id_estate'] ?></td>
                  <td><?= htmlspecialchars($e['city']) ?></td>
                  <td><?= htmlspecialchars($e['square_meters']) ?> m²</td>
                  <td><?= number_format((int)$e['rent'], 0, ',', ' ') ?> €</td>
                  <td><?= htmlspecialchars($e['type_label']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>

    <!-- Step 2: review and decide -->
    <section class="admin-table-wrap" id="estateDetail" style="display:none;">
      <header class="admin-table-header">
        <h2>Étape 2 — Vérifier et décider</h2>
      </header>

      <div class="depose-detail-grid">
        <div class="depose-field">
          <span class="depose-field-label">Type</span>
          <span class="depose-field-value" id="det-type">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Loyer</span>
          <span class="depose-field-value" id="det-rent">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Charges</span>
          <span class="depose-field-value" id="det-charges">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Surface</span>
          <span class="depose-field-value" id="det-m2">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Pièces</span>
          <span class="depose-field-value" id="det-room">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Chambres</span>
          <span class="depose-field-value" id="det-chamber">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Étage</span>
          <span class="depose-field-value" id="det-floor">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Adresse</span>
          <span class="depose-field-value" id="det-adress">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Ville</span>
          <span class="depose-field-value" id="det-city">—</span>
        </div>
        <div class="depose-field">
          <span class="depose-field-label">Code postal</span>
          <span class="depose-field-value" id="det-postcode">—</span>
        </div>
        <div class="depose-field depose-description">
          <span class="depose-field-label">Description</span>
          <span class="depose-field-value" id="det-description">—</span>
        </div>
      </div>

      <div class="depose-images-section">
        <h3>Images</h3>
        <div class="depose-img-grid" id="detImgGrid"></div>
      </div>

      <div class="depose-actions">
        <form method="POST" action="<?= BASE_URL ?>/admin/biens/en-attente" class="form-inline">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
          <input type="hidden" name="estate_id" id="act-estate-id" value="">
          <input type="hidden" name="action" value="activate">
          <button type="submit" class="btn-primary">Activer</button>
        </form>
        <div>
          <button type="button" class="btn-secondary" onclick="openEditForm()">Corriger</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/admin/biens/en-attente" class="form-inline">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
          <input type="hidden" name="estate_id" id="act-estate-id-archive" value="">
          <input type="hidden" name="action" value="archive">
          <button type="submit" class="btn-secondary">Archiver</button>
        </form>
        <form method="POST" action="<?= BASE_URL ?>/admin/biens/en-attente" class="form-inline"
          onsubmit="return confirm('Supprimer définitivement ce bien ?')">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
          <input type="hidden" name="estate_id" id="act-estate-id-delete" value="">
          <input type="hidden" name="action" value="delete">
          <button type="submit" class="btn-danger">Supprimer</button>
        </form>
      </div>

    </section>

    <!-- Step 3: edit the estate -->
    <section class="admin-table-wrap" id="estateEdit" style="display:none;">
      <header class="admin-table-header">
        <h2>Étape 3 — Modifier le bien</h2>
      </header>

      <form method="POST" action="<?= BASE_URL ?>/admin/biens/en-attente" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="estate_id" id="edit-estate-id" value="">
        <input type="hidden" name="id_status" id="edit-id-status" value="">
        <input type="hidden" name="image1" id="edit-img1" value="">
        <input type="hidden" name="image2" id="edit-img2" value="">
        <input type="hidden" name="image3" id="edit-img3" value="">
        <input type="hidden" name="image4" id="edit-img4" value="">
        <input type="hidden" name="old_image1" id="edit-old-img1" value="">
        <input type="hidden" name="old_image2" id="edit-old-img2" value="">
        <input type="hidden" name="old_image3" id="edit-old-img3" value="">
        <input type="hidden" name="old_image4" id="edit-old-img4" value="">
        <input type="file" name="new_image1" id="edit-new-img1" accept="image/jpeg,image/png,image/webp" style="display:none">
        <input type="file" name="new_image2" id="edit-new-img2" accept="image/jpeg,image/png,image/webp" style="display:none">
        <input type="file" name="new_image3" id="edit-new-img3" accept="image/jpeg,image/png,image/webp" style="display:none">
        <input type="file" name="new_image4" id="edit-new-img4" accept="image/jpeg,image/png,image/webp" style="display:none">

        <div class="edit-grid">
          <div class="edit-field">
            <label for="edit-type">Type</label>
            <select id="edit-type" name="type_label" class="form-select" required>
              <?php foreach ($types as $t): ?>
                <option value="<?= htmlspecialchars($t['label']) ?>"><?= htmlspecialchars($t['label']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="edit-field">
            <label for="edit-rent">Loyer (€)</label>
            <input type="number" id="edit-rent" name="rent" class="form-input" min="0" required>
          </div>
          <div class="edit-field">
            <label for="edit-charges">Charges</label>
            <select id="edit-charges" name="is_charges_included" class="form-select">
              <option value="1">Charges comprises</option>
              <option value="0">Hors charges</option>
            </select>
          </div>
          <div class="edit-field">
            <label for="edit-m2">Surface (m²)</label>
            <input type="number" id="edit-m2" name="square_meters" class="form-input" step="0.1" min="0" required>
          </div>
          <div class="edit-field">
            <label for="edit-room">Pièces</label>
            <input type="number" id="edit-room" name="room" class="form-input" min="0" placeholder="Vide = inconnu">
          </div>
          <div class="edit-field">
            <label for="edit-chamber">Chambres</label>
            <input type="number" id="edit-chamber" name="chamber" class="form-input" min="0" placeholder="Vide = inconnu">
          </div>
          <div class="edit-field">
            <label for="edit-floor">Étage</label>
            <input type="number" id="edit-floor" name="floor" class="form-input" min="0" placeholder="Vide = inconnu  |  0 = RDC">
          </div>
          <div class="edit-field">
            <label for="edit-adress">Adresse</label>
            <input type="text" id="edit-adress" name="adress" class="form-input" placeholder="Rue / quartier (optionnel)">
          </div>
          <div class="edit-field">
            <label for="edit-city">Ville</label>
            <input type="text" id="edit-city" name="city" class="form-input" required>
          </div>
          <div class="edit-field">
            <label for="edit-postcode">Code postal</label>
            <input type="text" id="edit-postcode" name="postcode" class="form-input" required>
          </div>
          <div class="edit-field edit-field-full">
            <label for="edit-description">Description</label>
            <textarea id="edit-description" name="description" class="form-input form-textarea" rows="5"></textarea>
          </div>
          <div class="edit-field edit-field-full">
            <label>Images</label>
            <div class="depose-img-grid" id="editImgGrid"></div>
          </div>
        </div>

        <div class="depose-actions">
          <button type="submit" class="btn-primary">Enregistrer les modifications</button>
          <button type="button" class="btn-secondary" onclick="closeEditForm()">Annuler</button>
        </div>
      </form>
    </section>

  </section>
</div>

<!-- Data store: passes PHP data to admin.js via data-* attributes -->
<div id="admin-data" hidden
  data-page="estate-sent"
  data-estates="<?= htmlspecialchars(json_encode(array_values($estates ?? []), JSON_HEX_TAG)) ?>"
  data-base-url="<?= htmlspecialchars(BASE_URL) ?>"></div>