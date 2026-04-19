// ═══════════════════════════════════════════════════════════════
//   PROFIL — game detail
// ═══════════════════════════════════════════════════════════════

let profilHistory = [];
let profilBaseUrl = "";

document.addEventListener("DOMContentLoaded", function () {
  const el = document.getElementById("profil-data");
  if (!el) return;
  profilHistory = JSON.parse(el.dataset.history || "[]");
  profilBaseUrl = el.dataset.baseUrl || "";
});

function selectProfilGame(id) {
  const game = profilHistory.find((g) => Number(g.id_game) === Number(id));
  if (!game) return;

  document
    .querySelectorAll(".history-table tbody tr")
    .forEach((tr) => tr.classList.remove("selected"));
  const row = document.querySelector(
    '.history-table tbody tr[data-id="' + id + '"]',
  );
  if (row) row.classList.add("selected");

  function setText(elId, val) {
    const el = document.getElementById(elId);
    if (el) el.textContent = val ?? "—";
  }

  // Deleted estate — show placeholder values
  if (!game.id_estate) {
    setText("pdet-type", "—");
    setText("pdet-rent", "—");
    setText("pdet-charges", "—");
    setText("pdet-m2", "—");
    setText("pdet-room", "—");
    setText("pdet-chamber", "—");
    setText("pdet-floor", "—");
    setText("pdet-adress", "Bien supprimé");
    setText("pdet-city", "—");
    setText("pdet-description", "—");
  } else {
    setText("pdet-type", game.type_label || "—");
    setText(
      "pdet-rent",
      game.rent ? Number(game.rent).toLocaleString("fr-FR") + " €" : "—",
    );
    setText(
      "pdet-charges",
      game.is_charges_included == 1 ? "Charges comprises" : "Hors charges",
    );
    setText("pdet-m2", game.square_meters ? game.square_meters + " m²" : "—");
    setText("pdet-room", game.room ?? "—");
    setText("pdet-chamber", game.chamber ?? "—");
    setText(
      "pdet-floor",
      game.floor !== null && game.floor !== undefined
        ? game.floor == 0
          ? "RDC"
          : "Étage " + game.floor
        : "—",
    );
    setText("pdet-adress", game.adress || "—");
    setText("pdet-city", game.city || "—");
    setText("pdet-description", game.description || "—");
  }

  setText(
    "pdet-guess",
    game.guess ? Number(game.guess).toLocaleString("fr-FR") + " €" : "—",
  );
  setText(
    "pdet-score",
    game.game_result !== null && game.game_result !== undefined
      ? game.game_result + " pts"
      : "—",
  );

  // Image grid
  const grid = document.getElementById("pdet-img-grid");
  grid.innerHTML = "";
  ["image1", "image2", "image3", "image4"].forEach(function (key) {
    const url = game[key] || "";
    if (!url) return;
    const card = document.createElement("div");
    card.className = "depose-img-card";
    const img = document.createElement("img");
    img.src = profilBaseUrl + "/public/img/estates/" + url;
    img.alt = "";
    card.appendChild(img);
    grid.appendChild(card);
  });

  const detail = document.getElementById("profilEstateDetail");
  detail.style.display = "";
  detail.scrollIntoView({ behavior: "smooth", block: "start" });
}

function closeProfilDetail() {
  document.getElementById("profilEstateDetail").style.display = "none";
  document
    .querySelectorAll(".history-table tbody tr")
    .forEach((tr) => tr.classList.remove("selected"));
}

// ═══════════════════════════════════════════════════════════════
//   PROFIL — action panels (edit / delete)
// ═══════════════════════════════════════════════════════════════

function toggleProfilPanel(name) {
  const panels = ["edit", "delete"];
  panels.forEach(function (p) {
    const el = document.getElementById("profil-panel-" + p);
    if (!el) return;
    if (p === name) {
      const isOpen = el.style.display !== "none";
      el.style.display = isOpen ? "none" : "";
      if (!isOpen) el.scrollIntoView({ behavior: "smooth", block: "start" });
    } else {
      el.style.display = "none";
    }
  });
}
