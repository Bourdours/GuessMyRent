<?php

namespace GmR\controller;

use GmR\controller\BaseController;
use GmR\model\EstateModel;
use GmR\model\TypeModel;
use GmR\model\StatusModel;

class EstateController extends BaseController
{
    public function propose(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $result = $this->handlePropose();
            $this->refreshCsrf();
            $this->render(V_CONTACT . 'v_contact.html.php', array_merge(
                ['pageTitle' => 'Proposer un bien', 'csrf_token' => $_SESSION['csrf_token'], 'activeTab' => 'bien'],
                $result
            ));
            return;
        }

        $this->refreshCsrf();
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

    public function adminSent(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            $action   = $_POST['action'] ?? '';
            $estateId = (int) ($_POST['estate_id'] ?? 0);
            $model    = new EstateModel();

            if ($action === 'update') {
                $type = (new TypeModel())->findByLabel(trim($_POST['type_label'] ?? ''));
                if ($type) {
                    $images = $this->processImageSlots();
                    $model->update($estateId, [
                        'rent'                => (int) round((int) ($_POST['rent'] ?? 0) / 10) * 10,
                        'is_charges_included' => (int) ($_POST['is_charges_included'] ?? 0),
                        'adress'              => trim($_POST['adress'] ?? '') ?: null,
                        'city'                => trim($_POST['city'] ?? ''),
                        'postcode'            => trim($_POST['postcode'] ?? ''),
                        'gps_y'               => null,
                        'gps_x'               => null,
                        'square_meters'       => trim($_POST['square_meters'] ?? ''),
                        'room'                => $_POST['room'] !== '' ? (int) $_POST['room'] : null,
                        'chamber'             => $_POST['chamber'] !== '' ? (int) $_POST['chamber'] : null,
                        'floor'               => $_POST['floor'] !== '' ? (int) $_POST['floor'] : null,
                        'description'         => trim($_POST['description'] ?? '') ?: null,
                        'image1'              => $images['image1'],
                        'image2'              => $images['image2'],
                        'image3'              => $images['image3'],
                        'image4'              => $images['image4'],
                        'id_status'           => (int) ($_POST['id_status'] ?? 1),
                        'id_type'             => $type['id_type'],
                    ]);
                }
            } elseif ($action === 'delete') {
                $estate = $model->findById($estateId);
                if ($model->deleteToVoid($estateId) && $estate) {
                    $imgDir = IMG . 'estates/';
                    foreach (['image1', 'image2', 'image3', 'image4'] as $col) {
                        if (!empty($estate[$col])) {
                            $path = $imgDir . $estate[$col];
                            if (file_exists($path)) {
                                unlink($path);
                            }
                        }
                    }
                }
            } else {
                $statusMap = [
                    'activate'   => 2,
                    'archive'    => 3,
                    'correction' => 4,
                ];
                if (isset($statusMap[$action])) {
                    $model->updateStatus($estateId, $statusMap[$action]);
                }
            }

            header('Location: ' . BASE_URL . '/admin/biens/en-attente');
            exit;
        }

        $this->refreshCsrf();

