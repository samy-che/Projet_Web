// Animation pour les u00e9lu00e9ments au chargement de la page
document.addEventListener("DOMContentLoaded", function () {
  // Animer les u00e9lu00e9ments principaux
  const productCard = document.querySelector(".product-card");
  const reviewCards = document.querySelectorAll(".review-card");

  if (productCard) {
    setTimeout(() => {
      productCard.style.opacity = "1";
      productCard.style.transform = "translateY(0)";
    }, 300);
  }

  if (reviewCards.length > 0) {
    reviewCards.forEach((card, index) => {
      setTimeout(() => {
        card.style.opacity = "1";
        card.style.transform = "translateY(0)";
      }, 500 + index * 150);
    });
  }

  // Aucun code de blocage pour les utilisateurs connectu00e9s
  // Les liens vers le panier et le bouton d'ajout au panier fonctionnent normalement
});

// Fonction pour afficher une notification stylu00e9e (conservu00e9e pour une utilisation u00e9ventuelle)
function showNotification(message) {
  const notification = document.createElement("div");
  notification.className = "notification";
  notification.innerHTML = `
        <div class="notification-content">
            <i class='bx bx-info-circle'></i>
            <p>${message}</p>
        </div>
        <button class="notification-close"><i class='bx bx-x'></i></button>
    `;

  document.body.appendChild(notification);

  // Animation d'entru00e9e
  setTimeout(() => {
    notification.classList.add("show");
  }, 10);

  // Fermeture automatique apru00e8s 5 secondes
  setTimeout(() => {
    notification.classList.remove("show");
    setTimeout(() => {
      notification.remove();
    }, 300);
  }, 5000);

  // Fermeture manuelle
  const closeBtn = notification.querySelector(".notification-close");
  closeBtn.addEventListener("click", () => {
    notification.classList.remove("show");
    setTimeout(() => {
      notification.remove();
    }, 300);
  });
}
