<?php require APPROOT . '/views/includes/header.php'; ?>

<style>
    .discover-container {
        max-width: 500px;
        margin: 0 auto;
        position: relative;
    }
    
    .card-stack {
        position: relative;
        height: 600px;
        margin-bottom: 2rem;
    }
    
    .profile-card {
        position: absolute;
        width: 100%;
        height: 600px;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        transition: transform 0.5s ease, opacity 0.5s ease;
        cursor: grab;
        background-color: var(--color-surface);
        
        display: flex;
        flex-direction: column;
    }
    
    .profile-card.dragging {
        cursor: grabbing;
    }
    
    .profile-card:nth-child(1) {
        z-index: 5;
        transform: scale(1) translateY(0);
        opacity: 1;
    }
    
    .profile-card:nth-child(2) {
        z-index: 4;
        transform: scale(0.95) translateY(15px);
        opacity: 0.8;
    }
    
    .profile-card:nth-child(3) {
        z-index: 3;
        transform: scale(0.9) translateY(30px);
        opacity: 0.6;
    }
    
    .profile-card.swipe-left {
        transform: translateX(-1500px) rotate(-30deg);
        opacity: 0;
    }
    
    .profile-card.swipe-right {
        transform: translateX(1500px) rotate(30deg);
        opacity: 0;
    }
    
    .profile-card.swipe-up {
        transform: translateY(-1500px);
        opacity: 0;
    }
    
    .profile-photo {
        width: 100%;
        height: 70%;
        object-fit: cover;
        flex-grow: 1;
    }
    
    .profile-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2rem;
        background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0.2), transparent);
        color: white;
    }
    
    .profile-info h3 {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        color: white;
    }
    
    .profile-bio {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 1rem;
    }
    
    .profile-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.9rem;
        opacity: 0.8;
    }
    
    .profile-meta span {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .interaction-buttons {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 1rem;
    }
    
    .interaction-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: var(--shadow-md);
        cursor: pointer;
        transition: var(--transition-normal);
        border: none;
    }
    
    .interaction-btn:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: var(--shadow-lg);
    }
    
    .dislike-btn {
        color: var(--color-error);
    }
    
    .dislike-btn:hover {
        background-color: var(--color-error);
        color: white;
    }
    
    .like-btn {
        color: var(--color-primary);
    }
    
    .like-btn:hover {
        background-color: var(--color-primary);
        color: white;
    }
    
    .superlike-btn {
        color: var(--color-secondary);
    }
    
    .superlike-btn:hover {
        background-color: var(--color-secondary);
        color: white;
    }
    
    .info-btn {
        color: var(--color-text-primary);
    }
    
    .info-btn:hover {
        background-color: var(--color-text-primary);
        color: white;
    }
    
    .swipe-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 800;
        text-transform: uppercase;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    
    .swipe-overlay-left {
        color: var(--color-error);
        border: 10px solid var(--color-error);
        border-radius: var(--border-radius-lg);
        transform: rotate(-20deg);
    }
    
    .swipe-overlay-right {
        color: var(--color-primary);
        border: 10px solid var(--color-primary);
        border-radius: var(--border-radius-lg);
        transform: rotate(20deg);
    }
    
    .swipe-overlay-up {
        color: var(--color-secondary);
        border: 10px solid var(--color-secondary);
        border-radius: var(--border-radius-lg);
    }
    
    .swipe-instruction {
        text-align: center;
        color: var(--color-text-secondary);
        font-size: 0.9rem;
        margin-top: 1rem;
    }
    
    .no-profiles {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--color-surface);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
    }
    
    .match-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    
    .match-modal.active {
        opacity: 1;
        visibility: visible;
    }
    
    .match-modal-content {
        background-color: var(--color-surface);
        border-radius: var(--border-radius-lg);
        max-width: 500px;
        width: 90%;
        padding: 2rem;
        text-align: center;
        box-shadow: var(--shadow-lg);
        transform: scale(0.8);
        transition: transform 0.3s ease;
        position: relative;
    }
    
    .match-modal.active .match-modal-content {
        transform: scale(1);
    }
    
    .match-modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 1.5rem;
        color: var(--color-text-secondary);
        cursor: pointer;
        transition: var(--transition-normal);
    }
    
    .match-modal-close:hover {
        color: var(--color-primary);
    }
    
    .match-profiles {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin: 2rem 0;
    }
    
    .match-profile {
        text-align: center;
    }
    
    .match-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--color-primary);
    }
    
    .match-name {
        margin-top: 0.5rem;
        font-weight: 600;
    }
    
    .match-icon {
        font-size: 2rem;
        color: var(--color-primary);
    }
    
    .match-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: #f00;
        border-radius: 50%;
        animation: confetti-fall 5s ease forwards;
    }
    
    @keyframes confetti-fall {
        0% {
            transform: translateY(-100vh) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(720deg);
            opacity: 0;
        }
    }
