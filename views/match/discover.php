<div class="container py-8">
    <?php if(isset($_GET['error'])): ?>
        <div class="bg-danger bg-opacity-10 text-danger p-4 rounded-md mb-6">
            <?php
                $error = $_GET['error'];
                switch($error) {
                    case 'invalid_user':
                        echo 'Invalid user selected.';
                        break;
                    default:
                        echo 'An error occurred.';
                }
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['match']) && $_GET['match'] == 1): ?>
        <div class="match-notification bg-gradient-primary text-white p-6 rounded-lg mb-6 text-center">
            <h2 class="text-2xl font-bold mb-2">It's a Match! 🎉</h2>
            <p>You both liked each other!</p>
            <div class="mt-4">
                <a href="<?= APP_URL ?>/matches" class="btn bg-white text-primary">View Matches</a>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if(empty($potentialMatches)): ?>
        <div class="discover-empty">
            <div class="discover-empty-icon">🔍</div>
            <h2 class="text-2xl font-bold mb-3">No more profiles to discover</h2>
            <p class="text-gray mb-6">We're looking for more people to match with you. Check back soon!</p>
            <a href="<?= APP_URL ?>/" class="btn btn-primary">Go Home</a>
        </div>
    <?php else: ?>
        <div class="discover-container">
            <div class="discover-stack" id="discover-stack">
                <?php 
                $displayCount = min(count($potentialMatches), 5);
                for ($i = 0; $i < $displayCount; $i++): 
                    $profile = $potentialMatches[$i];
                ?>
                    <div class="discover-card" data-user-id="<?= $profile['id'] ?>">
                        <div class="discover-overlay like">Like</div>
                        <div class="discover-overlay pass">Pass</div>
                        
                
                        <div class="discover-images">
                            <?php if(!empty($profile['profile_picture'])): ?>
                                <img src="<?= APP_URL . '/' . $profile['profile_picture'] ?>" alt="Profile" class="discover-main-image">
                            <?php else: ?>
                                <div class="discover-default-image">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                      
                        <div class="discover-info">
                            <div class="flex justify-between items-center mb-2">
                                <h2 class="text-2xl font-bold">
                                    <?= htmlspecialchars($profile['first_name']) ?>, <?= $profile['age'] ?>
                                </h2>
                                <?php if(!empty($profile['location'])): ?>
                                    <div class="text-gray">
                                        <i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($profile['location']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="my-3">
                                <?php if(!empty($profile['bio'])): ?>
                                    <p><?= nl2br(htmlspecialchars($profile['bio'])) ?></p>
                                <?php else: ?>
                                    <p class="text-gray italic">No bio provided</p>
                                <?php endif; ?>
                            </div>
                            
                            <?php if(!empty($profile['relationship_type'])): ?>
                                <div class="relationship-badge">
                                    <?php 
                                        switch($profile['relationship_type']) {
                                            case 'friendship': echo '<span class="badge bg-info">Looking for friendship</span>'; break;
                                            case 'casual': echo '<span class="badge bg-warning">Looking for casual</span>'; break;
                                            case 'serious': echo '<span class="badge bg-success">Looking for serious</span>'; break;
                                            case 'marriage': echo '<span class="badge bg-primary">Looking for marriage</span>'; break;
                                        }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        
            <div class="discover-actions">
                <button class="discover-action-btn pass" id="pass-btn">
                    <i class="fas fa-times"></i>
                </button>
                <button class="discover-action-btn superlike" id="superlike-btn">
                    <i class="fas fa-star"></i>
                </button>
                <button class="discover-action-btn like" id="like-btn">
                    <i class="fas fa-heart"></i>
                </button>
            </div>
        </div>
        
       
        <form id="pass-form" action="<?= APP_URL ?>/match/pass" method="POST" class="hidden">
            <input type="hidden" name="user_id" id="pass-user-id" value="">
        </form>
        
        <form id="like-form" action="<?= APP_URL ?>/match/like" method="POST" class="hidden">
            <input type="hidden" name="user_id" id="like-user-id" value="">
        </form>
    <?php endif; ?>
</div>

<style>
.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 9999px;
    color: white;
    margin-right: 0.5rem;
}
.bg-info { background-color: var(--info); }
.bg-warning { background-color: var(--warning); }
.bg-success { background-color: var(--success); }
.bg-primary { background-color: var(--primary); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log("Discover page loaded");
    
  
    const stack = document.getElementById('discover-stack');
    if (!stack) return;
    
    const cards = Array.from(stack.querySelectorAll('.discover-card'));
    if (cards.length === 0) return;
    
    const passBtn = document.getElementById('pass-btn');
    const likeBtn = document.getElementById('like-btn');
    const superlikeBtn = document.getElementById('superlike-btn');
    
    const passForm = document.getElementById('pass-form');
    const likeForm = document.getElementById('like-form');
    
    
    let currentCardIndex = 0;
    let currentCard = cards[currentCardIndex];
    
    let isDragging = false;
    let startX, startY, currentX, currentY;
    
  
    initializeCards();
    
   
    cards.forEach(card => {
   
        card.addEventListener('mousedown', handleDragStart);
        
      
        card.addEventListener('touchstart', handleDragStart, { passive: false });
    });
    
    document.addEventListener('mousemove', handleDrag);
    document.addEventListener('mouseup', handleDragEnd);
    document.addEventListener('touchmove', handleDrag, { passive: false });
    document.addEventListener('touchend', handleDragEnd);
    
    
    if (passBtn) passBtn.addEventListener('click', () => swipeCard('left'));
    if (likeBtn) likeBtn.addEventListener('click', () => swipeCard('right'));
    if (superlikeBtn) superlikeBtn.addEventListener('click', () => swipeCard('up'));
    
    function initializeCards() {
        cards.forEach((card, index) => {
        
            if (index === currentCardIndex) {
                card.style.zIndex = '5';
                card.style.transform = '';
                card.style.opacity = '1';
            } else if (index === currentCardIndex + 1) {
                card.style.zIndex = '4';
                card.style.transform = 'scale(0.95) translateY(10px)';
                card.style.opacity = '0.9';
            } else if (index === currentCardIndex + 2) {
                card.style.zIndex = '3';
                card.style.transform = 'scale(0.9) translateY(20px)';
                card.style.opacity = '0.7';
            } else {
                card.style.zIndex = '1';
                card.style.transform = 'scale(0.85) translateY(30px)';
                card.style.opacity = '0.5';
            }
        });
    }
    
    function handleDragStart(e) {
    
        if (cards[currentCardIndex] !== this) return;
        
        isDragging = true;
        this.classList.add('dragging');
        
      
        if (e.type === 'touchstart') {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        } else {
            startX = e.clientX;
            startY = e.clientY;
        }
        
        e.preventDefault();
    }
    
    function handleDrag(e) {
        if (!isDragging) return;
        
      
        if (e.type === 'touchmove') {
            currentX = e.touches[0].clientX;
            currentY = e.touches[0].clientY;
        } else {
            currentX = e.clientX;
            currentY = e.clientY;
        }
        
      
        const deltaX = currentX - startX;
        const deltaY = currentY - startY;
        
      
        const card = cards[currentCardIndex];
        card.style.transform = `translate(${deltaX}px, ${deltaY}px) rotate(${deltaX * 0.1}deg)`;
        
     
        if (deltaX > 50) {
            card.classList.add('swiping-right');
            card.classList.remove('swiping-left');
        } else if (deltaX < -50) {
            card.classList.add('swiping-left');
            card.classList.remove('swiping-right');
        } else {
            card.classList.remove('swiping-left', 'swiping-right');
        }
        
        e.preventDefault();
    }
    
    function handleDragEnd(e) {
        if (!isDragging) return;
        
        isDragging = false;
        const card = cards[currentCardIndex];
        card.classList.remove('dragging');
        
    
        let endX;
        if (e.type === 'touchend') {
            endX = e.changedTouches[0].clientX;
        } else {
            endX = e.clientX;
        }
        
        const deltaX = endX - startX;
        
        if (deltaX > 100) {
            swipeCard('right');
        } else if (deltaX < -100) {
            swipeCard('left');
        } else {
          
            card.style.transform = '';
            card.classList.remove('swiping-left', 'swiping-right');
        }
    }
    
    function swipeCard(direction) {
        const card = cards[currentCardIndex];
        if (!card) return;
        
        const userId = card.getAttribute('data-user-id');
        
       
        if (direction === 'left') {
            card.classList.add('swiped-left');
            passForm.querySelector('#pass-user-id').value = userId;
            setTimeout(() => passForm.submit(), 300);
        } else if (direction === 'right') {
            card.classList.add('swiped-right');
            likeForm.querySelector('#like-user-id').value = userId;
            setTimeout(() => likeForm.submit(), 300);
        } else if (direction === 'up') {
            card.classList.add('swiped-up');
        
            likeForm.querySelector('#like-user-id').value = userId;
            setTimeout(() => likeForm.submit(), 300);
        }
        
        
        setTimeout(() => {
            currentCardIndex++;
            if (currentCardIndex < cards.length) {
                initializeCards();
            }
        }, 300);
    }
});
</script>
