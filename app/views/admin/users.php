<?php require APPROOT . '/views/includes/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="admin-container">
      
        <div class="dashboard-header">
            <div>
                <h1><i class="fas fa-users-cog"></i> Gestion des Utilisateurs</h1>
                <p>Gérez les comptes utilisateurs, suspensions et bannissements</p>
            </div>
            <div class="admin-actions">
                <a href="<?php echo BASEURL; ?>/adminDashboard" class="btn-admin">
                    <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                </a>
            </div>
        </div>

        <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 2rem;">
            <div class="stat-card">
                <div class="stat-icon" style="background: #6366F1;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo number_format($data['total_users']); ?></div>
                    <div class="stat-label">Total Utilisateurs</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: #10B981;">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">
                        <?php 
                        $activeUsers = 0;
                        foreach($data['users'] as $user) {
                            $isSuspended = isset($user->is_suspended) ? $user->is_suspended : 0;
                            $isBanned = isset($user->is_banned) ? $user->is_banned : 0;
                            if(!$isSuspended && !$isBanned) $activeUsers++;
                        }
                        echo $activeUsers;
                        ?>
                    </div>
                    <div class="stat-label">Utilisateurs Actifs</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: #F59E0B;">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">
                        <?php 
                        $suspendedUsers = 0;
                        foreach($data['users'] as $user) {
                            $isSuspended = isset($user->is_suspended) ? $user->is_suspended : 0;
                            if($isSuspended) $suspendedUsers++;
                        }
                        echo $suspendedUsers;
                        ?>
                    </div>
                    <div class="stat-label">Utilisateurs Suspendus</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: #EF4444;">
                    <i class="fas fa-user-slash"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">
                        <?php 
                        $bannedUsers = 0;
                        foreach($data['users'] as $user) {
                            $isBanned = isset($user->is_banned) ? $user->is_banned : 0;
                            if($isBanned) $bannedUsers++;
                        }
                        echo $bannedUsers;
                        ?>
                    </div>
                    <div class="stat-label">Utilisateurs Bannis</div>
                </div>
            </div>
        </div>

      
        <div class="admin-filters" style="background: white; padding: 1.5rem; border-radius: 16px; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
            <form method="GET" action="<?php echo BASEURL; ?>/adminDashboard/users" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                <input type="text" name="search" placeholder="Rechercher par nom ou email..." style="flex: 1; min-width: 200px; padding: 0.75rem; border: 1px solid #E5E7EB; border-radius: 8px;">
                
                <select name="status" style="padding: 0.75rem; border: 1px solid #E5E7EB; border-radius: 8px;">
                    <option value="">Tous les statuts</option>
                    <option value="active">Actifs</option>
                    <option value="suspended">Suspendus</option>
                    <option value="banned">Bannis</option>
                    <option value="admin">Administrateurs</option>
                </select>
                
                <button type="submit" class="btn-admin" style="padding: 0.75rem 1.5rem;">
                    <i class="fas fa-search"></i> Filtrer
                </button>
            </form>
        </div>

        <div style="background: white; border-radius: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: rgba(0, 0, 0, 0.02);">
                        <tr>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #1F2937; border-bottom: 1px solid #E5E7EB;">Utilisateur</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #1F2937; border-bottom: 1px solid #E5E7EB;">Email</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #1F2937; border-bottom: 1px solid #E5E7EB;">Statut</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #1F2937; border-bottom: 1px solid #E5E7EB;">Inscription</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #1F2937; border-bottom: 1px solid #E5E7EB;">Abonnements</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #1F2937; border-bottom: 1px solid #E5E7EB;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['users'])) : ?>
                            <?php foreach($data['users'] as $user) : ?>
                            <tr style="transition: background-color 0.3s ease;">
                                <td style="padding: 1rem; border-bottom: 1px solid #E5E7EB;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <img src="<?php echo BASEURL; ?>/img/profiles/<?php echo $user->profile_pic; ?>" 
                                             alt="Avatar" 
                                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"
                                             onerror="this.src='<?php echo BASEURL; ?>/img/profiles/default.jpg'">
                                        <div>
                                            <div style="font-weight: 600; color: #1F2937;">
                                                <?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?>
                                            </div>
                                            <div style="font-size: 0.875rem; color: #6B7280;">
                                                ID: <?php echo $user->id; ?>
                                                <?php if($user->is_admin) : ?>
                                                    <span style="color: #6366F1; font-weight: 500;"> • Admin</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td style="padding: 1rem; border-bottom: 1px solid #E5E7EB; color: #6B7280;">
                                    <?php echo htmlspecialchars($user->email); ?>
                                </td>
                                
                                <td style="padding: 1rem; border-bottom: 1px solid #E5E7EB;">
                                    <?php 
                                    $isBanned = isset($user->is_banned) ? $user->is_banned : 0;
                                    $isSuspended = isset($user->is_suspended) ? $user->is_suspended : 0;
                                    
                                    if($isBanned) : ?>
                                        <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.75rem; background: rgba(239, 68, 68, 0.1); color: #EF4444; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                            <i class="fas fa-ban"></i> Banni
                                        </span>
                                    <?php elseif($isSuspended) : ?>
                                        <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.75rem; background: rgba(245, 158, 11, 0.1); color: #F59E0B; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                            <i class="fas fa-pause"></i> Suspendu
                                        </span>
                                    <?php else : ?>
                                        <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.75rem; background: rgba(16, 185, 129, 0.1); color: #10B981; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                            <i class="fas fa-check"></i> Actif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <td style="padding: 1rem; border-bottom: 1px solid #E5E7EB; color: #6B7280; font-size: 0.875rem;">
                                    <?php echo date('d/m/Y', strtotime($user->created_at)); ?>
                                </td>
                                
                                <td style="padding: 1rem; border-bottom: 1px solid #E5E7EB;">
                                    <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.75rem; background: rgba(99, 102, 241, 0.1); color: #6366F1; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                        <i class="fas fa-crown"></i> <?php echo $user->subscription_count; ?>
                                    </span>
                                </td>
                                
                                <td style="padding: 1rem; border-bottom: 1px solid #E5E7EB; text-align: center;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                        <?php if(!$user->is_admin) : ?>
                                            <?php 
                                            $isSuspended = isset($user->is_suspended) ? $user->is_suspended : 0;
                                            $isBanned = isset($user->is_banned) ? $user->is_banned : 0;
                                            ?>
                                            
                                            <?php if($isBanned) : ?>
                                                
                                                <form method="POST" action="<?php echo BASEURL; ?>/adminDashboard/unbanUser/<?php echo $user->id; ?>" style="display: inline;">
                                                    <button type="submit" 
                                                            style="padding: 0.5rem; background: #6366F1; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.75rem;"
                                                            title="Débannir l'utilisateur"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir débannir cet utilisateur ?')">
                                                        <i class="fas fa-user-check"></i> Débannir
                                                    </button>
                                                </form>
                                            <?php else : ?>
                                                
                                                <?php if(!$isSuspended) : ?>
                                                    <form method="POST" action="<?php echo BASEURL; ?>/adminDashboard/toggleUserStatus/<?php echo $user->id; ?>" style="display: inline;">
                                                        <button type="submit" 
                                                                style="padding: 0.5rem; background: #F59E0B; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.75rem;"
                                                                title="Suspendre"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir suspendre cet utilisateur ?')">
                                                            <i class="fas fa-pause"></i>
                                                        </button>
                                                    </form>
                                                <?php else : ?>
                                                    <form method="POST" action="<?php echo BASEURL; ?>/adminDashboard/toggleUserStatus/<?php echo $user->id; ?>" style="display: inline;">
                                                        <button type="submit" 
                                                                style="padding: 0.5rem; background: #10B981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.75rem;"
                                                                title="Réactiver"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir réactiver cet utilisateur ?')">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                
                                               
                                                <button onclick="showBanModal(<?php echo $user->id; ?>, '<?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?>')" 
                                                        style="padding: 0.5rem; background: #EF4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.75rem;"
                                                        title="Bannir">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            
                                            <span style="color: #6B7280; font-size: 0.75rem;">Administrateur</span>
                                        <?php endif; ?>
                                        
                                        
                                        <a href="<?php echo BASEURL; ?>/profiles/show/<?php echo $user->id; ?>" 
                                           style="padding: 0.5rem; background: #6366F1; color: white; border: none; border-radius: 6px; text-decoration: none; font-size: 0.75rem;"
                                           title="Voir le profil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" style="padding: 3rem; text-align: center; color: #6B7280;">
                                    <i class="fas fa-users fa-3x" style="margin-bottom: 1rem; opacity: 0.3;"></i>
                                    <div>Aucun utilisateur trouvé</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

       
        <?php if($data['total_pages'] > 1) : ?>
        <div style="display: flex; justify-content: center; margin-top: 2rem; gap: 0.5rem;">
            <?php for($i = 1; $i <= $data['total_pages']; $i++) : ?>
                <a href="<?php echo BASEURL; ?>/adminDashboard/users/<?php echo $i; ?>" 
                   style="padding: 0.75rem 1rem; background: <?php echo ($i == $data['current_page']) ? '#6366F1' : 'white'; ?>; color: <?php echo ($i == $data['current_page']) ? 'white' : '#6B7280'; ?>; border-radius: 8px; text-decoration: none; border: 1px solid #E5E7EB;">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</div>


