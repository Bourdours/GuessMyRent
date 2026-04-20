<?php

namespace GmR\controller;

use GmR\controller\BaseController;
use GmR\model\ApiEstateModel;
use GmR\model\EstateModel;
use GmR\model\TypeModel;
use GmR\model\StatusModel;

class ApiController extends BaseController
{
    /** Admin: display the API import form and process estate imports */
    public function adminApi(): void
    {
        $this->requireAdmin();

        $apiModel    = new ApiEstateModel();
        // Pre-load already-imported IDs to disable them in the JS dropdown
        $importedIds = array_map('intval', $apiModel->findAllExternalIds());

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            $result      = $this->handleApiImport($apiModel);
            // Reload imported IDs so the newly imported one appears as disabled
            $importedIds = array_map('intval', $apiModel->findAllExternalIds());
            $this->refreshCsrf();

            $this->render(V_ADMIN . 'v_admin_api.html.php', array_merge([
                'pageTitle'        => 'Gestion des biens de l\'api',
                'csrf_token'       => $_SESSION['csrf_token'],
                'imported_api_ids' => $importedIds,
                'pageScript'       => BASE_URL . '/public/js/admin.js',
            ], $result));
            return;
        }

        $this->refreshCsrf();

        $this->render(V_ADMIN . 'v_admin_api.html.php', [
            'pageTitle'        => 'Gestion des biens de l\'api',
            'csrf_token'       => $_SESSION['csrf_token'],
            'imported_api_ids' => $importedIds,
            'pageScript'       => BASE_URL . '/public/js/admin.js',
        ]);
    }

    /** Validate, download images, and persist an estate from external API data; returns success or error array */
    private function handleApiImport(ApiEstateModel $apiModel): array
    {
        // Sanitize and cast all form inputs
        $apiId        = (int) ($_POST['api_id'] ?? 0);
        $rent         = (int) round((int) ($_POST['rent'] ?? 0) / 10) * 10;  // round to nearest 10
        $isCharges    = (int) ($_POST['is_charges_included'] ?? 0);
        $adress       = trim($_POST['adress'] ?? '') ?: null;
        $city         = trim($_POST['city'] ?? '');
        $postcode     = trim($_POST['postcode'] ?? '');
        $squareMeters = (float) str_replace(',', '.', $_POST['square_meters'] ?? '0');
        $roomRaw      = trim($_POST['room'] ?? '');
        $room         = $roomRaw !== '' ? (int) $roomRaw : null;
        $chamberRaw   = trim($_POST['chamber'] ?? '');
        $chamber      = $chamberRaw !== '' ? (int) $chamberRaw : null;
        $floorRaw     = trim($_POST['floor'] ?? '');
        $floor        = $floorRaw !== '' ? (int) $floorRaw : null;
        $description  = trim($_POST['description'] ?? '') ?: null;
        $typeLabel    = trim($_POST['type_label'] ?? '') ?: 'Autre';
        // Collect only non-empty image URLs (up to 4)
        $imageUrls    = array_values(array_filter([
            trim($_POST['image_url_1'] ?? ''),
            trim($_POST['image_url_2'] ?? ''),
            trim($_POST['image_url_3'] ?? ''),
            trim($_POST['image_url_4'] ?? ''),
        ]));

        // Guard: validate required fields before hitting the DB
        if ($apiId <= 0) {
            return ['error' => 'ID API invalide.'];
        }
        if ($apiModel->isImported($apiId)) {
            return ['error' => 'Ce bien (#' . $apiId . ') a déjà été importé.'];
        }
        if ($city === '' || $postcode === '' || $squareMeters <= 0 || $rent <= 0) {
            return ['error' => 'Ville, code postal, surface et loyer sont obligatoires.'];
        }
        if (empty($imageUrls)) {
            return ['error' => 'Au moins une image est requise.'];
        }

        // Find or create the estate type (API may introduce new labels)
        $typeModel = new TypeModel();
        $type      = $typeModel->findByLabel($typeLabel);
        $typeId    = $type ? (int) $type['id_type'] : $typeModel->create($typeLabel);

        // All imported estates start with "déposé" status (pending admin review)
        $status = (new StatusModel())->findByLabel('déposé');
        if (!$status) {
            return ['error' => 'Statut "déposé" introuvable en base.'];
        }

        // Download each image URL to a temp file, validate MIME, then move to estates dir
        $imageNames = [null, null, null, null];
        $mimeToExt  = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $finfo      = new \finfo(FILEINFO_MIME_TYPE);
        $ctx        = stream_context_create(['http' => ['timeout' => 10]]);

        foreach ($imageUrls as $i => $url) {
            if ($url === '') continue;

            $content = @file_get_contents($url, false, $ctx);
            if ($content === false) continue;  // skip unreachable URLs silently

            $tmp = tempnam(sys_get_temp_dir(), 'api_img_');
            file_put_contents($tmp, $content);
            $mime = $finfo->file($tmp);

            if (!isset($mimeToExt[$mime])) {
                unlink($tmp);
                continue;  // skip unsupported formats silently
            }

            $filename       = uniqid('estate_', true) . '.' . $mimeToExt[$mime];
            $dest           = IMG . 'estates/' . $filename;
            rename($tmp, $dest);
            chmod($dest, 0644);
            $imageNames[$i] = $filename;
        }

        $estateId = (new EstateModel())->create([
            'rent'                => $rent,
            'is_charges_included' => $isCharges,
            'adress'              => $adress,
            'city'                => $city,
            'postcode'            => $postcode,
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
            'id_status'           => (int) $status['id_status'],
            'id_type'             => $typeId,
            'id_user'             => (int) $_SESSION['user_id'],
        ]);

        $apiModel->create($apiId, $estateId);

        return ['success' => 'Le bien #' . $apiId . ' a été importé avec succès (ESTATE #' . $estateId . ').'];
    }
}
