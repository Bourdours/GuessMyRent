<div class="admin-layout">

    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="admin-sidebar-section">
            <a href="<?= BASE_URL ?>/admin">Dashboard</a>
            <div class="section-label">Contenu</div>
            <a href="<?= BASE_URL ?>/admin/biens">Biens</a>
            <a href="<?= BASE_URL ?>/admin/api" class="active">Biens de l'api</a>
        </div>
        <div class="admin-sidebar-section">
            <div class="section-label">Utilisateurs</div>
            <a href="<?= BASE_URL ?>/admin/utilisateurs">Utilisateurs</a>
        </div>
        <div class="admin-sidebar-section">
            <div class="section-label">Navigation</div>
            <a href="<?= BASE_URL ?>/">← Retour au site</a>
        </div>
    </aside>

    <div class="admin-main">
        <div class="admin-header-bar">
            <h2>Gestion des biens de l'api</h2>
        </div>

        <button class="btn-primary btn-sm" id="getJson">
            Récupérer
        </button>
    </div>
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