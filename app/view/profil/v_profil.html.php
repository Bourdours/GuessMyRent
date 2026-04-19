<section class="profile-main">
    <h1 class="visually-hidden">Page profil de l'utilisateur</h1>
    <!-- Profile hero -->
    <div class="profile-hero">
        <div class="avatar"><?= htmlspecialchars($initial) ?></div>
        <h2><?= htmlspecialchars($pseudo) ?></h2>
        <p class="rank-label">⭐ Joueur</p>
    </div>

    <!-- Flash messages -->
    <?php if (!empty($flash_success)): ?>
        <div class="alert alert-success mb-md" role="status"><?= htmlspecialchars($flash_success) ?></div>
    <?php endif; ?>
    <?php if (!empty($flash_error)): ?>
        <div class="alert alert-error mb-md" role="alert"><?= htmlspecialchars($flash_error) ?></div>
    <?php endif; ?>

    <!-- Stats band -->
    <section class="profile-stats" aria-label="Statistiques du joueur">
        <dl class="profile-stats-inner">
            <div>
                <dt class="pstat-label">Parties</dt>
                <dd class="pstat-value"><?= $totalGames ?></dd>
            </div>
            <div>
                <dt class="pstat-label">Score moyen</dt>
                <dd class="pstat-value"><?= $avgScore ?></dd>
            </div>
            <div>
                <dt class="pstat-label">Total pts</dt>
                <dd class="pstat-value"><?= $totalScore ?></dd>
            </div>
            <div>
                <dt class="pstat-label">Classement</dt>
                <dd class="pstat-value"><?= $userRank !== null ? '#' . $userRank : '—' ?></dd>
            </div>
        </dl>
    </section>

    <!-- History -->
    <div class="profile-content">
        <section class="profile-section">
            <h3>Historique</h3>

            <?php if (empty($history)): ?>
                <div class="alert alert-info">
                    Aucune partie enregistrée. <a href="<?= BASE_URL ?>/jeu">&nbsp;Jouer maintenant →</a>
                </div>
            <?php else: ?>
                <div class="table-overflow <?= count($history) > 10 ? ' history-scroll' : '' ?>">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Ville</th>
                                <th>Surface</th>
                                <th>Estimation</th>
                                <th>Loyer réel</th>
                                <th>Écart</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $game):
                                $gap     = 100 - (int)$game['game_result'];
                                $pillCls = match (true) {
                                    $gap < 10  => 'pill-green',
                                    $gap < 20 => 'pill-gold',
                                    $gap < 35 => 'pill-amber',
                                    default   => 'pill-red',
                                };
                            ?>
                                <tr onclick="selectProfilGame(<?= (int)$game['id_game'] ?>)" data-id="<?= (int)$game['id_game'] ?>" style="cursor:pointer;">
                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($game['date']))) ?></td>
                                    <td><?= $game['city'] ? htmlspecialchars($game['city']) : '<em>Bien supprimé</em>' ?></td>
                                    <td><?= $game['square_meters'] ? htmlspecialchars($game['square_meters']) . ' m²' : '—' ?></td>
                                    <td><?= number_format((int)$game['guess'], 0, ',', ' ') ?> €</td>
                                    <td><?= $game['rent'] ? number_format((int)$game['rent'], 0, ',', ' ') . ' €' : '—' ?></td>
                                    <td><span class="pill <?= $pillCls ?>"><?= number_format($gap, 1) ?> %</span></td>
                                    <td><strong><?= (int)$game['game_result'] ?> pts</strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <!-- Classement général -->
    <?php if (!empty($leaderboard)): ?>
    <div class="profile-content">
        <section class="profile-section">
            <h3>Classement général</h3>
            <div class="table-overflow">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Joueur</th>
                            <th>Parties</th>
                            <th>Score moyen</th>
                            <th>Total pts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leaderboard as $i => $row):
                            $isMe = ($row['pseudo'] === $pseudo);
                        ?>
                        <tr <?= $isMe ? 'class="leaderboard-me"' : '' ?>>
                            <td><strong><?= $i + 1 ?></strong></td>
                            <td><?= htmlspecialchars($row['pseudo']) ?><?= $isMe ? ' <span class="pill pill-purple">Vous</span>' : '' ?></td>
                            <td><?= (int)$row['nb_parties'] ?></td>
                            <td><?= (int)$row['avg_score'] ?> pts</td>
                            <td><strong><?= number_format((int)$row['total_pts'], 0, ',', ' ') ?> pts</strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <?php endif; ?>

    <!-- Détail du bien sélectionné -->
    <div class="profile-content" id="profilEstateDetail" style="display:none;">
        <section class="profile-section">
            <header style="display:flex; justify-content:space-between; align-items:center;">
                <h3>Détail du bien</h3>
                <button type="button" onclick="closeProfilDetail()" class="btn-secondary btn-sm">Fermer</button>
            </header>

            <div class="depose-detail-grid">
                <div class="depose-field">
                    <span class="depose-field-label">Type</span>
                    <span class="depose-field-value" id="pdet-type">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Loyer</span>
                    <span class="depose-field-value" id="pdet-rent">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Charges</span>
                    <span class="depose-field-value" id="pdet-charges">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Surface</span>
                    <span class="depose-field-value" id="pdet-m2">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Pièces</span>
                    <span class="depose-field-value" id="pdet-room">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Chambres</span>
                    <span class="depose-field-value" id="pdet-chamber">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Étage</span>
                    <span class="depose-field-value" id="pdet-floor">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Adresse</span>
                    <span class="depose-field-value" id="pdet-adress">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Ville</span>
                    <span class="depose-field-value" id="pdet-city">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Description</span>
                    <span class="depose-field-value pdet-description-box" id="pdet-description">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Votre estimation</span>
                    <span class="depose-field-value" id="pdet-guess">—</span>
                </div>
                <div class="depose-field">
                    <span class="depose-field-label">Score</span>
                    <span class="depose-field-value" id="pdet-score">—</span>
                </div>
            </div>

            <div id="pdet-img-grid" class="depose-img-grid" style="margin-top:1rem;"></div>
        </section>
    </div>

    <!-- Actions bar -->
    <div class="profile-content">
        <section class="profile-section">

            <div class="profile-action-bar">
                <a href="<?= BASE_URL ?>/auth?action=logout" class="btn-danger">Déconnexion</a>
                <button type="button" class="btn-secondary" onclick="toggleProfilPanel('edit')">Modifier mon compte</button>
                <button type="button" class="btn-danger" onclick="toggleProfilPanel('delete')">Supprimer mon compte</button>
            </div>

            <!-- Étape : modifier -->
            <div id="profil-panel-edit" style="display:none;" class="profil-panel">
                <h3>Modifier mon profil</h3>
                <form method="POST" action="<?= BASE_URL ?>/profil/modifier">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                    <div class="edit-grid">
                        <div class="edit-field">
                            <label class="form-label" for="edit-pseudo">Pseudo</label>
                            <input class="form-input" type="text" id="edit-pseudo" name="pseudo"
                                   value="<?= htmlspecialchars($pseudo) ?>" required maxlength="50">
                        </div>

                        <div class="edit-field">
                            <label class="form-label" for="edit-email">Email</label>
                            <input class="form-input" type="email" id="edit-email" name="email"
                                   value="<?= htmlspecialchars($user['email'] ?? '') ?>" required maxlength="150">
                        </div>

                        <div class="edit-field">
                            <label class="form-label" for="edit-password">Nouveau mot de passe <small>(laisser vide = inchangé)</small></label>
                            <input class="form-input" type="password" id="edit-password" name="new_password"
                                   minlength="8" autocomplete="new-password">
                        </div>

                        <div class="edit-field">
                            <label class="form-label" for="edit-confirm">Confirmer le mot de passe</label>
                            <input class="form-input" type="password" id="edit-confirm" name="confirm_password"
                                   minlength="8" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="depose-actions">
                        <button type="submit" class="btn-primary">Enregistrer</button>
                        <button type="button" class="btn-secondary" onclick="toggleProfilPanel(null)">Annuler</button>
                    </div>
                </form>
            </div>

            <!-- Étape : supprimer -->
            <div id="profil-panel-delete" style="display:none;" class="profil-panel">
                <h3>Supprimer mon compte</h3>
                <p>La suppression est définitive. Vos parties et vos biens proposés sont conservés mais anonymisés.</p>
                <form method="POST" action="<?= BASE_URL ?>/profil/supprimer"
                      onsubmit="return confirm('Supprimer définitivement votre compte ?');">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <div class="depose-actions">
                        <button type="submit" class="btn-danger">Confirmer la suppression</button>
                        <button type="button" class="btn-secondary" onclick="toggleProfilPanel(null)">Annuler</button>
                    </div>
                </form>
            </div>

        </section>
    </div>

</section>

<div id="profil-data" hidden
    data-history="<?= htmlspecialchars(json_encode(array_values($history ?? []), JSON_HEX_TAG)) ?>"
    data-base-url="<?= htmlspecialchars($baseUrl) ?>"></div>