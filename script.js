// ========= Featured Events Rendering =========
const FEATURED_EVENTS = [
  {
    id: 1,
    title: "Sunburn Arena Goa",
    date: "2024-03-15",
    venue: "Vagator Beach",
    image: "https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3",
    category: "Music Festival"
  },
  {
    id: 2,
    title: "Comedy Night at Baga",
    date: "2024-03-20",
    venue: "LPK Waterfront",
    image: "https://images.unsplash.com/photo-1516450360452-9312f5e86fc7",
    category: "Stand-up Comedy"
  },
  {
    id: 3,
    title: "Jazz by the Beach",
    date: "2024-03-25",
    venue: "Anjuna Beach",
    image: "https://images.unsplash.com/photo-1511192336575-5a79af67a629",
    category: "Live Music"
  }
];

function renderFeaturedEvents() {
  const eventsContainer = document.getElementById('featuredEvents');
  if (!eventsContainer) return;

  FEATURED_EVENTS.forEach(event => {
    const eventCard = document.createElement('div');
    eventCard.className = 'event-card';

    eventCard.innerHTML = `
      <img src="${event.image}" alt="${event.title}">
      <div class="event-card-content">
        <span class="category">${event.category}</span>
        <h3>${event.title}</h3>
        <div class="event-details">
          <span><i data-lucide="calendar"></i> ${new Date(event.date).toLocaleDateString()}</span>
          <span><i data-lucide="map-pin"></i> ${event.venue}</span>
        </div>
      </div>
    `;

    eventsContainer.appendChild(eventCard);
  });

  lucide.createIcons(); // Refresh icons
}

// ========= Login Modal (Optional) =========
function handleSignIn() {
  if (document.getElementById('loginModal')) {
    document.getElementById('loginModal').style.display = 'block';
    return;
  }

  const modal = document.createElement('div');
  modal.id = 'loginModal';
  modal.className = 'modal';
  modal.innerHTML = `
    <div class="modal-content">
      <span class="close" id="closeModal">&times;</span>
      <h2>Sign In</h2>
      <form id="signInForm" method="POST" action="login.php">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" required>
        </div>
        <button type="submit" class="button-primary">Sign In</button>
      </form>
    </div>
  `;

  document.body.appendChild(modal);

  document.getElementById('closeModal').onclick = () => modal.style.display = 'none';

  window.onclick = (e) => {
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  };
}

// ========= DOM Ready =========
document.addEventListener('DOMContentLoaded', () => {
  renderFeaturedEvents();

  const signInBtn = document.getElementById('signInBtn');
  if (signInBtn) {
    signInBtn.addEventListener('click', handleSignIn);
  }

  // Category Filter Fix
  const categoryButtons = document.querySelectorAll('.category-btn');
  const eventCards = document.querySelectorAll('.event-card[data-category]');

  if (categoryButtons.length && eventCards.length) {
    categoryButtons.forEach(button => {
      button.addEventListener('click', () => {
        categoryButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

       const selectedCategory = button.dataset.category;
        const eventLinks = document.querySelectorAll('.event-card-link');
        eventLinks.forEach(link => {
          const card = link.querySelector('.event-card');
          const cardCategory = card.getAttribute('data-category');
          const match = selectedCategory === 'all' || cardCategory === selectedCategory;
          link.classList.toggle('hidden', !match); // ✅ GOOD: hide <a> not just .event-card
        });
      });
    });
  }

  // Contact Form
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const formData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        message: document.getElementById('message').value
      };

      console.log('Form submitted:', formData);
      contactForm.reset();
      alert('Thank you for your message! We will get back to you soon.');
    });
  }

  lucide.createIcons();
});

