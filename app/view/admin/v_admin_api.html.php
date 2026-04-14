<div class="admin-layout">

  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <nav class="admin-sidebar-section" aria-label="Menu Admin">
      <a href="<?= BASE_URL ?>/admin">Dashboard</a>
    </nav>
    <nav class="admin-sidebar-section" aria-label="Contenu">
      <p class="section-label">Contenu</p>
      <a href="<?= BASE_URL ?>/admin/biens">Biens</a>
      <a href="<?= BASE_URL ?>/admin/api" class="active">Biens de l'api</a>
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

  <section class="admin-main">
    <header class="admin-header-bar">
      <h1>Gestion des biens de l'api</h1>
    </header>

    <button class="btn-primary btn-sm" id="getJson">
      Récupérer
    </button>
  </section>

</div>

<script>
  let getBtn = document.querySelector("#getJson");
  getBtn.addEventListener("click", getDataUrl);

  function getDataUrl() {
    let URL = 'https://wgbqsvxvnheukskrnlzb.supabase.co/rest/v1/Logement?apikey=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6IndnYnFzdnh2bmhldWtza3JubHpiIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzMyNDExMDMsImV4cCI6MjA4ODgxNzEwM30.iRldHE5p3RuUqVWnR5MftBrysd_7c4BkAqHvYyR4ru8';

    fetch(URL)
      .then((result) => result.json())
      .then((result) => (console.log(result)))
      .catch((error) => console.log("Erreur : " + error));
  }
</script>