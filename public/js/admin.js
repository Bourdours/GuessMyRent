// ═══════════════════════════════════════════════════════════════
//   ADMIN 
// ═══════════════════════════════════════════════════════════════

function setText(id, val) {
  document.getElementById(id).textContent = val ?? '—';
}

function setVal(id, val) {
  document.getElementById(id).value = val ?? '';
}

function showForm(formId, visible) {
  document.getElementById(formId).style.display = visible ? '' : 'none';
}

// ── Estate — formulaire édition ────────────────────────────────
function closeEditForm() {
  document.getElementById('estateEdit').style.display = 'none';
}

function openEditForm() {
  if (!currentItem) return;

  setVal('edit-estate-id',   currentItem.id_estate);
  setVal('edit-id-status',   currentItem.id_status);
  setVal('edit-type',        currentItem.type_label || '');
  setVal('edit-rent',        currentItem.rent || '');
  setVal('edit-charges',     currentItem.is_charges_included);
  setVal('edit-m2',          currentItem.square_meters || '');
  setVal('edit-room',        currentItem.room ?? '');
  setVal('edit-chamber',     currentItem.chamber ?? '');
  setVal('edit-floor',       currentItem.floor ?? '');
  setVal('edit-adress',      currentItem.adress || '');
  setVal('edit-city',        currentItem.city || '');
  setVal('edit-postcode',    currentItem.postcode || '');
  setVal('edit-description', currentItem.description || '');

  buildEditImgGrid(currentItem);

  const edit = document.getElementById('estateEdit');
  edit.style.display = '';
  edit.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function buildEditImgGrid(item) {
  const grid = document.getElementById('editImgGrid');
  grid.innerHTML = '';

  [1, 2, 3, 4].forEach(function (n) {
    const url = item['image' + n] || '';

    setVal('edit-img' + n,     url);
    setVal('edit-old-img' + n, url);

    const fileInput = document.getElementById('edit-new-img' + n);
    fileInput.value = '';
    fileInput.onchange = function (e) { previewEditImg(n, e.target.files[0]); };

    // Wrapper slot
    const slot = document.createElement('div');
    slot.id = 'edit-img-card-' + n;
    slot.style.display = 'flex';
    slot.style.flexDirection = 'column';
    slot.style.gap = '0.4rem';

    // Image 
    const card = document.createElement('div');
    card.className = 'depose-img-card';
    card.id = 'edit-img-preview-' + n;

    if (url) {
      const img = document.createElement('img');
      img.src = baseUrl + '/public/img/estates/' + url;
      img.alt = 'Image ' + n;
      card.appendChild(img);
    } else {
      const ph = document.createElement('div');
      ph.className = 'depose-img-placeholder';
      ph.textContent = 'Aucune image';
      card.appendChild(ph);
    }

    // Boutons sous la carte
    const actions = document.createElement('div');
    actions.style.display = 'flex';
    actions.style.gap = '0.4rem';

    const uploadBtn = document.createElement('button');
    uploadBtn.type = 'button';
    uploadBtn.className = 'btn-secondary btn-sm';
    uploadBtn.style.flex = '1';
    uploadBtn.textContent = url ? 'Modifier' : 'Ajouter';
    uploadBtn.onclick = function () { document.getElementById('edit-new-img' + n).click(); };
    actions.appendChild(uploadBtn);

    if (url) {
      const delBtn = document.createElement('button');
      delBtn.type = 'button';
      delBtn.className = 'btn-danger btn-sm';
      delBtn.id = 'edit-del-btn-' + n;
      delBtn.textContent = 'Supprimer';
      delBtn.onclick = function () { deleteEditImg(n); };
      actions.appendChild(delBtn);
    }

    slot.appendChild(card);
    slot.appendChild(actions);
    grid.appendChild(slot);
  });

  refreshDeleteBtns();
}

function countRemainingEditImgs() {
  let count = 0;
  for (let i = 1; i <= 4; i++) {
    const val = document.getElementById('edit-img' + i);
    const file = document.getElementById('edit-new-img' + i);
    if ((val && val.value !== '') || (file && file.files && file.files.length > 0)) count++;
  }
  return count;
}

function refreshDeleteBtns() {
  const remaining = countRemainingEditImgs();
  for (let i = 1; i <= 4; i++) {
    const btn = document.getElementById('edit-del-btn-' + i);
    if (btn) btn.disabled = remaining <= 1;
  }
}

function deleteEditImg(n) {
  setVal('edit-img' + n, '');
  document.getElementById('edit-new-img' + n).value = '';

  const card = document.getElementById('edit-img-preview-' + n);
  card.innerHTML = '<div class="depose-img-placeholder">Supprimée</div>';

  const delBtn = document.getElementById('edit-del-btn-' + n);
  if (delBtn) delBtn.style.display = 'none';

  const slot = document.getElementById('edit-img-card-' + n);
  const uploadBtn = slot.querySelector('.btn-secondary');
  if (uploadBtn) uploadBtn.textContent = 'Ajouter';

  refreshDeleteBtns();
}

function previewEditImg(n, file) {
  if (!file) return;
  setVal('edit-img' + n, '');
  const reader = new FileReader();
  reader.onload = function (e) {
    const card = document.getElementById('edit-img-preview-' + n);
    const img = document.createElement('img');
    img.src = e.target.result;
    img.alt = 'Nouvelle image ' + n;
    card.innerHTML = '';
    card.appendChild(img);

    const slot = document.getElementById('edit-img-card-' + n);
    const uploadBtn = slot.querySelector('.btn-secondary');
    if (uploadBtn) uploadBtn.textContent = 'Modifier';

    // Affiche ou crée le bouton Supprimer si absent
    let delBtn = document.getElementById('edit-del-btn-' + n);
    if (!delBtn) {
      delBtn = document.createElement('button');
      delBtn.type = 'button';
      delBtn.className = 'btn-danger btn-sm';
      delBtn.id = 'edit-del-btn-' + n;
      delBtn.textContent = 'Supprimer';
      delBtn.onclick = function () { deleteEditImg(n); };
      slot.querySelector('div').appendChild(delBtn);
    }
    delBtn.style.display = '';
    refreshDeleteBtns();
  };
  reader.readAsDataURL(file);
}

function buildEstateImageGrid(item) {
  const grid = document.getElementById('detImgGrid');
  grid.innerHTML = '';

  ['image1', 'image2', 'image3', 'image4'].forEach(function (key, i) {
    const url  = item[key] || '';
    const card = document.createElement('div');
    card.className = 'depose-img-card';

    if (url) {
      const img = document.createElement('img');
      img.src = baseUrl + '/public/img/estates/' + url;
      img.alt = 'Image ' + (i + 1);
      card.appendChild(img);
    } else {
      const ph = document.createElement('div');
      ph.className   = 'depose-img-placeholder';
      ph.textContent = 'Aucune image';
      card.appendChild(ph);
    }

    grid.appendChild(card);
  });
}

// ── Estate list ────────────────────────────────────────────────
function selectEstateList(id) {
  const statusLabels = { 1: 'Déposé', 2: 'Jouable', 3: 'Archivé', 4: 'Correction' };

  closeEditForm();
  const item = estates.find(e => Number(e.id_estate) === Number(id));
  if (!item) return;

  document.querySelectorAll('.estate-table tbody tr').forEach(tr => tr.classList.remove('selected'));
  document.querySelector('.estate-table tbody tr[data-id="' + id + '"]').classList.add('selected');

  const status = Number(item.id_status);

  setText('det-type',        item.type_label || '—');
  setText('det-status',      statusLabels[status] || '—');
  setText('det-rent',        item.rent ? Number(item.rent).toLocaleString('fr-FR') + ' €' : '—');
  setText('det-charges',     item.is_charges_included == 1 ? 'Charges comprises' : 'Hors charges');
  setText('det-m2',          item.square_meters ? item.square_meters + ' m²' : '—');
  setText('det-room',        item.room ?? '—');
  setText('det-chamber',     item.chamber ?? '—');
  setText('det-floor',       item.floor !== null ? (item.floor == 0 ? 'RDC' : 'Étage ' + item.floor) : '—');
  setText('det-adress',      item.adress || '—');
  setText('det-city',        item.city || '—');
  setText('det-postcode',    item.postcode || '—');
  setText('det-description', item.description || '—');

  document.getElementById('act-id-activate').value   = id;
  document.getElementById('act-id-deactivate').value = id;
  document.getElementById('act-id-archive').value    = id;
  document.getElementById('act-id-delete').value     = id;

  currentItem = item;

  showForm('form-activate',   status !== 2);
  showForm('form-deactivate', status === 2);
  showForm('form-archive',    status !== 3);
  showForm('form-correction', status !== 4);
  showForm('form-delete',     true);

  buildEstateImageGrid(item);

  const detail = document.getElementById('estateDetail');
  detail.style.display = '';
  detail.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ── Estate en attente ──────────────────────────────────────────
function selectEstateSent(id) {
  closeEditForm();
  const item = estates.find(e => Number(e.id_estate) === Number(id));
  if (!item) return;

  document.querySelectorAll('.depose-table tbody tr').forEach(tr => tr.classList.remove('selected'));
  document.querySelector('.depose-table tbody tr[data-id="' + id + '"]').classList.add('selected');

  setText('det-type',        item.type_label || '—');
  setText('det-rent',        item.rent ? Number(item.rent).toLocaleString('fr-FR') + ' €' : '—');
  setText('det-charges',     item.is_charges_included == 1 ? 'Charges comprises' : 'Hors charges');
  setText('det-m2',          item.square_meters ? item.square_meters + ' m²' : '—');
  setText('det-room',        item.room ?? '—');
  setText('det-chamber',     item.chamber ?? '—');
  setText('det-floor',       item.floor !== null ? (item.floor == 0 ? 'RDC' : 'Étage ' + item.floor) : '—');
  setText('det-adress',      item.adress || '—');
  setText('det-city',        item.city || '—');
  setText('det-postcode',    item.postcode || '—');
  setText('det-description', item.description || '—');

  document.getElementById('act-estate-id').value         = id;
  document.getElementById('act-estate-id-archive').value = id;
  document.getElementById('act-estate-id-delete').value  = id;

  currentItem = item;

  buildEstateImageGrid(item);

  const detail = document.getElementById('estateDetail');
  detail.style.display = '';
  detail.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ═══════════════════════════════════════════════════════════════
//   ADMIN API 
// ═══════════════════════════════════════════════════════════════

const API_URL = 'https://wgbqsvxvnheukskrnlzb.supabase.co/rest/v1/Logement?apikey=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6IndnYnFzdnh2bmhldWtza3JubHpiIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzMyNDExMDMsImV4cCI6MjA4ODgxNzEwM30.iRldHE5p3RuUqVWnR5MftBrysd_7c4BkAqHvYyR4ru8';

function parseLieux(lieux) {
  if (!lieux) return { adress: '', city: '', postcode: '' };
  const pcMatch   = lieux.match(/\((\d{4,5})\)/);
  const postcode  = pcMatch ? pcMatch[1] : '';
  const cleaned   = lieux.replace(/\s*\(\d{4,5}\)/, '').trim();
  const lastComma = cleaned.lastIndexOf(',');
  if (lastComma === -1) return { adress: '', city: cleaned.trim(), postcode };
  return {
    adress:   cleaned.substring(0, lastComma).trim(),
    city:     cleaned.substring(lastComma + 1).trim(),
    postcode,
  };
}

function parseFloor(etage) {
  if (!etage) return '';
  if (/rdc/i.test(etage)) return '0';
  const m = etage.match(/\d+/);
  return m ? m[0] : '';
}

function parseRent(loyer) {
  return loyer ? loyer.replace(/[^\d]/g, '') : '';
}

function parseSquareMeters(m2) {
  return m2 ? m2.replace(/[^\d,.]/g, '').replace(',', '.') : '';
}

function parseNumber(str) {
  if (!str) return '';
  const m = str.match(/\d+/);
  return m ? m[0] : '';
}

function parseType(type) {
  if (!type) return 'Autre';
  const idx = type.indexOf(' à ');
  return idx !== -1 ? type.substring(0, idx) : type;
}

function fetchBiens() {
  const btn    = document.getElementById('getJson');
  const status = document.getElementById('fetch-status');

  btn.disabled         = true;
  btn.textContent      = 'Chargement…';
  status.style.display = '';
  status.textContent   = 'Récupération en cours…';

  fetch(API_URL)
    .then(res => res.json())
    .then(data => {
      apiData = data;
      populateDropdown(apiData);
      const already = apiData.filter(d => importedIds.includes(Number(d.ID))).length;
      status.textContent = apiData.length + ' biens récupérés — ' + already + ' déjà importé(s).';
      btn.textContent    = 'Actualiser';
    })
    .catch(e => {
      status.textContent = 'Erreur : ' + e.message;
      btn.textContent    = 'Réessayer';
    })
    .finally(() => { btn.disabled = false; });
}

function populateDropdown(data) {
  const select = document.getElementById('apiSelect');
  select.innerHTML = '<option value="">-- Sélectionner un bien --</option>';

  [...data].sort((a, b) => a.ID - b.ID).forEach(item => {
    const isImported = importedIds.includes(Number(item.ID));
    const opt        = document.createElement('option');
    opt.value        = item.ID;
    const city       = parseLieux(item.lieux).city || 'Lieu inconnu';
    opt.textContent  = `#${item.ID} — ${city}${isImported ? '  - Importé' : ''}`;
    if (isImported) { opt.disabled = true; opt.style.color = '#999'; }
    select.appendChild(opt);
  });

  document.getElementById('selectWrapper').style.display = '';
}

function selectBien(id) {
  const detail = document.getElementById('bienDetail');
  if (!id) { detail.style.display = 'none'; return; }

  const item = apiData.find(d => String(d.ID) === String(id));
  if (!item) return;

  const lieux     = parseLieux(item.lieux);
  const isCharges = (item.charges_comprises || '').toLowerCase().includes('comprises') ? '1' : '0';

  setText('raw-loyer',   item.loyer);
  setText('raw-charges', item.charges_comprises);
  setText('raw-lieux',   item.lieux);
  setText('raw-chambre', item.chambre);
  setText('raw-piece',   item.piece);
  setText('raw-m2',      item.metre_carre);
  setText('raw-etage',   item.etage);
  setText('raw-type',    item.type);
  setText('raw-desc',    item.description);

  setVal('inp-api-id',      id);
  setVal('inp-rent',        parseRent(item.loyer));
  document.getElementById('inp-charges').value = isCharges;
  setVal('inp-adress',      lieux.adress);
  setVal('inp-city',        lieux.city);
  setVal('inp-postcode',    lieux.postcode);
  setVal('inp-chamber',     parseNumber(item.chambre));
  setVal('inp-room',        parseNumber(item.piece));
  setVal('inp-m2',          parseSquareMeters(item.metre_carre));
  setVal('inp-floor',       parseFloor(item.etage));
  setVal('inp-type',        parseType(item.type));
  setVal('inp-description', item.description || '');

  const grid = document.getElementById('imgGrid');
  grid.innerHTML = '';
  let availableCount = 0;

  ['image', 'image2', 'image3', 'image4'].forEach((key, i) => {
    const n   = i + 1;
    const url = item[key] || '';
    if (url) availableCount++;

    const card     = document.createElement('div');
    card.id        = 'img-card-' + n;
    card.className = 'api-img-card' + (url ? '' : ' excluded');

    if (url) {
      const img = document.createElement('img');
      img.src = url; img.alt = 'Image ' + n;
      card.appendChild(img);
    } else {
      const ph = document.createElement('div');
      ph.className = 'api-img-placeholder'; ph.textContent = 'Aucune image';
      card.appendChild(ph);
    }

    const footer     = document.createElement('div');
    footer.className = 'api-img-card-footer';
    const cb         = document.createElement('input');
    cb.type = 'checkbox'; cb.id = 'chk-img' + n;
    cb.checked = !!url; cb.disabled = !url; cb.dataset.url = url;
    cb.addEventListener('change', () => toggleImage(n, cb));
    const lbl       = document.createElement('label');
    lbl.htmlFor     = 'chk-img' + n;
    lbl.textContent = url ? 'Image ' + n : 'Aucune image';
    footer.appendChild(cb); footer.appendChild(lbl);
    card.appendChild(footer);
    grid.appendChild(card);
    setVal('inp-img' + n, url);
  });

  updateImgCountLabel(availableCount, availableCount);
  detail.style.display = '';
  detail.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function toggleImage(n, cb) {
  const card = document.getElementById('img-card-' + n);
  if (cb.checked) {
    card.classList.remove('excluded');
    setVal('inp-img' + n, cb.dataset.url);
  } else {
    card.classList.add('excluded');
    setVal('inp-img' + n, '');
  }
  updateImgCountLabel(
    document.querySelectorAll('.api-img-card:not(.excluded) img').length,
    document.querySelectorAll('.api-img-card img').length
  );
}

function updateImgCountLabel(selected, available) {
  document.getElementById('img-count-label').textContent =
    available > 0 ? `(${selected} / ${available} sélectionnée${selected > 1 ? 's' : ''})` : '';
}

// ── User list ──────────────────────────────────────────────────
function selectUser(id) {
  const item = users.find(u => Number(u.id_user) === Number(id));
  if (!item) return;

  document.querySelectorAll('.user-table tbody tr').forEach(tr => tr.classList.remove('selected'));
  const row = document.querySelector('.user-table tbody tr[data-id="' + id + '"]');
  if (row) row.classList.add('selected');

  setVal('uedit-user-id', item.id_user);
  setVal('uedit-pseudo',  item.pseudo  || '');
  setVal('uedit-email',   item.email   || '');
  setVal('uedit-avatar',  item.avatar  || '');
  setVal('uedit-password', '');
  document.getElementById('uedit-role').value = '0';

  const edit = document.getElementById('userEdit');
  edit.style.display = '';
  edit.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function closeUserEditForm() {
  document.getElementById('userEdit').style.display = 'none';
  document.querySelectorAll('.user-table tbody tr').forEach(tr => tr.classList.remove('selected'));
}

// ═══════════════════════════════════════════════════════════════
//   ADMIN GAMES
// ═══════════════════════════════════════════════════════════════

function selectGame(id) {
  const item = games.find(g => Number(g.id_game) === Number(id));
  if (!item) return;

  document.querySelectorAll('.game-table tbody tr').forEach(tr => tr.classList.remove('selected'));
  document.querySelector('.game-table tbody tr[data-id="' + id + '"]').classList.add('selected');

  setText('gdet-id',     item.id_game);
  setText('gdet-date',   item.date ? item.date.replace('T', ' ').substring(0, 16) : '—');
  setText('gdet-pseudo', item.pseudo || 'Anonyme');
  setText('gdet-city',   item.city   || '—');
  setText('gdet-guess',  item.guess  ? Number(item.guess).toLocaleString('fr-FR') + ' €' : '—');
  setText('gdet-score',  item.game_result !== null && item.game_result !== undefined ? item.game_result + ' pts' : '—');

  document.getElementById('gact-id-delete').value = id;

  showForm('gform-delete', true);

  const detail = document.getElementById('gameDetail');
  detail.style.display = '';
  detail.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ═══════════════════════════════════════════════════════════════
//   ADMIN MESSAGE
// ═══════════════════════════════════════════════════════════════

function selectMessage(id) {
  const item = message.find(m => Number(m.id_message) === Number(id));
  if (!item) return;

  document.querySelectorAll('.message-table tbody tr').forEach(tr => tr.classList.remove('selected'));
  document.querySelector('.message-table tbody tr[data-id="' + id + '"]').classList.add('selected');

  setText('mdet-id',      item.id_message);
  setText('mdet-email',   item.email || 'Anonyme');
  setText('mdet-objet',   item.objet || '—');
  setText('mdet-content', item.content || '—');
  setText('mdet-user',    item.pseudo || 'Anonyme');

  document.getElementById('mact-id-delete').value = id;

  showForm('mform-delete', true);

  const detail = document.getElementById('messageDetail');
  detail.style.display = '';
  detail.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ═══════════════════════════════════════════════════════════════
//   INITIALISATION
// ═══════════════════════════════════════════════════════════════

let estates      = [];
let games        = [];
let message =[];
let users        = [];
let currentItem  = null;
let baseUrl      = '';
let importedIds  = [];
let apiData      = [];
let selectEstate = function () {};

document.addEventListener('DOMContentLoaded', function () {
  const el = document.getElementById('admin-data');
  if (!el) return;

  const page = el.dataset.page;

  if (page === 'estate-list' || page === 'estate-sent') {
    estates = JSON.parse(el.dataset.estates || '[]');
    baseUrl = el.dataset.baseUrl || '';
    selectEstate = page === 'estate-list' ? selectEstateList : selectEstateSent;
  }

  if (page === 'game-list') {
    games = JSON.parse(el.dataset.games || '[]');
  }

  if (page === 'message-list') {
    message = JSON.parse(el.dataset.message || '[]');
  }

  if (page === 'user-list') {
    users = JSON.parse(el.dataset.users || '[]');
  }

  if (page === 'api') {
    importedIds = JSON.parse(el.dataset.importedIds || '[]');
    const btn = document.getElementById('getJson');
    if (btn) btn.addEventListener('click', fetchBiens);
  }
});