<div id="banModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 2rem; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 1rem; color: #EF4444;">
            <i class="fas fa-ban"></i> Bannir un utilisateur
        </h3>
        <p style="margin-bottom: 1.5rem; color: #6B7280;">
            Vous êtes sur le point de bannir <strong id="banUserName"></strong>. Cette action peut être réversible.
        </p>
        
        <form id="banForm" method="POST" action="">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Raison du bannissement :</label>
                <textarea name="ban_reason" 
                          style="width: 100%; padding: 0.75rem; border: 1px solid #E5E7EB; border-radius: 8px; min-height: 100px;"
                          placeholder="Expliquez la raison du bannissement..."></textarea>
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="closeBanModal()" 
                        style="padding: 0.75rem 1.5rem; background: #6B7280; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    Annuler
                </button>
                <button type="submit" 
                        style="padding: 0.75rem 1.5rem; background: #EF4444; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    <i class="fas fa-ban"></i> Bannir
                </button>
            </div>
        </form>
    </div>
</div>

<script>

function showBanModal(userId, userName) {
    document.getElementById('banUserName').textContent = userName;
    document.getElementById('banForm').action = '<?php echo BASEURL; ?>/adminDashboard/banUser/' + userId;
    
    const modal = document.getElementById('banModal');
    modal.style.display = 'flex';
    
    
    setTimeout(() => {
        modal.style.opacity = '1';
    }, 10);
}

function closeBanModal() {
    const modal = document.getElementById('banModal');
    modal.style.opacity = '0';
    
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}


document.getElementById('banModal').addEventListener('click', function(e) {
    if(e.target === this) {
        closeBanModal();
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(0, 0, 0, 0.02)';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>

<?php require APPROOT . '/views/includes/admin_footer.php'; ?>
