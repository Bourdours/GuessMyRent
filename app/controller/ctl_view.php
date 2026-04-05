<?php
require_once MODEL . "mdl_user.php";
require_once MODEL . "mdl_estate.php";
require_once MODEL . "mdl_message.php";
require_once MODEL . "mdl_estate_type.php";
require_once MODEL . "mdl_estate_status.php";

class ViewController
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        require V_SKELETON . 'v_header.html.php';
        require $view;
        require V_SKELETON . 'v_footer.html.php';
    }

    public function home(): void
    {
        $this->render(VIEW . 'v_index.html.php', ['pageTitle' => 'Accueil']);
    }

    public function play(): void
    {
        $this->render(V_GAME . 'v_game.html.php', ['pageTitle' => 'Jouer']);
    }

    public function rules(): void
    {
        $this->render(V_RULES . 'v_rules.html.php', ['pageTitle' => 'Règles']);
    }

    // affiche /contact par defaut, affichage selon le post soit email soit form bien
    public function contact(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                http_response_code(403);
                die('Requête invalide.');
            }

            $tab    = $_POST['tab'] ?? 'message';
            $result = $tab === 'bien'
                ? $this->handleContactEstate()
                : $this->handleContactMessage();

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->render(V_CONTACT . 'v_contact.html.php', array_merge(
                ['pageTitle' => 'Contact', 'csrf_token' => $_SESSION['csrf_token'], 'activeTab' => $tab],
                $result
            ));
            return;
        }

        $activeTab = $_GET['tab'] ?? 'message';
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $this->render(V_CONTACT . 'v_contact.html.php', ['pageTitle' => 'Contact', 'csrf_token' => $_SESSION['csrf_token'], 'activeTab' => $activeTab]);
    }
    // récupère email/content => verifie validité => stock bdd
    private function handleContactMessage(): array
    {
        $email   = trim($_POST['email'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $content === '') {
            return ['error' => 'Un email valide et un message sont requis.'];
        }

        (new MessageModel())->create($email, $content, $_SESSION['user_id'] ?? null);

        return ['success' => 'Votre message a bien été envoyé.'];
    }

    // récupère donnée POST, verifie que les clefs obligatoires soient renseignés, controle l'image => création bdd 
    private function handleContactEstate(): array
    {
        if (empty($_SESSION['user_id'])) {
            return ['error' => 'Vous devez être connecté pour proposer un bien.'];
        }

        $city         = trim($_POST['city'] ?? '');
        $postal       = trim($_POST['postal'] ?? '');
        $neighborhood = trim($_POST['neighborhood'] ?? '');
        $typeLabel    = $_POST['type'] ?? '';
        $squareMeters = (float) ($_POST['square_meters'] ?? 0);
        $rent         = (int) ($_POST['rent'] ?? 0);

        if ($city === '' || $postal === '' || $typeLabel === '' || $squareMeters <= 0 || $rent <= 0) {
            return ['error' => 'Veuillez remplir tous les champs obligatoires.'];
        }

        $type = (new TypeModel())->findByLabel($typeLabel);
        if (!$type) {
            return ['error' => 'Type de bien invalide.'];
        }

        $status = (new StatusModel())->findByLabel('déposé');
        if (!$status) {
            return ['error' => 'Statut indisponible, veuillez réessayer.'];
        }

        if (empty($_FILES['photos']['tmp_name'][0]) || $_FILES['photos']['error'][0] !== UPLOAD_ERR_OK) {
            return ['error' => 'Au moins une photo est requise.'];
        }

        $imageNames  = [null, null, null, null];
        $mimeToExt   = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $finfo       = new finfo(FILEINFO_MIME_TYPE);

        if (!empty($_FILES['photos']['tmp_name'])) {
            $count = min(count($_FILES['photos']['tmp_name']), 4);
            for ($i = 0; $i < $count; $i++) {
                if ($_FILES['photos']['error'][$i] !== UPLOAD_ERR_OK) continue;

                $tmpName = $_FILES['photos']['tmp_name'][$i];
                $mime    = $finfo->file($tmpName);

                if (!isset($mimeToExt[$mime]) || $_FILES['photos']['size'][$i] > 5 * 1024 * 1024) {
                    return ['error' => 'Image ' . ($i + 1) . ' invalide (JPG, PNG, WebP — 5 Mo max).'];
                }

                // Vérifie que c'est une vraie image
                if (getimagesize($tmpName) === false) {
                    return ['error' => 'Image ' . ($i + 1) . ' corrompue ou invalide.'];
                }

                // Extension dérivée du MIME, jamais du nom fourni par l'utilisateur
                $imageNames[$i] = uniqid('estate_', true) . '.' . $mimeToExt[$mime];

                if (!move_uploaded_file($tmpName, IMG . 'estates/' . $imageNames[$i])) {
                    return ['error' => "Erreur lors de l'envoi de l'image " . ($i + 1) . "."];
                }
            }
        }

        (new EstateModel())->create([
            'rent'                => $rent,
            'is_charges_included' => 0,
            'adress'              => $neighborhood,
            'city'                => $city,
            'postcode'            => $postal,
            'gps_y'               => null,
            'gps_x'               => null,
            'square_meters'       => (string) $squareMeters,
            'room'                => null,
            'chamber'             => null,
            'floor_'              => null,
            'description'         => null,
            'image1'              => $imageNames[0],
            'image2'              => $imageNames[1],
            'image3'              => $imageNames[2],
            'image4'              => $imageNames[3],
            'id_status'           => $status['id_status'],
            'id_type'             => $type['id_type'],
            'id_user'             => $_SESSION['user_id'] ?? null,
        ]);

        return ['success' => 'Votre proposition a bien été soumise et sera examinée par notre équipe.'];
    }

    public function info(): void
    {
        $this->render(V_INFO . 'v_info.html.php', ['pageTitle' => 'Informations']);
    }
}
