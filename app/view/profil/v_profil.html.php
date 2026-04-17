<section class="profile-main">
    <h1 class="visually-hidden">Page profil de l'utilisateur</h1>
    <!-- Profile hero -->
    <div class="profile-hero">
        <div class="avatar"><?= htmlspecialchars($initial) ?></div>
        <h2><?= htmlspecialchars($pseudo) ?></h2>
        <p class="rank-label">⭐ Joueur</p>
    </div>

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
                <dd class="pstat-value">—</dd>
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

    <!-- Bottom actions -->
    <p class="profile-actions">
        <a href="<?= BASE_URL ?>/auth?action=logout" class="btn-danger">Déconnexion</a>
    </p>

</section>

<div id="profil-data" hidden
    data-history="<?= htmlspecialchars(json_encode(array_values($history ?? []), JSON_HEX_TAG)) ?>"
    data-base-url="<?= htmlspecialchars($baseUrl) ?>"></div>