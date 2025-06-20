<?php require APPROOT . '/views/includes/header.php'; ?>

<style>
    .pricing-section {
        padding: 2rem 0;
    }
    
    .pricing-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .pricing-card {
        background-color: var(--color-surface);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: var(--transition-normal);
        border: 1px solid var(--color-divider);
        width: 100%;
        max-width: 320px;
        display: flex;
        flex-direction: column;
    }
    
    .pricing-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
    }
    
    .pricing-card.popular {
        border: 2px solid var(--color-primary);
        transform: scale(1.05);
        z-index: 1;
    }
    
    .pricing-card.popular:hover {
        transform: scale(1.05) translateY(-10px);
    }
    
    .pricing-card.current {
        border: 2px solid var(--color-secondary);
    }
    
    .pricing-header {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        color: white;
        padding: 1.5rem;
        text-align: center;
        position: relative;
    }
    
    .popular-badge {
        position: absolute;
        top: 0;
        right: 0;
        background-color: var(--color-tertiary);
        color: white;
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-bottom-left-radius: var(--border-radius-md);
    }
    
    .current-badge {
        position: absolute;
        top: 0;
        left: 0;
        background-color: var(--color-secondary);
        color: white;
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-bottom-right-radius: var(--border-radius-md);
    }
    
    .pricing-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .pricing-price {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }
    
    .pricing-period {
        font-size: 0.9rem;
        opacity: 0.8;
    }
    
    .pricing-body {
        padding: 2rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .pricing-description {
        color: var(--color-text-secondary);
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .pricing-features {
        list-style: none;
        padding: 0;
        margin: 0 0 1.5rem 0;
        flex-grow: 1;
    }
    
    .pricing-feature {
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--color-divider);
        display: flex;
        align-items: center;
    }
    
    .pricing-feature:last-child {
        border-bottom: none;
    }
    
    .pricing-feature i {
        color: var(--color-primary);
        margin-right: 0.75rem;
        font-size: 1.1rem;
    }
    
    .pricing-cta {
        text-align: center;
        margin-top: auto;
    }
    
    .current-plan-info {
        background-color: var(--color-surface);
        border-radius: var(--border-radius-lg);
        padding: 1.5rem;
        margin-bottom: 3rem;
        box-shadow: var(--shadow-md);
        text-align: center;
        border: 1px solid var(--color-secondary);
    }
    
    .current-plan-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .current-plan-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--color-secondary);
        margin: 0;
    }
    
    .plan-expiry {
        background-color: var(--color-surface-variant);
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-full);
        font-size: 0.9rem;
    }
    
    @media (max-width: 768px) {
        .pricing-card.popular {
            transform: none;
        }
        
        .pricing-card.popular:hover {
            transform: translateY(-10px);
        }
    }
</style>

