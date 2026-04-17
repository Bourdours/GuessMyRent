<div class="admin-layout">

    <?php require __DIR__ . '/v_admin_sidebar.html.php'; ?>

    <!-- Main -->
    <section class="admin-main">

        <header class="admin-header-bar">
            <h1>Gestion des messages</h1>
        </header>

        <?php require V_SKELETON . 'v_alerts.html.php'; ?>

        <!-- Liste des parties -->
        <section class="admin-table-wrap">
            <header class="admin-table-header">
                <h2>Messages <span class="pill pill-purple"><?= count($message ?? []) ?></span></h2>
            </header>

            <?php if (empty($message)): ?>
                <p class="admin-empty-state">Aucun message enregistré.</p>
            <?php else: ?>
                <div class="table-overflow">
                    <table class="admin-table message-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Objet</th>
                                <th>Contenu</th>
                                <th>Utilisateur</th>
                                <th>Action rapide</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($message as $m): ?>
                                <tr onclick="selectMessage(<?= (int)$m['id_message'] ?>)" data-id="<?= (int)$m['id_message'] ?>">
                                    <td><?= (int)$m['id_message'] ?></td>
                                    <td><?= $m['email'] !== null ? htmlspecialchars($m['email']) : '<em>Anonyme</em>' ?></td>
                                    <td><?= $m['objet'] !== null ? htmlspecialchars($m['objet']) : '—' ?></td>
                                    <td><?= $m['content'] !== null ? htmlspecialchars(mb_strimwidth($m['content'], 0, 33, '…')) : '—' ?></td>
                                    <td><?= $m['pseudo'] !== null ? htmlspecialchars($m['pseudo']) : '<em>Anonyme</em>' ?></td>
                                    <td class="td-actions" onclick="event.stopPropagation()">
                                        <form method="POST" action="<?= BASE_URL ?>/admin/messagerie" class="form-inline"
                                            onsubmit="return confirm('Supprimer définitivement ce message ?')">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                            <input type="hidden" name="message_id" value="<?= (int)$m['id_message'] ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <!-- Détail d'une partie -->
        <section class="admin-table-wrap" id="messageDetail" style="display:none;">
            <header class="admin-table-header">
                <h2>Détail du message</h2>
            </header>

            <div class="depose-detail-grid">
                <div class="depose-field">
                    <span class="depose-field-label">ID</span>
                    <span class="depose-field-value" id="mdet-id">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Email</span>
                    <span class="depose-field-value" id="mdet-email">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Objet</span>
                    <span class="depose-field-value" id="mdet-objet">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Contenu</span>
                    <span class="depose-field-value" id="mdet-content">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Pseudo</span>
                    <span class="depose-field-value" id="mdet-user">—</span>
                </div>
            </div>

            <div class="depose-actions">
                <form method="POST" action="<?= BASE_URL ?>/admin/messagerie" class="form-inline" id="mform-delete"
                    onsubmit="return confirm('Supprimer définitivement ce message ?')" style="display:none;">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <input type="hidden" name="message_id" id="mact-id-delete" value="">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn-danger">Supprimer</button>
                </form>
            </div>
        </section>

    </section>
</div>

<div id="admin-data" hidden
    data-page="message-list"
    data-message="<?= htmlspecialchars(json_encode(array_values($message ?? []), JSON_HEX_TAG)) ?>"></div>