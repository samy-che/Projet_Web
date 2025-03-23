// Animation pour les éléments au chargement de la page
document.addEventListener('DOMContentLoaded', function () {
    // Animer les éléments principaux
    const productCard = document.querySelector('.product-card');
    const reviewCards = document.querySelectorAll('.review-card');

    if (productCard) {
        setTimeout(() => {
            productCard.style.opacity = '1';
            productCard.style.transform = 'translateY(0)';
        }, 300);
    }

    if (reviewCards.length > 0) {
        reviewCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 500 + (index * 150));
        });
    }

    // Gestion des alertes pour les utilisateurs non connectés
    if (window.location.pathname.includes('page.php')) {
        let cartIcon = document.querySelector(".bx-shopping-bag");
        if (cartIcon) {
            cartIcon.parentElement.addEventListener('click', function (e) {
                e.preventDefault();
                showNotification("Vous devez d'abord vous connecter !");
            });
        }

        let addToCartBtn = document.querySelector(".add-to-cart-btn");
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function (e) {
                e.preventDefault();
                showNotification("Vous devez d'abord vous connecter !");
            });
        }
    }
});

// Fonction pour afficher une notification stylisée
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.innerHTML = `
        <div class="notification-content">
            <i class='bx bx-info-circle'></i>
            <p>${message}</p>
        </div>
        <button class="notification-close"><i class='bx bx-x'></i></button>
    `;

    document.body.appendChild(notification);

    // Animation d'entrée
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    // Fermeture automatique après 5 secondes
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);

    // Fermeture manuelle
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    });
}