</style>

<section class="discover-loove-section slide-up">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 class="section-title-loove"><?php echo htmlspecialchars($data['title']); ?></h1>
        <p class="section-subtitle-loove"><?php echo htmlspecialchars($data['description']); ?></p>
    </div>

    <?php flash('discover_message'); ?>

    <?php if(!empty($data['profilesToDiscover'])): ?>
        <div class="discover-container">
            <div class="card-stack" id="card-stack">
                <?php 
                for($i = 0; $i < min(5, count($data['profilesToDiscover'])); $i++): 
                    $profile = $data['profilesToDiscover'][$i];
                ?>
                <div class="profile-card" data-profile-id="<?php echo $profile->id; ?>">
                    <img src="<?php echo htmlspecialchars($profile->profile_pic); ?>" alt="Photo de <?php echo htmlspecialchars($profile->first_name); ?>" class="profile-photo">
                    <div class="profile-info">
                        <h3><?php echo htmlspecialchars($profile->first_name); ?>, <?php echo htmlspecialchars($profile->age); ?></h3>
                        <div class="profile-bio"><?php echo htmlspecialchars(substr($profile->bio ?? 'Aucune bio disponible.', 0, 150)); ?><?php echo (strlen($profile->bio ?? '') > 150) ? '...' : ''; ?></div>
                        <div class="profile-meta">
                            <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($profile->location ?? 'Non spécifié'); ?></span>
                            <span><i class="fas fa-heart"></i> <?php echo htmlspecialchars(ucfirst($profile->relationship_type ?? 'Non spécifié')); ?></span>
                        </div>
                    </div>
                    
                    <div class="swipe-overlay swipe-overlay-left">NON</div>
                    <div class="swipe-overlay swipe-overlay-right">LIKE</div>
                    <div class="swipe-overlay swipe-overlay-up">SUPER</div>
                </div>
                <?php endfor; ?>
                
               
                <div id="hidden-profiles" style="display: none;">
                    <?php 
                   
                    for($i = 5; $i < count($data['profilesToDiscover']); $i++): 
                        $profile = $data['profilesToDiscover'][$i];
                    ?>
                    <div class="profile-data" 
                         data-profile-id="<?php echo $profile->id; ?>"
                         data-first-name="<?php echo htmlspecialchars($profile->first_name); ?>"
                         data-age="<?php echo htmlspecialchars($profile->age); ?>"
                         data-bio="<?php echo htmlspecialchars($profile->bio ?? 'Aucune bio disponible.'); ?>"
                         data-location="<?php echo htmlspecialchars($profile->location ?? 'Non spécifié'); ?>"
                         data-relationship-type="<?php echo htmlspecialchars($profile->relationship_type ?? 'Non spécifié'); ?>"
                         data-profile-pic="<?php echo htmlspecialchars($profile->profile_pic); ?>">
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="interaction-buttons">
                <button class="interaction-btn dislike-btn" id="dislike-btn" title="Passer">
                    <i class="fas fa-times"></i>
                </button>
                <button class="interaction-btn info-btn" id="info-btn" title="Plus d'infos">
                    <i class="fas fa-info"></i>
                </button>
                <button class="interaction-btn like-btn" id="like-btn" title="J'aime">
                    <i class="fas fa-heart"></i>
                </button>
                <button class="interaction-btn superlike-btn" id="superlike-btn" title="Super like">
                    <i class="fas fa-star"></i>
                </button>
            </div>

            <p class="swipe-instruction">
                <i class="fas fa-long-arrow-alt-left"></i> Glissez à gauche pour passer
                <br>
                <i class="fas fa-long-arrow-alt-right"></i> Glissez à droite pour aimer
                <br>
                <i class="fas fa-long-arrow-alt-up"></i> Glissez vers le haut pour super-liker
            </p>
        </div>
    <?php else: ?>
        <div class="no-profiles">
            <i class="fas fa-search fa-3x" style="color: var(--color-tertiary); margin-bottom: 1.5rem;"></i>
            <h3 style="margin-bottom: 1rem;">Aucun nouveau profil à découvrir</h3>
            <p style="color: var(--color-text-secondary); max-width: 500px; margin: 0 auto 1.5rem;">Revenez plus tard pour découvrir de nouveaux profils.</p>
        </div>
    <?php endif; ?>