        $this->render(V_ADMIN . 'v_admin_estate_sent.html.php', [
            'pageTitle'  => 'Biens déposés - Admin',
            'estates'    => (new EstateModel())->findInactive(),
            'types'      => (new TypeModel())->findAll(),
            'csrf_token' => $_SESSION['csrf_token'],
            'pageScript' => BASE_URL . '/public/js/admin.js',
        ]);
    }

    // Gestion admin des biens (liste + changement de statut)
    public function adminList(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            $action   = $_POST['action'] ?? '';
            $estateId = (int) ($_POST['estate_id'] ?? 0);
            $model    = new EstateModel();

            if ($action === 'update') {
                $type = (new TypeModel())->findByLabel(trim($_POST['type_label'] ?? ''));
                if ($type) {
                    $images = $this->processImageSlots();
                    $model->update($estateId, [
                        'rent'                => (int) round((int) ($_POST['rent'] ?? 0) / 10) * 10,
                        'is_charges_included' => (int) ($_POST['is_charges_included'] ?? 0),
                        'adress'              => trim($_POST['adress'] ?? '') ?: null,
                        'city'                => trim($_POST['city'] ?? ''),
                        'postcode'            => trim($_POST['postcode'] ?? ''),
                        'gps_y'               => null,
                        'gps_x'               => null,
                        'square_meters'       => trim($_POST['square_meters'] ?? ''),
                        'room'                => $_POST['room'] !== '' ? (int) $_POST['room'] : null,
                        'chamber'             => $_POST['chamber'] !== '' ? (int) $_POST['chamber'] : null,
                        'floor'               => $_POST['floor'] !== '' ? (int) $_POST['floor'] : null,
                        'description'         => trim($_POST['description'] ?? '') ?: null,
                        'image1'              => $images['image1'],
                        'image2'              => $images['image2'],
                        'image3'              => $images['image3'],
                        'image4'              => $images['image4'],
                        'id_status'           => (int) ($_POST['id_status'] ?? 1),
                        'id_type'             => $type['id_type'],
                    ]);
                }
            } elseif ($action === 'delete') {
                $estate = $model->findById($estateId);
                if ($model->deleteToVoid($estateId) && $estate) {
                    $imgDir = IMG . 'estates/';
                    foreach (['image1', 'image2', 'image3', 'image4'] as $col) {
                        if (!empty($estate[$col])) {
                            $file = $imgDir . basename($estate[$col]);
                            if (is_file($file)) {
                                unlink($file);
                            }
                        }
                    }
                }
            } else {
                $statusMap = [
                    'activate'   => 2,
                    'deactivate' => 1,
                    'archive'    => 3,
                    'correction' => 4,
                ];
                if (isset($statusMap[$action])) {
                    $model->updateStatus($estateId, $statusMap[$action]);
                }
            }

            header('Location: ' . BASE_URL . '/admin/biens');
            exit;
        }

        $this->refreshCsrf();

        $this->render(V_ADMIN . 'v_admin_estate.html.php', [
            'pageTitle'  => 'Gestion des biens - Admin',
            'estates'    => (new EstateModel())->findJoinedAll(),
            'types'      => (new TypeModel())->findAll(),
            'csrf_token' => $_SESSION['csrf_token'],
            'pageScript' => BASE_URL . '/public/js/admin.js',
        ]);
    }

    private function processImageSlots(): array
    {
        $imgDir    = IMG . 'estates/';
        $mimeToExt = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $finfo     = new \finfo(FILEINFO_MIME_TYPE);
        $slots     = [];

        for ($n = 1; $n <= 4; $n++) {
            $key    = 'image' . $n;
            $oldKey = 'old_image' . $n;
            $newKey = 'new_image' . $n;

            $current = trim($_POST[$key]    ?? '');
            $old     = trim($_POST[$oldKey] ?? '');

            $hasUpload = !empty($_FILES[$newKey]['tmp_name'])
                      && $_FILES[$newKey]['error'] === UPLOAD_ERR_OK;

            if ($hasUpload) {
                $tmpName = $_FILES[$newKey]['tmp_name'];
                $mime    = $finfo->file($tmpName);

                if (isset($mimeToExt[$mime])
                    && $_FILES[$newKey]['size'] <= 5 * 1024 * 1024
                    && getimagesize($tmpName) !== false
                ) {
                    $filename = uniqid('estate_', true) . '.' . $mimeToExt[$mime];
                    if (move_uploaded_file($tmpName, $imgDir . $filename)) {
                        if ($old !== '') {
                            $f = $imgDir . basename($old);
                            if (is_file($f)) unlink($f);
                        }
                        $slots[] = $filename;
                        continue;
                    }
                }
                $slots[] = $old ?: null;
            } elseif ($current === '' && $old !== '') {
                $f = $imgDir . basename($old);
                if (is_file($f)) unlink($f);
                $slots[] = null;
            } else {
                $slots[] = $current ?: null;
            }
        }

        $filled = array_values(array_filter($slots, fn($s) => $s !== null));

        if (empty($filled)) {
            $filled[] = trim($_POST['old_image1'] ?? '');
        }

        return [
            'image1' => $filled[0] ?? null,
            'image2' => $filled[1] ?? null,
            'image3' => $filled[2] ?? null,
            'image4' => $filled[3] ?? null,
        ];
    }
}
