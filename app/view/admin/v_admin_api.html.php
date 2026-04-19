<div class="admin-layout">

  <?php require __DIR__ . '/v_admin_sidebar.html.php'; ?>

  <!-- Main content -->
  <section class="admin-main">

    <header class="admin-header-bar">
      <h1>Gestion des biens de l'api</h1>
    </header>

    <?php require V_SKELETON . 'v_alerts.html.php'; ?>

    <!-- Step 1: fetch -->
    <section class="admin-table-wrap">
      <header class="admin-table-header">
        <h2>Étape 1 — Récupérer les biens</h2>
      </header>
      <div class="api-fetch-bar">
        <button class="btn-primary btn-sm" id="getJson" type="button">Récupérer les biens de l'API</button>
        <p id="fetch-status" class="api-status" style="display:none;"></p>
      </div>
    </section>

    <!-- Step 2: select -->
    <section class="admin-table-wrap" id="selectWrapper" style="display:none;">
      <header class="admin-table-header">
        <h2>Étape 2 — Sélectionner un bien</h2>
      </header>
      <div class="api-select-wrapper">
        <select class="api-select form-select" id="apiSelect" onchange="selectBien(this.value)">
          <option value="">-- Sélectionner un bien --</option>
        </select>
      </div>
    </section>

    <!-- Step 3: review and import -->
    <section class="admin-table-wrap" id="bienDetail" style="display:none;">
      <header class="admin-table-header">
        <h2>Étape 3 — Vérifier et importer</h2>
      </header>

      <form method="POST" action="<?= BASE_URL ?>/admin/api">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
        <input type="hidden" name="api_id" id="inp-api-id" value="">
        <input type="hidden" name="image_url_1" id="inp-img1" value="">
        <input type="hidden" name="image_url_2" id="inp-img2" value="">
        <input type="hidden" name="image_url_3" id="inp-img3" value="">
        <input type="hidden" name="image_url_4" id="inp-img4" value="">

        <div class="table-overflow">
          <table class="admin-table api-mapping-table">
            <thead>
              <tr>
                <th>Champ</th>
                <th>Valeur API (brute)</th>
                <th>Valeur à enregistrer en BDD</th>
              </tr>
            </thead>
            <tbody>

              <tr>
                <td class="field-label">Loyer</td>
                <td class="api-raw" id="raw-loyer">—</td>
                <td>
                  <div class="field-with-unit">
                    <input type="number" name="rent" id="inp-rent" class="form-input" min="0" required placeholder="850">
                    <span>€</span>
                  </div>
                </td>
              </tr>

              <tr>
                <td class="field-label">Charges</td>
                <td class="api-raw" id="raw-charges">—</td>
                <td>
                  <select name="is_charges_included" id="inp-charges" class="form-select">
                    <option value="1">Charges comprises</option>
                    <option value="0">Hors charges</option>
                  </select>
                </td>
              </tr>

              <!-- location → 3 DB fields, 1 API cell -->
              <tr>
                <td class="field-label">Adresse</td>
                <td class="api-raw" id="raw-lieux" rowspan="3">—</td>
                <td>
                  <input type="text" name="adress" id="inp-adress" class="form-input" placeholder="Rue / quartier (optionnel)">
                </td>
              </tr>
              <tr>
                <td class="field-label">Ville</td>
                <td>
                  <input type="text" name="city" id="inp-city" class="form-input" required placeholder="Ville">
                </td>
              </tr>
              <tr>
                <td class="field-label">Code postal</td>
                <td>
                  <input type="text" name="postcode" id="inp-postcode" class="form-input" required placeholder="75015">
                </td>
              </tr>

              <tr>
                <td class="field-label">Chambres</td>
                <td class="api-raw" id="raw-chambre">—</td>
                <td>
                  <input type="number" name="chamber" id="inp-chamber" class="form-input" min="0" placeholder="Vide = inconnu">
                </td>
              </tr>

              <tr>
                <td class="field-label">Pièces</td>
                <td class="api-raw" id="raw-piece">—</td>
                <td>
                  <input type="number" name="room" id="inp-room" class="form-input" min="0" placeholder="Vide = inconnu">
                </td>
              </tr>

              <tr>
                <td class="field-label">Surface</td>
                <td class="api-raw" id="raw-m2">—</td>
                <td>
                  <div class="field-with-unit">
                    <input type="number" name="square_meters" id="inp-m2" class="form-input" step="0.1" min="0" required placeholder="45">
                    <span>m²</span>
                  </div>
                </td>
              </tr>

              <tr>
                <td class="field-label">Étage</td>
                <td class="api-raw" id="raw-etage">—</td>
                <td>
                  <input type="number" name="floor" id="inp-floor" class="form-input" min="0" placeholder="Vide = inconnu  |  0 = RDC">
                </td>
              </tr>

              <tr>
                <td class="field-label">Type</td>
                <td class="api-raw" id="raw-type">—</td>
                <td>
                  <input type="text" name="type_label" id="inp-type" class="form-input" required placeholder="Appartement, Maison, Studio…">
                </td>
              </tr>

              <tr>
                <td class="field-label">Description</td>
                <td class="api-raw" id="raw-desc">—</td>
                <td>
                  <textarea name="description" id="inp-description" class="form-input form-textarea" rows="6"
                    placeholder="Description du bien (modifiable avant import)…"></textarea>
                </td>
              </tr>

            </tbody>
          </table>
        </div>

        <!-- Image preview + selection -->
        <div class="api-images-section">
          <h3>Images <span class="api-status" id="img-count-label"></span></h3>
          <div class="api-img-grid" id="imgGrid"></div>
        </div>

        <div class="api-actions admin-table-btn-group">
          <button type="submit" class="btn-primary">Importer ce bien</button>
        </div>
      </form>
    </section>

  </section>
</div>

<!-- Data store: passes PHP data to admin.js via data-* attributes -->
<div id="admin-data" hidden
  data-page="api"
  data-imported-ids="<?= htmlspecialchars(json_encode(array_map('intval', $imported_api_ids ?? []))) ?>"
></div>