</section>


<div class="match-modal" id="matchModal">
    <div class="match-modal-content">
        <div class="match-modal-close" id="matchModalClose"><i class="fas fa-times"></i></div>
        
        <h2 style="color: var(--color-primary); font-size: 2.5rem; margin-bottom: 0.5rem;">C'est un Match !</h2>
        <p style="color: var(--color-text-secondary);">Vous avez matché avec <span id="matchName">cette personne</span></p>
        
        <div class="match-profiles">
            <div class="match-profile">
                <img src="<?php echo isset($_SESSION['user_profile_pic']) ? BASEURL . '/img/profiles/' . $_SESSION['user_profile_pic'] : BASEURL . '/img/profiles/default.jpg'; ?>" alt="Votre photo" class="match-avatar" id="userAvatar">
                <div class="match-name">Vous</div>
            </div>
            
            <div class="match-icon">
                <i class="fas fa-heart"></i>
            </div>
            
            <div class="match-profile">
                <img src="" alt="Photo du match" class="match-avatar" id="matchAvatar">
                <div class="match-name" id="matchNameDisplay"></div>
            </div>
        </div>
        
        <div class="match-actions">
            <a href="#" class="btn-loove btn-loove-primary" id="sendMessageBtn">
                <i class="fas fa-comment"></i> Envoyer un message
            </a>
            <button class="btn-loove btn-loove-secondary" id="continueDiscoveringBtn">
                <i class="fas fa-compass"></i> Continuer à découvrir
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cardStack = document.getElementById('card-stack');
    const hiddenProfiles = document.getElementById('hidden-profiles');
    const dislikeBtn = document.getElementById('dislike-btn');
    const likeBtn = document.getElementById('like-btn');
    const superlikeBtn = document.getElementById('superlike-btn');
    const infoBtn = document.getElementById('info-btn');
    

    const matchModal = document.getElementById('matchModal');
    const matchModalClose = document.getElementById('matchModalClose');
    const matchName = document.getElementById('matchName');
    const matchNameDisplay = document.getElementById('matchNameDisplay');
    const matchAvatar = document.getElementById('matchAvatar');
    const sendMessageBtn = document.getElementById('sendMessageBtn');
    const continueDiscoveringBtn = document.getElementById('continueDiscoveringBtn');
    
    let profileIndex = 5; 
    let cards = Array.from(cardStack.querySelectorAll('.profile-card'));
    let currentCard = cards[0];
    let startX, startY, moveX, moveY, initialX, initialY;
    let isSwiping = false;
    
    
    function initDraggable(card) {
        card.addEventListener('mousedown', dragStart);
        card.addEventListener('touchstart', dragStart, { passive: true });
        
        card.addEventListener('mouseup', dragEnd);
        card.addEventListener('touchend', dragEnd);
        
        card.addEventListener('mousemove', drag);
        card.addEventListener('touchmove', drag, { passive: true });
    }
    
   
    cards.forEach(initDraggable);
    
    
    function createProfileCard(profileData) {
        const newCard = document.createElement('div');
        newCard.className = 'profile-card';
        newCard.dataset.profileId = profileData.dataset.profileId;
        
        newCard.innerHTML = `
            <img src="${profileData.dataset.profilePic}" alt="Photo de ${profileData.dataset.firstName}" class="profile-photo">
            <div class="profile-info">
                <h3>${profileData.dataset.firstName}, ${profileData.dataset.age}</h3>
                <div class="profile-bio">${profileData.dataset.bio.substring(0, 150)}${profileData.dataset.bio.length > 150 ? '...' : ''}</div>
                <div class="profile-meta">
                    <span><i class="fas fa-map-marker-alt"></i> ${profileData.dataset.location}</span>
                    <span><i class="fas fa-heart"></i> ${profileData.dataset.relationshipType.charAt(0).toUpperCase() + profileData.dataset.relationshipType.slice(1)}</span>
                </div>
            </div>
            <div class="swipe-overlay swipe-overlay-left">NON</div>
            <div class="swipe-overlay swipe-overlay-right">LIKE</div>
            <div class="swipe-overlay swipe-overlay-up">SUPER</div>
        `;
        
        return newCard;
    }
    
    
    function loadNextProfile() {
        const hiddenProfilesData = hiddenProfiles.querySelectorAll('.profile-data');
        
        if (profileIndex < hiddenProfilesData.length) {
            const newCard = createProfileCard(hiddenProfilesData[profileIndex]);
            cardStack.appendChild(newCard);
            initDraggable(newCard);
            profileIndex++;
            
           
            cards = Array.from(cardStack.querySelectorAll('.profile-card'));
            currentCard = cards[0];
        }
    }
    
 
    function dragStart(e) {
        if (e.type === 'touchstart') {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        } else {
            startX = e.clientX;
            startY = e.clientY;
        }
        
        initialX = this.offsetLeft;
        initialY = this.offsetTop;
        
        if (e.target === this || this.contains(e.target)) {
            isSwiping = true;
            this.classList.add('dragging');
        }
    }
    
    function dragEnd(e) {
        if (!isSwiping) return;
        
        this.classList.remove('dragging');
        
        
        const deltaX = moveX - startX;
        const deltaY = moveY - startY;
        const absDeltaX = Math.abs(deltaX);
        const absDeltaY = Math.abs(deltaY);
        
        
        const threshold = 100;
        
        if (absDeltaX > threshold || absDeltaY > threshold) {
            const profileId = this.dataset.profileId;
            
            
            if (absDeltaX > absDeltaY) {
                if (deltaX > 0) {
                   
                    this.classList.add('swipe-right');
                    handleInteraction(profileId, 'like');
                } else {
               
                    this.classList.add('swipe-left');
                    handleInteraction(profileId, 'dislike');
                }
            } else {
                if (deltaY < 0) {
                   
                    this.classList.add('swipe-up');
                    handleInteraction(profileId, 'superlike');
                } else {
                    
                    resetCardPosition(this);
                }
            }
            
            
            if (this === currentCard) {
                prepareNextCard();
            }
        } else {
            
            resetCardPosition(this);
        }
        
        isSwiping = false;
    }
    
    function drag(e) {
        if (!isSwiping) return;
        
        e.preventDefault();
        
        if (e.type === 'touchmove') {
            moveX = e.touches[0].clientX;
            moveY = e.touches[0].clientY;
        } else {
            moveX = e.clientX;
            moveY = e.clientY;
        }
        
        
        const deltaX = moveX - startX;
        const deltaY = moveY - startY;
        
        
        this.style.transform = `translate(${deltaX}px, ${deltaY}px) rotate(${deltaX * 0.05}deg)`;
        
        
        updateOverlays(this, deltaX, deltaY);
    }
    
    
    function updateOverlays(card, deltaX, deltaY) {
        const absDeltaX = Math.abs(deltaX);
        const absDeltaY = Math.abs(deltaY);
        const overlayLeft = card.querySelector('.swipe-overlay-left');
        const overlayRight = card.querySelector('.swipe-overlay-right');
        const overlayUp = card.querySelector('.swipe-overlay-up');
        
        
        overlayLeft.style.opacity = '0';
        overlayRight.style.opacity = '0';
        overlayUp.style.opacity = '0';
        
        
        if (absDeltaX > absDeltaY) {
            if (deltaX < 0) {
                
                overlayLeft.style.opacity = Math.min(absDeltaX / 100, 1).toString();
            } else {
               
                overlayRight.style.opacity = Math.min(absDeltaX / 100, 1).toString();
            }
        } else {
            if (deltaY < 0) {
                
                overlayUp.style.opacity = Math.min(absDeltaY / 100, 1).toString();
            }
        }
    }
    
    function resetCardPosition(card) {
        card.style.transform = '';
     
        const overlays = card.querySelectorAll('.swipe-overlay');
        overlays.forEach(overlay => overlay.style.opacity = '0');
    }
    
   
    function prepareNextCard() {
        setTimeout(() => {
            
            cardStack.removeChild(currentCard);
            
           
            cards = Array.from(cardStack.querySelectorAll('.profile-card'));
            
         
            loadNextProfile();
          
            currentCard = cards[0];
        }, 300);
    }
    
  
    function handleInteraction(profileId, interactionType) {
        
        fetch(`<?php echo BASEURL; ?>/discover/interact/${profileId}/${interactionType}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(data => {
            console.log('Interaction enregistrée', data);
            
            
            if ((interactionType === 'like' || interactionType === 'superlike') && Math.random() < 0.2) {
                
                const matchedCard = currentCard;
                const matchedProfileId = matchedCard.dataset.profileId;
                let matchedProfileName = '';
                let matchedProfilePic = '';
                
                
                if (matchedCard) {
                    const nameElement = matchedCard.querySelector('h3');
                    const imgElement = matchedCard.querySelector('img');
                    
                    if (nameElement) {
                        
                        const fullName = nameElement.textContent;
                        matchedProfileName = fullName.split(',')[0].trim();
                    }
                    
                    if (imgElement) {
                        matchedProfilePic = imgElement.src;
                    }
                }
                
                
                showMatchModal(matchedProfileId, matchedProfileName, matchedProfilePic);
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'enregistrement de l\'interaction', error);
        });
    }
    
    function showMatchModal(profileId, name, profilePic) {
       
        matchName.textContent = name;
        matchNameDisplay.textContent = name;
        matchAvatar.src = profilePic;
        matchAvatar.alt = 'Photo de ' + name;
        
       
        sendMessageBtn.href = `<?php echo BASEURL; ?>/messages/with/${profileId}`;
        
        matchModal.classList.add('active');
        
      
        createConfetti();
    }
    
    
    function createConfetti() {
        const colors = ['#FF4081', '#7C5DFA', '#10B981', '#F59E0B', '#6366F1', '#EC4899'];
        const confettiCount = 100;
        
        for (let i = 0; i < confettiCount; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.width = Math.random() * 10 + 5 + 'px';
            confetti.style.height = confetti.style.width;
            confetti.style.animationDuration = Math.random() * 3 + 2 + 's';
            confetti.style.animationDelay = Math.random() * 2 + 's';
            
            matchModal.appendChild(confetti);
            
            
            setTimeout(() => {
                confetti.remove();
            }, 5000);
        }
    }
    
    
    matchModalClose.addEventListener('click', function() {
        matchModal.classList.remove('active');
    });
    
    continueDiscoveringBtn.addEventListener('click', function() {
        matchModal.classList.remove('active');
    });

    dislikeBtn.addEventListener('click', function() {
        if (currentCard) {
          
            currentCard.classList.add('swipe-left');
            handleInteraction(currentCard.dataset.profileId, 'dislike');
            prepareNextCard();
        }
    });
    
    likeBtn.addEventListener('click', function() {
        if (currentCard) {
          
            currentCard.classList.add('swipe-right');
            handleInteraction(currentCard.dataset.profileId, 'like');
            prepareNextCard();
        }
    });
    
    superlikeBtn.addEventListener('click', function() {
        if (currentCard) {
      
            currentCard.classList.add('swipe-up');
            handleInteraction(currentCard.dataset.profileId, 'superlike');
            prepareNextCard();
        }
    });
    
    infoBtn.addEventListener('click', function() {
        if (currentCard) {
            
            const profileId = currentCard.dataset.profileId;
            window.location.href = `<?php echo BASEURL; ?>/profiles/show/${profileId}`;
        }
    });
});
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?>
