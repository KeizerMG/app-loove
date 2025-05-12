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
        <!-- Match Animation -->
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
                // Display up to 5 profiles in stack
                $displayCount = min(count($potentialMatches), 5);
                for ($i = 0; $i < $displayCount; $i++): 
                    $profile = $potentialMatches[$i];
                ?>
                    <div class="discover-card" data-user-id="<?= $profile['id'] ?>">
                        <!-- Decision overlays -->
                        <div class="discover-overlay like">Like</div>
                        <div class="discover-overlay pass">Pass</div>
                        
                        <!-- Profile images -->
                        <div class="discover-images">
                            <?php if(!empty($profile['profile_picture'])): ?>
                                <img src="<?= APP_URL . '/' . $profile['profile_picture'] ?>" alt="Profile" class="discover-main-image">
                            <?php else: ?>
                                <div class="discover-default-image">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Profile info -->
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
            
            <!-- Action buttons -->
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
        
        <!-- Hidden forms for actions -->
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
    
    // Get required elements
    const stack = document.getElementById('discover-stack');
    if (!stack) return;
    
    const cards = Array.from(stack.querySelectorAll('.discover-card'));
    if (cards.length === 0) return;
    
    const passBtn = document.getElementById('pass-btn');
    const likeBtn = document.getElementById('like-btn');
    const superlikeBtn = document.getElementById('superlike-btn');
    
    const passForm = document.getElementById('pass-form');
    const likeForm = document.getElementById('like-form');
    
    // Set up state variables
    let currentCardIndex = 0;
    let currentCard = cards[currentCardIndex];
    
    let isDragging = false;
    let startX, startY, currentX, currentY;
    
    // Initialize card positions
    initializeCards();
    
    // Add event listeners to all cards
    cards.forEach(card => {
        // Mouse events
        card.addEventListener('mousedown', handleDragStart);
        
        // Touch events
        card.addEventListener('touchstart', handleDragStart, { passive: false });
    });
    
    document.addEventListener('mousemove', handleDrag);
    document.addEventListener('mouseup', handleDragEnd);
    document.addEventListener('touchmove', handleDrag, { passive: false });
    document.addEventListener('touchend', handleDragEnd);
    
    // Add click event listeners to buttons
    if (passBtn) passBtn.addEventListener('click', () => swipeCard('left'));
    if (likeBtn) likeBtn.addEventListener('click', () => swipeCard('right'));
    if (superlikeBtn) superlikeBtn.addEventListener('click', () => swipeCard('up'));
    
    function initializeCards() {
        cards.forEach((card, index) => {
            // Apply initial styles based on position in stack
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
        // Only allow dragging the top card
        if (cards[currentCardIndex] !== this) return;
        
        isDragging = true;
        this.classList.add('dragging');
        
        // Get start position
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
        
        // Get current position
        if (e.type === 'touchmove') {
            currentX = e.touches[0].clientX;
            currentY = e.touches[0].clientY;
        } else {
            currentX = e.clientX;
            currentY = e.clientY;
        }
        
        // Calculate distance moved
        const deltaX = currentX - startX;
        const deltaY = currentY - startY;
        
        // Apply transform to card
        const card = cards[currentCardIndex];
        card.style.transform = `translate(${deltaX}px, ${deltaY}px) rotate(${deltaX * 0.1}deg)`;
        
        // Show decision overlays
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
        
        // Get final position
        let endX;
        if (e.type === 'touchend') {
            endX = e.changedTouches[0].clientX;
        } else {
            endX = e.clientX;
        }
        
        const deltaX = endX - startX;
        
        // Determine swipe direction
        if (deltaX > 100) {
            swipeCard('right');
        } else if (deltaX < -100) {
            swipeCard('left');
        } else {
            // Reset card position
            card.style.transform = '';
            card.classList.remove('swiping-left', 'swiping-right');
        }
    }
    
    function swipeCard(direction) {
        const card = cards[currentCardIndex];
        if (!card) return;
        
        const userId = card.getAttribute('data-user-id');
        
        // Add swipe animation
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
            // Super like would submit to a different form in a real app
            likeForm.querySelector('#like-user-id').value = userId;
            setTimeout(() => likeForm.submit(), 300);
        }
        
        // Move to next card after animation
        setTimeout(() => {
            currentCardIndex++;
            if (currentCardIndex < cards.length) {
                initializeCards();
            }
        }, 300);
    }
});
</script>
