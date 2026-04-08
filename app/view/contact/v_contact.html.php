<div class="contact-body">
  <!-- Hero -->
  <div class="contact-hero">
    <h1>Contactez-nous</h1>
    <p>Une question, un problème ou un bien à nous proposer ?</p>
  </div>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success mb-lg" role="status"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert alert-error mb-lg" role="alert"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <!-- Tabs -->
  <div class="contact-tabs" role="tablist">
    <a href="<?= BASE_URL ?>/contact?tab=message" class="<?= $activeTab === 'message' ? 'active' : '' ?>" role="tab">
      Nous écrire
    </a>
    <a href="<?= BASE_URL ?>/contact?tab=bien" class="<?= $activeTab === 'bien' ? 'active' : '' ?>" role="tab">
      Proposer un bien
    </a>
  </div>

  <?php if ($activeTab === 'message'): ?>
    <!-- Contact form -->
    <form method="POST" action="<?= BASE_URL ?>/contact?tab=message" class="contact-form" novalidate>
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
      <input type="hidden" name="tab" value="message">

      <div class="form-group">
        <label class="form-label">Objet</label>
        <div class="radio-group">
          <?php foreach (['Question générale', 'Signaler un bug', 'Signaler un bien incorrect', 'Autre'] as $objet): ?>
            <div class="radio-pill">
              <input type="radio" name="objet" id="objet_<?= md5($objet) ?>" value="<?= htmlspecialchars($objet) ?>"
                <?= (($_POST['objet'] ?? '') === $objet) ? 'checked' : '' ?>>
              <label for="objet_<?= md5($objet) ?>"><?= htmlspecialchars($objet) ?></label>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="email">Votre e-mail</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-input"
          required
          maxlength="50"
          autocomplete="email"
          placeholder="exemple@mail.com"
          value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label" for="content">Message</label>
        <textarea
          id="content"
          name="content"
          class="form-textarea"
          maxlength="500"
          required
          rows="5"
          placeholder="Décrivez votre demande…"><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
        <div class="char-count"><span id="charCount">0</span> / 500</div>
      </div>

      <button type="submit" class="btn-primary">Envoyer le message</button>
    </form>

  <?php else: ?>
    <!-- Propose a property form -->
    <form method="POST" action="<?= BASE_URL ?>/contact/proposer" class="contact-form" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
      <input type="hidden" name="tab" value="bien">

      <div class="form-group">
        <label class="form-label" for="city">Ville</label>
        <input type="text" id="city" name="city" class="form-input" required maxlength="100" placeholder="Paris">
      </div>

      <div class="form-group">
        <label class="form-label" for="postal">Code postal</label>
        <input type="text" id="postal" name="postal" class="form-input" required maxlength="10" placeholder="75001">
      </div>

      <div class="form-group">
        <label class="form-label" for="neighborhood">Quartier (optionnel)</label>
        <input type="text" id="neighborhood" name="neighborhood" class="form-input" maxlength="100" placeholder="Marais">
      </div>

      <div class="form-group">
        <label class="form-label">Type de bien</label>
        <div class="radio-group">
          <?php foreach (['Appartement', 'Maison', 'Studio', 'Loft', 'Colocation'] as $type): ?>
            <div class="radio-pill">
              <input type="radio" name="type" id="type_<?= md5($type) ?>" value="<?= htmlspecialchars($type) ?>">
              <label for="type_<?= md5($type) ?>"><?= htmlspecialchars($type) ?></label>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="square_meters">Surface (m²)</label>
        <input type="number" id="square_meters" name="square_meters" class="form-input" required min="5" max="999" placeholder="45">
      </div>

      <div class="form-group">
        <label class="form-label" for="rent">Loyer mensuel (€)</label>
        <input type="number" id="rent" name="rent" class="form-input" required min="100" max="99999" placeholder="850">
      </div>

      <div class="form-group">
        <label class="form-label">Photos (jusqu'à 4, min 1)</label>
        <div class="upload-zone" id="uploadZone">
          <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
          </svg>
          <p>Glissez jusqu'à 4 photos ou <strong>parcourez</strong></p>
          <p>JPG, PNG, WebP — max 5 Mo par photo</p>
          <input type="file" name="photos[]" id="photoInput" accept="image/*" multiple required>
        </div>
        <div class="photo-preview" id="photoPreview"></div>
      </div>

      <div class="moderation-note">
        Votre proposition sera examinée par notre équipe avant publication. Les biens incorrects ou incomplets seront refusés.
      </div>

      <button type="submit" class="btn-primary">Soumettre le bien</button>
    </form>
  <?php endif; ?>

</div>