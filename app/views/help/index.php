<?php require APPROOT . '/views/includes/header.php'; ?>

<style>
    .help-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .help-card {
        background-color: var(--color-surface);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .help-card h2 {
        margin-top: 0;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        color: var(--color-text-primary);
    }
    
    .faq-item {
        margin-bottom: 1.5rem;
    }
    
    .faq-question {
        font-weight: 600;
        margin-bottom: 0.5rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .faq-question i {
        transition: var(--transition-normal);
    }
    
    .faq-question.active i {
        transform: rotate(180deg);
    }
    
    .faq-answer {
        display: none;
        padding: 0.5rem 0 0.5rem 1.5rem;
        color: var(--color-text-secondary);
        border-left: 2px solid var(--color-primary-soft);
        margin-left: 0.5rem;
    }
    
    .faq-answer.show {
        display: block;
    }
    
    .contact-form {
        margin-top: 2rem;
    }
</style>

<div class="help-section slide-up">
    <div style="margin-bottom: 2rem; text-align: center;">
        <h1 class="section-title-loove">Centre d'aide</h1>
        <p class="section-subtitle-loove">Trouvez des réponses à vos questions et obtenez de l'aide</p>
    </div>
    
    <div class="help-container">
        <div class="help-card">
            <h2>Questions fréquemment posées</h2>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Comment fonctionne le système de match ?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Lorsque vous "likez" ou "superlikez" un profil, et que cette personne vous "like" ou "superlike" en retour, un match est créé. Vous pouvez alors commencer à discuter avec cette personne dans l'onglet Messages.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Comment modifier mon profil ?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Vous pouvez modifier votre profil en cliquant sur "Mon Profil" dans le menu, puis sur "Modifier mon profil". Vous pourrez alors mettre à jour vos informations personnelles, votre bio, vos photos, etc.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Quels sont les avantages des abonnements premium ?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Les abonnements premium offrent plusieurs avantages comme des likes illimités, des Super Likes supplémentaires, la possibilité de voir qui vous a liké, et bien plus encore. Consultez la page d'abonnement pour plus de détails sur chaque forfait.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Comment supprimer mon compte ?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Vous pouvez supprimer votre compte dans les paramètres, section "Compte", en bas de page dans la "Zone dangereuse". Attention, cette action est irréversible et entraînera la perte de toutes vos données.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Comment signaler un utilisateur ?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Vous pouvez signaler un utilisateur en visitant son profil et en cliquant sur le bouton "Signaler" en bas de page. Nous examinerons votre signalement dans les plus brefs délais.</p>
                </div>
            </div>
        </div>
        
        <div class="help-card">
            <h2>Besoin d'aide supplémentaire ?</h2>
            <p>N'hésitez pas à nous contacter si vous avez des questions ou des problèmes.</p>
            
            <div class="contact-form">
                <div class="form-group-loove">
                    <label for="subject" class="form-label-loove">Sujet</label>
                    <select id="subject" class="form-control-loove">
                        <option value="">Sélectionnez un sujet</option>
                        <option value="account">Problème de compte</option>
                        <option value="billing">Facturation et abonnement</option>
                        <option value="technical">Problème technique</option>
                        <option value="other">Autre question</option>
                    </select>
                </div>
                
                <div class="form-group-loove">
                    <label for="message" class="form-label-loove">Votre message</label>
                    <textarea id="message" class="form-control-loove" rows="5" placeholder="Décrivez votre problème ou posez votre question..."></textarea>
                </div>
                
                <div class="form-group-loove">
                    <button type="button" class="btn-loove btn-loove-primary">
                        <i class="fas fa-paper-plane"></i> Envoyer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script pour afficher/masquer les réponses FAQ
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', function() {
            this.classList.toggle('active');
            const answer = this.nextElementSibling;
            answer.classList.toggle('show');
        });
    });
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?>
