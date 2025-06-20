<?php
/**
 * Vérifier si un utilisateur peut utiliser une fonctionnalité premium
 */
function canUseFeature($userId, $feature) {
    $subscriptionModel = new Subscription();
    return $subscriptionModel->canUseFeature($userId, $feature);
}

/**
 * Vérifier si un utilisateur a un abonnement actif
 */
function hasActiveSubscription($userId) {
    $subscriptionModel = new Subscription();
    return $subscriptionModel->hasActiveSubscription($userId);
}
