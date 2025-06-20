<?php require APPROOT . '/views/includes/header.php'; ?>

<style>
    .payment-history-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .subscription-summary {
        background-color: var(--color-surface);
        border-radius: var(--border-radius-lg);
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-md);
    }
    
    .subscription-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .subscription-details {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
    }
    
    .subscription-detail {
        flex: 1;
        min-width: 200px;
    }
    
    .subscription-label {
        font-size: 0.9rem;
        color: var(--color-text-secondary);
        margin-bottom: 0.25rem;
    }
    
    .subscription-value {
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .payment-table {
        width: 100%;
        border-collapse: collapse;
        background-color: var(--color-surface);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }
    
    .payment-table th, 
    .payment-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--color-divider);
    }
    
    .payment-table th {
        background-color: var(--color-surface-variant);
        font-weight: 600;
        color: var(--color-text-secondary);
    }
    
    .payment-status {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: var(--border-radius-sm);
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-completed {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--color-secondary);
    }
    
    .status-pending {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--color-warning);
    }
    
    .status-failed {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--color-error);
    }
    
    .no-payments {
        text-align: center;
        padding: 3rem 2rem;
        background-color: var(--color-surface);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
    }
</style>

<section class="payment-history-section slide-up">
    <div class="payment-history-container">
        <a href="<?php echo BASEURL; ?>/subscriptions" class="btn-loove btn-loove-outline" style="margin-bottom: 1.5rem;">
            <i class="fas fa-arrow-left"></i> Retour aux abonnements
        </a>
        
        <h1 class="section-title-loove" style="text-align: center; margin-bottom: 2rem;">Historique des paiements</h1>
        
        <?php if(isset($data['active_subscription']) && $data['active_subscription']) : 
            $endDate = new DateTime($data['active_subscription']->end_date);
            $startDate = new DateTime($data['active_subscription']->start_date);
            $now = new DateTime();
            $daysLeft = $now->diff($endDate)->days;
        ?>
        <div class="subscription-summary">
            <div class="subscription-header">
                <h3>Abonnement actuel: <span style="color: var(--color-primary);"><?php echo $data['active_subscription']->plan_name; ?></span></h3>
            </div>
            <div class="subscription-details">
                <div class="subscription-detail">
                    <div class="subscription-label">Date de début</div>
                    <div class="subscription-value"><?php echo $startDate->format('d/m/Y'); ?></div>
                </div>
                <div class="subscription-detail">
                    <div class="subscription-label">Date d'expiration</div>
                    <div class="subscription-value"><?php echo $endDate->format('d/m/Y'); ?></div>
                </div>
                <div class="subscription-detail">
                    <div class="subscription-label">Temps restant</div>
                    <div class="subscription-value"><?php echo $daysLeft; ?> jours</div>
                </div>
                <div class="subscription-detail">
                    <div class="subscription-label">Statut</div>
                    <div class="subscription-value">
                        <span class="payment-status status-completed">Actif</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if(!empty($data['payment_history'])) : ?>
        <table class="payment-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Plan</th>
                    <th>Montant</th>
                    <th>Méthode</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['payment_history'] as $payment) : 
                    $statusClass = '';
                    switch($payment->status) {
                        case 'completed':
                            $statusClass = 'status-completed';
                            $statusText = 'Complété';
                            break;
                        case 'pending':
                            $statusClass = 'status-pending';
                            $statusText = 'En attente';
                            break;
                        case 'failed':
                            $statusClass = 'status-failed';
                            $statusText = 'Échoué';
                            break;
                        default:
                            $statusClass = '';
                            $statusText = ucfirst($payment->status);
                    }
                    
                    $paymentDate = new DateTime($payment->created_at);
                ?>
                <tr>
                    <td><?php echo $paymentDate->format('d/m/Y H:i'); ?></td>
                    <td><?php echo $payment->plan_name ?? 'Plan inconnu'; ?></td>
                    <td><?php echo number_format($payment->amount, 2); ?>€</td>
                    <td><?php echo $payment->payment_method; ?></td>
                    <td><span class="payment-status <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else : ?>
        <div class="no-payments">
            <i class="fas fa-receipt fa-3x" style="color: var(--color-text-tertiary); margin-bottom: 1.5rem;"></i>
            <h3>Aucun historique de paiement</h3>
            <p style="color: var(--color-text-secondary);">Vous n'avez pas encore effectué de paiement.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require APPROOT . '/views/includes/footer.php'; ?>
