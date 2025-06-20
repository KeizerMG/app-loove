<?php require APPROOT . '/views/includes/header.php'; ?>

<style>
    .admin-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    
    .admin-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .admin-header h1 {
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }
    
    .admin-nav {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    
    .admin-nav-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .admin-nav-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        color: inherit;
    }
    
    .admin-nav-card .icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .admin-nav-card h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .admin-nav-card p {
        font-size: 0.9rem;
        color: #666;
        margin: 0;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 45px rgba(0, 0, 0, 0.15);
    }
    
    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.5rem;
        color: white;
    }
    
    .stat-card:nth-child(1) .stat-icon {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }
    
    .stat-card:nth-child(2) .stat-icon {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }
    
    .stat-card:nth-child(3) .stat-icon {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }
    
    .stat-card:nth-child(4) .stat-icon {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 1rem;
        color: #666;
        font-weight: 500;
    }
    
    .welcome-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 3rem;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .welcome-section h2 {
        font-size: 2rem;
        color: #333;
        margin-bottom: 1rem;
    }
    
    .welcome-section p {
        font-size: 1.1rem;
        color: #666;
        line-height: 1.6;
    }
    
    @media (max-width: 768px) {
        .admin-nav {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
        
        .admin-header h1 {
            font-size: 2rem;
        }
    }
</style>

<div class="admin-page">
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-crown"></i> Panneau d'Administration</h1>
            <p>Tableau de bord de gestion de l'application Loove</p>
        </div>
        
        <div class="admin-nav">
            <a href="<?php echo BASEURL; ?>/admin/users" class="admin-nav-card">
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Gestion des Utilisateurs</h3>
                <p>Gérer les comptes utilisateurs, suspensions et bannissements</p>
            </a>
            
            <a href="<?php echo BASEURL; ?>/admin/revenue" class="admin-nav-card">
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Revenus</h3>
                <p>Voir les statistiques de revenus et transactions</p>
            </a>
            
            <a href="<?php echo BASEURL; ?>/admin/subscriptions" class="admin-nav-card">
                <div class="icon">
                    <i class="fas fa-crown"></i>
                </div>
                <h3>Abonnements</h3>
                <p>Gérer les plans d'abonnement et leurs prix</p>
            </a>
            
            <a href="<?php echo BASEURL; ?>/admin/reports" class="admin-nav-card">
                <div class="icon">
                    <i class="fas fa-flag"></i>
                </div>
                <h3>Signalements</h3>
                <p>Traiter les signalements d'utilisateurs</p>
            </a>
            
            <a href="<?php echo BASEURL; ?>/admin/statistics" class="admin-nav-card">
                <div class="icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Statistiques</h3>
                <p>Analyses détaillées et métriques de l'application</p>
            </a>
            
            <a href="<?php echo BASEURL; ?>/admin/settings" class="admin-nav-card">
                <div class="icon">
                    <i class="fas fa-cog"></i>
                </div>
                <h3>Paramètres</h3>
                <p>Configuration générale de l'application</p>
            </a>
        </div>

    
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo isset($data['stats']['total_users']) ? number_format($data['stats']['total_users']) : '0'; ?></div>
                <div class="stat-label">Utilisateurs Total</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stat-number"><?php echo isset($data['stats']['new_users_today']) ? $data['stats']['new_users_today'] : '0'; ?></div>
                <div class="stat-label">Nouveaux Aujourd'hui</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-euro-sign"></i>
                </div>
                <div class="stat-number"><?php echo isset($data['stats']['revenue_this_month']) ? number_format($data['stats']['revenue_this_month'], 0) : '0'; ?>€</div>
                <div class="stat-label">Revenus ce Mois</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="stat-number"><?php echo isset($data['stats']['active_subscriptions']) ? $data['stats']['active_subscriptions'] : '0'; ?></div>
                <div class="stat-label">Abonnements Actifs</div>
            </div>
        </div>
        
        <div class="welcome-section">
            <h2>Bienvenue dans l'interface d'administration</h2>
            <p>
                Vous avez maintenant accès à toutes les fonctionnalités de gestion de l'application Loove. 
                Utilisez les différentes sections ci-dessus pour gérer les utilisateurs, surveiller les revenus, 
                traiter les signalements et consulter les statistiques détaillées.
            </p>
            <p style="margin-top: 1rem;">
                <strong>Version actuelle :</strong> Loove Admin v1.0 | 
                <strong>Dernière mise à jour :</strong> <?php echo date('d/m/Y H:i'); ?>
            </p>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>
                    <i class="fas fa-flag"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-number"><?php echo $data['stats']['top_selling_plan']; ?></div>
                    <div class="stat-label">Plan le plus vendu</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
        </div>
    </div>

   
    <div class="chart-grid">
        <div class="chart-card">
            <h3>Revenus des 30 derniers jours</h3>
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>
        
        <div class="chart-card">
            <h3>Activités récentes</h3>
            <div class="recent-activity">
                <h4 style="color: var(--color-primary); margin-bottom: 1rem;">Nouveaux utilisateurs</h4>
                <?php foreach($data['recent_activities']['recent_users'] as $user) : ?>
                <div class="activity-item">
                    <div class="activity-avatar">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title"><?php echo $user->first_name . ' ' . $user->last_name; ?></div>
                        <div class="activity-time"><?php echo date('d/m/Y H:i', strtotime($user->created_at)); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <h4 style="color: var(--color-secondary); margin: 1.5rem 0 1rem;">Nouveaux abonnements</h4>
                <?php foreach($data['recent_activities']['recent_subscriptions'] as $sub) : ?>
                <div class="activity-item">
                    <div class="activity-avatar" style="background: rgba(16, 185, 129, 0.1); color: var(--color-secondary);">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title"><?php echo $sub->first_name . ' ' . $sub->last_name; ?> - <?php echo $sub->plan_name; ?></div>
                        <div class="activity-time"><?php echo date('d/m/Y H:i', strtotime($sub->created_at)); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
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
