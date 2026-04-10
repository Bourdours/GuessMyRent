<div class="profile-main">
    <!-- Profile hero -->
    <div class="profile-hero">
        <div class="avatar"><?= htmlspecialchars($initial) ?></div>
        <h2><?= htmlspecialchars($pseudo) ?></h2>
        <div class="rank-label">⭐ Joueur</div>
    </div>

    <!-- Stats band -->
    <div class="profile-stats">
        <div class="profile-stats-inner">
            <div>
                <div class="pstat-value"><?= $totalGames ?></div>
                <div class="pstat-label">Parties</div>
            </div>
            <div>
                <div class="pstat-value"><?= $avgScore ?></div>
                <div class="pstat-label">Score moyen</div>
            </div>
            <div>
                <div class="pstat-value"><?= $totalScore ?></div>
                <div class="pstat-label">Total pts</div>
            </div>
            <div>
                <div class="pstat-value">—</div>
                <div class="pstat-label">Classement</div>
            </div>
        </div>
    </div>

    <!-- History -->
    <div class="profile-content">
        <div class="profile-section">
            <h3>Historique des parties</h3>

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
                                    <td><?= htmlspecialchars($game['city']) ?></td>
                                    <td><?= htmlspecialchars($game['square_meters']) ?> m²</td>
                                    <td><?= number_format((int)$game['guess'], 0, ',', ' ') ?> €</td>
                                    <td><?= number_format((int)$game['rent'], 0, ',', ' ') ?> €</td>
                                    <td><span class="pill <?= $pillCls ?>"><?= number_format($gap, 1) ?> %</span></td>
                                    <td><strong><?= (int)$game['game_result'] ?> pts</strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bottom actions -->
    <div class="profile-actions">
        <a href="<?= BASE_URL ?>/auth?action=logout" class="btn-danger">Déconnexion</a>
    </div>

</div>