<div class="pricing-section slide-up">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 class="section-title-loove">Choisissez votre forfait</h1>
        <p class="section-subtitle-loove">Découvrez nos différentes options d'abonnement et choisissez celle qui vous correspond le mieux</p>
    </div>
    
    <?php flash('subscription_success'); ?>
    <?php flash('subscription_error'); ?>
    
    <?php if(isset($data['active_subscription']) && $data['active_subscription']) : 
        $endDate = new DateTime($data['active_subscription']->end_date);
        $now = new DateTime();
        $daysLeft = $now->diff($endDate)->days;
    ?>
    <div class="current-plan-info">
        <div class="current-plan-header">
            <h3 class="current-plan-title">Votre forfait actuel : <?php echo $data['active_subscription']->plan_name; ?></h3>
            <div class="plan-expiry">
                <i class="far fa-calendar-alt"></i> Expire le <?php echo $endDate->format('d/m/Y'); ?> 
                (<?php echo $daysLeft; ?> jours restants)
            </div>
        </div>
        <p>Profitez de tous les avantages de votre abonnement premium et augmentez vos chances de trouver l'amour !</p>
        <a href="<?php echo BASEURL; ?>/subscriptions/history" class="btn-loove btn-loove-outline">
            <i class="fas fa-history"></i> Voir mon historique de paiement
        </a>
    </div>
    <?php endif; ?>
    
    <div class="pricing-container">
        <?php if(isset($data['plans']) && is_array($data['plans'])) : ?>
            <?php foreach($data['plans'] as $index => $plan) : 
                // Vérifier correctement si l'utilisateur a un abonnement actif et si c'est ce plan
                $isCurrentPlan = isset($data['active_subscription']) && $data['active_subscription'] && 
                                isset($data['active_subscription']->plan_id) && $plan->id == $data['active_subscription']->plan_id;
                $isPopular = $plan->name == 'Loove Gold'; // Le plan Gold est marqué comme populaire
                
                // S'assurer que features est défini
                $planFeatures = [];
                if(isset($plan->features) && !empty($plan->features)) {
                    $planFeatures = explode(';', $plan->features);
                }
            ?>
            <div class="pricing-card <?php echo $isPopular ? 'popular' : ''; ?> <?php echo $isCurrentPlan ? 'current' : ''; ?>">
                <div class="pricing-header">
                    <?php if($isPopular) : ?>
                        <div class="popular-badge">Le plus populaire</div>
                    <?php endif; ?>
                    
                    <?php if($isCurrentPlan) : ?>
                        <div class="current-badge">Forfait actuel</div>
                    <?php endif; ?>
                    
                    <h3 class="pricing-title"><?php echo $plan->name; ?></h3>
                    <div class="pricing-price">
                        <?php if($plan->price == 0) : ?>
                            0€
                        <?php else : ?>
                            <?php echo number_format($plan->price, 2); ?>€
                        <?php endif; ?>
                    </div>
                    <div class="pricing-period">
                        <?php if($plan->price == 0) : ?>
                            à vie
                        <?php else : ?>
                            par mois
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="pricing-body">
                    <p class="pricing-description"><?php echo isset($plan->description) ? $plan->description : ''; ?></p>
                    
                    <ul class="pricing-features">
                        <?php if(!empty($planFeatures)) : ?>
                            <?php foreach($planFeatures as $feature) : ?>
                            <li class="pricing-feature">
                                <i class="fas fa-check-circle"></i>
                                <span><?php echo $feature; ?></span>
                            </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li class="pricing-feature">
                                <i class="fas fa-info-circle"></i>
                                <span>Détails non disponibles</span>
                            </li>
                        <?php endif; ?>
                    </ul>
                    
                    <div class="pricing-cta">
                        <?php if($isCurrentPlan) : ?>
                            <button class="btn-loove btn-loove-secondary" disabled>
                                <i class="fas fa-check"></i> Forfait actif
                            </button>
                        <?php else : ?>
                            <?php if($plan->price == 0) : ?>
                                <a href="<?php echo BASEURL; ?>/subscriptions/activateFree" class="btn-loove btn-loove-outline">
                                    Activer gratuitement
                                </a>
                            <?php else : ?>
                                <a href="<?php echo BASEURL; ?>/subscriptions/checkout/<?php echo $plan->id; ?>" class="btn-loove <?php echo $isPopular ? 'btn-loove-primary' : 'btn-loove-outline'; ?>">
                                    <?php echo $isPopular ? 'Choisir ce forfait' : 'S\'abonner'; ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div style="text-align: center; width: 100%; padding: 2rem;">
                <p>Aucun plan d'abonnement n'est disponible pour le moment. Veuillez réessayer ultérieurement.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div style="text-align: center; margin-top: 3rem;">
        <h3>Besoin d'aide pour choisir ?</h3>
        <p>N'hésitez pas à nous contacter si vous avez des questions sur nos forfaits.</p>
        <a href="<?php echo BASEURL; ?>/pages/contact" class="btn-loove btn-loove-outline">
            <i class="fas fa-question-circle"></i> Contacter le support
        </a>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>
