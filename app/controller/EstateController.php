<?php
namespace GmR\controller;
use GmR\controller\BaseController;
use GmR\model\EstateModel;
use GmR\model\TypeModel;
use GmR\model\StatusModel;

class EstateController extends BaseController
{
    // GET : affiche le formulaire de proposition
    // POST : soumet un bien
    public function propose(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                http_response_code(403);
                die('Requête invalide.');
            }

            $result = $this->handlePropose();
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->render(V_CONTACT . 'v_contact.html.php', array_merge(
                ['pageTitle' => 'Proposer un bien', 'csrf_token' => $_SESSION['csrf_token'], 'activeTab' => 'bien'],
                $result
            ));
            return;
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $this->render(V_CONTACT . 'v_contact.html.php', [
            'pageTitle'  => 'Proposer un bien',
            'csrf_token' => $_SESSION['csrf_token'],
            'activeTab'  => 'bien',
        ]);
    }

    private function handlePropose(): array
    {
        $city             = trim($_POST['city'] ?? '');
        $postal           = trim($_POST['postal'] ?? '');
        $adress           = trim($_POST['adress'] ?? '') ?: null;
        $typeLabel        = $_POST['type'] ?? '';
        $squareMeters     = (float) ($_POST['square_meters'] ?? 0);
        $rent             = (int) round((int) ($_POST['rent'] ?? 0) / 10) * 10;
        $room             = $_POST['room'] !== '' ? (int) ($_POST['room'] ?? 0) : null;
        $chamber          = $_POST['chamber'] !== '' ? (int) ($_POST['chamber'] ?? 0) : null;
        $floor            = $_POST['floor'] !== '' ? (int) ($_POST['floor'] ?? 0) : null;
        $description      = trim($_POST['description'] ?? '') ?: null;
        $isChargesIncluded = isset($_POST['is_charges_included']) ? 1 : 0;

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

        $imageNames = [null, null, null, null];
        $mimeToExt  = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $finfo      = new \finfo(FILEINFO_MIME_TYPE);

        $count = min(count($_FILES['photos']['tmp_name']), 4);
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['photos']['error'][$i] !== UPLOAD_ERR_OK) continue;

            $tmpName = $_FILES['photos']['tmp_name'][$i];
            $mime    = $finfo->file($tmpName);

            if (!isset($mimeToExt[$mime]) || $_FILES['photos']['size'][$i] > 5 * 1024 * 1024) {
                return ['error' => 'Image ' . ($i + 1) . ' invalide (JPG, PNG, WebP — 5 Mo max).'];
            }

            if (getimagesize($tmpName) === false) {
                return ['error' => 'Image ' . ($i + 1) . ' corrompue ou invalide.'];
            }

            $imageNames[$i] = uniqid('estate_', true) . '.' . $mimeToExt[$mime];

            if (!move_uploaded_file($tmpName, IMG . 'estates/' . $imageNames[$i])) {
                return ['error' => "Erreur lors de l'envoi de l'image " . ($i + 1) . "."];
            }
        }

        (new EstateModel())->create([
            'rent'                => $rent,
            'is_charges_included' => $isChargesIncluded,
            'adress'              => $adress,
            'city'                => $city,
            'postcode'            => $postal,
            'gps_y'               => null,
            'gps_x'               => null,
            'square_meters'       => (string) $squareMeters,
            'room'                => $room,
            'chamber'             => $chamber,
            'floor'               => $floor,
            'description'         => $description,
            'image1'              => $imageNames[0],
            'image2'              => $imageNames[1],
            'image3'              => $imageNames[2],
            'image4'              => $imageNames[3],
            'id_status'           => $status['id_status'],
            'id_type'             => $type['id_type'],
            'id_user'             => $_SESSION['user_id'],
        ]);

        return ['success' => 'Votre proposition a bien été soumise et sera examinée par notre équipe.'];
    }

    // Gestion admin des biens (liste + changement de statut)
    public function adminList(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                http_response_code(403);
                die('Requête invalide.');
            }

            $action   = $_POST['action'] ?? '';
            $estateId = (int) ($_POST['estate_id'] ?? 0);
            $model    = new EstateModel();

            $statusMap = [
                'activate'   => 2,
                'deactivate' => 1,
                'archive'    => 3,
                'correction' => 4,
            ];
            if (isset($statusMap[$action])) {
                $model->updateStatus($estateId, $statusMap[$action]);
            }

            header('Location: ' . BASE_URL . '/admin/biens');
            exit;
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $this->render(V_ADMIN . 'v_admin_estate.html.php', [
            'pageTitle'  => 'Gestion des biens - Admin',
            'estates'    => (new EstateModel())->findJoinedAll(),
            'csrf_token' => $_SESSION['csrf_token'],
        ]);
    }
}
