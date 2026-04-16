<section class="profile-main">
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
                <dd class="pstat-value"><?= $totalGames ?></dd>
                <dt class="pstat-label">Parties</dt>
            </div>
            <div>
                <dd class="pstat-value"><?= $avgScore ?></dd>
                <dt class="pstat-label">Score moyen</dt>
            </div>
            <div>
                <dd class="pstat-value"><?= $totalScore ?></dd>
                <dt class="pstat-label">Total pts</dt>
            </div>
            <div>
                <dd class="pstat-value">—</dd>
                <dt class="pstat-label">Classement</dt>
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
                <div class="table-overflow">
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
                                <tr>
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

    <!-- Bottom actions -->
    <p class="profile-actions">
        <a href="<?= BASE_URL ?>/auth?action=logout" class="btn-danger">Déconnexion</a>
    </p>

</section>