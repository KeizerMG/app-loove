<?php require APPROOT . '/views/includes/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="admin-container">
        <div class="dashboard-header">
            <div>
                <h1><i class="fas fa-crown"></i> Tableau de Bord Administrateur</h1>
                <p>Bienvenue, <?php echo $_SESSION['admin_name']; ?> | Dernière connexion: <?php echo date('d/m/Y H:i'); ?></p>
            </div>
            <div class="admin-actions">
                <button class="btn-admin refresh-stats" onclick="refreshStats()">
                    <i class="fas fa-sync-alt"></i> Actualiser
                </button>
                <a href="<?php echo BASEURL; ?>" target="_blank" class="btn-admin">
                    <i class="fas fa-external-link-alt"></i> Voir le site
                </a>
                <a href="<?php echo BASEURL; ?>/adminAuth/logout" class="btn-admin btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>        
        <div class="stats-grid">
            <div class="stat-card users">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" id="total-users"><?php echo number_format($data['stats']['total_users']); ?></div>
                    <div class="stat-label">Utilisateurs Total</div>
                    <div class="stat-change">
                        +<?php echo $data['stats']['new_users_today']; ?> aujourd'hui
                    </div>
                </div>
            </div>

            <div class="stat-card revenue">
                <div class="stat-icon">
                    <i class="fas fa-euro-sign"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" id="revenue-today"><?php echo number_format($data['stats']['revenue_today'], 2); ?>€</div>
                    <div class="stat-label">Revenus Aujourd'hui</div>
                    <div class="stat-change">
                        <?php echo number_format($data['stats']['revenue_this_month'], 2); ?>€ ce mois
                    </div>
                </div>
            </div>

            <div class="stat-card subscriptions">
                <div class="stat-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" id="active-subscriptions"><?php echo $data['stats']['active_subscriptions']; ?></div>
                    <div class="stat-label">Abonnements Actifs</div>
                    <div class="stat-change">
                        Plan populaire: <?php echo $data['stats']['top_selling_plan']; ?>
                    </div>
                </div>
            </div>

            <div class="stat-card total-revenue">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo number_format($data['stats']['revenue_total'], 0); ?>€</div>
                    <div class="stat-label">Revenus Total</div>
                    <div class="stat-change">
                        Depuis le lancement
                    </div>
                </div>
            </div>
        </div>        
        <div class="quick-nav">
            <a href="<?php echo BASEURL; ?>/adminDashboard/users" class="nav-card">
                <div class="nav-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h3>Gestion Utilisateurs</h3>
                <p><?php echo $data['stats']['total_users']; ?> utilisateurs inscrits</p>
            </a>

            <a href="<?php echo BASEURL; ?>/adminDashboard/subscriptions" class="nav-card">
                <div class="nav-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <h3>Abonnements</h3>
                <p><?php echo $data['stats']['active_subscriptions']; ?> abonnements actifs</p>
            </a>

            <a href="<?php echo BASEURL; ?>/adminDashboard/revenue" class="nav-card">
                <div class="nav-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Revenus</h3>
                <p><?php echo number_format($data['stats']['revenue_this_month'], 0); ?>€ ce mois</p>
            </a>

            <div class="nav-card">
                <div class="nav-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Sécurité</h3>
                <p><?php echo $data['stats']['banned_users']; ?> utilisateurs bannis</p>
            </div>
        </div>        <div class="charts-grid">
            <div class="chart-container">
                <h3>Revenus des 30 derniers jours</h3>
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>

            <div class="chart-container">
                <h3>Nouveaux utilisateurs</h3>
                <canvas id="usersChart" width="400" height="200"></canvas>
            </div>
        </div>        
        <div class="recent-activities">
            <div class="activity-section">
                <h3><i class="fas fa-user-plus"></i> Derniers utilisateurs inscrits</h3>
                <div class="activity-list">
                    <?php foreach($data['recent_activities']['recent_users'] as $user): ?>
                    <div class="activity-item">
                        <div class="activity-avatar">
                            <img src="<?php echo BASEURL; ?>/img/profiles/<?php echo $user->profile_pic; ?>" alt="Avatar" onerror="this.src='<?php echo BASEURL; ?>/img/profiles/default.jpg'">
                        </div>
                        <div class="activity-content">
                            <div class="activity-title"><?php echo $user->first_name . ' ' . $user->last_name; ?></div>
                            <div class="activity-time"><?php echo date('d/m/Y H:i', strtotime($user->created_at)); ?></div>
                        </div>
                        <div class="activity-actions">
                            <a href="<?php echo BASEURL; ?>/adminDashboard/users" class="btn-sm">Voir</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="activity-section">
                <h3><i class="fas fa-crown"></i> Derniers abonnements</h3>
                <div class="activity-list">
                    <?php foreach($data['recent_activities']['recent_subscriptions'] as $sub): ?>
                    <div class="activity-item">
                        <div class="activity-avatar subscription">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title"><?php echo $sub->first_name . ' ' . $sub->last_name; ?></div>
                            <div class="activity-meta"><?php echo $sub->plan_name; ?></div>
                            <div class="activity-time"><?php echo date('d/m/Y H:i', strtotime($sub->created_at)); ?></div>
                        </div>
                        <div class="activity-actions">
                            <span class="badge success">Actif</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: [
                <?php 
                foreach($data['chart_data']['revenue_last_30_days'] as $day) {
                    echo "'" . date('d/m', strtotime($day->date)) . "',";
                }
                ?>
            ],
            datasets: [{
                label: 'Revenus (€)',
                data: [
                    <?php 
                    foreach($data['chart_data']['revenue_last_30_days'] as $day) {
                        echo $day->revenue . ',';
                    }
                    ?>
                ],
                borderColor: '#6366F1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + '€';
                        }
                    }
                }
            }
        }
    });    const usersCtx = document.getElementById('usersChart').getContext('2d');
    const usersChart = new Chart(usersCtx, {
        type: 'bar',
        data: {
            labels: [
                <?php 
                foreach($data['chart_data']['users_last_30_days'] as $day) {
                    echo "'" . date('d/m', strtotime($day->date)) . "',";
                }
                ?>
            ],
            datasets: [{
                label: 'Nouveaux utilisateurs',
                data: [
                    <?php 
                    foreach($data['chart_data']['users_last_30_days'] as $day) {
                        echo $day->count . ',';
                    }
                    ?>
                ],
                backgroundColor: '#10B981',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });    function refreshStats() {
        fetch('<?php echo BASEURL; ?>/adminDashboard/getStats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-users').textContent = new Intl.NumberFormat().format(data.total_users);
                document.getElementById('revenue-today').textContent = new Intl.NumberFormat('fr-FR', { 
                    style: 'currency', 
                    currency: 'EUR' 
                }).format(data.revenue_today);
                document.getElementById('active-subscriptions').textContent = data.active_subscriptions;                
                document.querySelectorAll('.stat-number').forEach(el => {
                    el.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        el.style.transform = 'scale(1)';
                    }, 200);
                });
            })
            .catch(error => console.error('Erreur:', error));
    }    
    setInterval(refreshStats, 30000);
</script>

<?php require APPROOT . '/views/includes/admin_footer.php'; ?>
    </div>
</div>    
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                <?php 
                foreach($data['chart_data']['revenue_last_30_days'] as $day) {
                    echo "'" . date('d/m', strtotime($day->date)) . "',";
                }
                ?>
            ],
            datasets: [{
                label: 'Revenus (€)',
                data: [
                    <?php 
                    foreach($data['chart_data']['revenue_last_30_days'] as $day) {
                        echo $day->revenue . ',';
                    }
                    ?>
                ],
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + '€';
                        }
                    }
                }
            }
        }
    });
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?>
