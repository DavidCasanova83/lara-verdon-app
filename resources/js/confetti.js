/**
 * Confetti animation for form success
 */

// Simple confetti function without external dependencies
function launchConfetti() {
    // Create confetti container
    const confettiContainer = document.createElement('div');
    confettiContainer.style.position = 'fixed';
    confettiContainer.style.top = '0';
    confettiContainer.style.left = '0';
    confettiContainer.style.width = '100%';
    confettiContainer.style.height = '100%';
    confettiContainer.style.pointerEvents = 'none';
    confettiContainer.style.zIndex = '9999';
    confettiContainer.id = 'confetti-container-' + Date.now();
    
    document.body.appendChild(confettiContainer);
    
    // Colors for confetti (green theme for success)
    const colors = ['#10b981', '#22c55e', '#16a34a', '#15803d', '#84cc16', '#65a30d', '#a3e635'];
    
    // Create confetti pieces
    for (let i = 0; i < 80; i++) {
        createConfettiPiece(confettiContainer, colors[Math.floor(Math.random() * colors.length)]);
    }
    
    // Remove confetti after animation
    setTimeout(() => {
        if (confettiContainer && confettiContainer.parentNode) {
            confettiContainer.parentNode.removeChild(confettiContainer);
        }
    }, 4000);
}

function createConfettiPiece(container, color) {
    const confetti = document.createElement('div');
    confetti.style.position = 'absolute';
    confetti.style.pointerEvents = 'none';
    
    // Random shapes (circles, squares, stars)
    const shapes = ['circle', 'square', 'star'];
    const shape = shapes[Math.floor(Math.random() * shapes.length)];
    
    if (shape === 'circle') {
        confetti.style.width = '8px';
        confetti.style.height = '8px';
        confetti.style.borderRadius = '50%';
        confetti.style.backgroundColor = color;
    } else if (shape === 'square') {
        confetti.style.width = '6px';
        confetti.style.height = '6px';
        confetti.style.backgroundColor = color;
    } else if (shape === 'star') {
        confetti.style.width = '0';
        confetti.style.height = '0';
        confetti.style.borderLeft = '4px solid transparent';
        confetti.style.borderRight = '4px solid transparent';
        confetti.style.borderBottom = '8px solid ' + color;
        confetti.style.position = 'relative';
        confetti.style.transform = 'rotate(35deg)';
    }
    
    // Random starting position
    confetti.style.left = Math.random() * window.innerWidth + 'px';
    confetti.style.top = '-20px';
    
    // Random rotation
    const rotation = Math.random() * 360;
    
    container.appendChild(confetti);
    
    // Animation
    const fallTime = Math.random() * 3 + 2; // 2-5 seconds
    const horizontalMovement = (Math.random() - 0.5) * 300; // -150 to 150px
    const rotationSpeed = Math.random() * 360 + 180; // 180-540 degrees
    
    confetti.animate([
        {
            transform: `translate(0, 0) rotate(${rotation}deg)`,
            opacity: 1
        },
        {
            transform: `translate(${horizontalMovement}px, ${window.innerHeight + 50}px) rotate(${rotation + rotationSpeed}deg)`,
            opacity: 0
        }
    ], {
        duration: fallTime * 1000,
        easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
    });
}

// Make function globally available
window.launchConfetti = launchConfetti;