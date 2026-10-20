document.addEventListener('DOMContentLoaded', () => {
  setupMobileNav();
  setupTabs();
  setupContactForm();
  setupFaq();
  setupServiceSearch();
  setupAvailabilityToggle();
  setupRevealOnScroll();
});

function setupMobileNav() {
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');

  if (!toggle || !nav) return;

  toggle.addEventListener('click', () => {
    nav.classList.toggle('open');
  });
}

function setupTabs() {
  const tabButtons = document.querySelectorAll('.tab-btn[data-tab]');
  const tabContents = document.querySelectorAll('.tab-content');

  if (!tabButtons.length || !tabContents.length) return;

  tabButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const targetId = button.dataset.tab;
      const target = document.getElementById(targetId);

      tabButtons.forEach((btn) => btn.classList.remove('active'));
      tabContents.forEach((content) => content.classList.remove('active'));

      button.classList.add('active');
      if (target) target.classList.add('active');
    });
  });
}

function setupContactForm() {
  const form = document.getElementById('contactForm');
  const message = document.getElementById('formMessage');

  if (!form || !message) return;

  form.addEventListener('submit', (event) => {
    let valid = true;
    const requiredFields = [
      { id: 'name', text: 'Please enter your name.' },
      { id: 'email', text: 'Please enter a valid email.' },
      { id: 'subject', text: 'Please enter a subject.' },
      { id: 'message', text: 'Please enter your message.' }
    ];

    requiredFields.forEach((field) => {
      const input = document.getElementById(field.id);
      const error = input.parentElement.querySelector('.error-text');
      const value = input.value.trim();

      error.textContent = '';

      if (!value) {
        error.textContent = field.text;
        valid = false;
      }

      if (field.id === 'email' && value) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(value)) {
          error.textContent = field.text;
          valid = false;
        }
      }
    });

    if (!valid) {
      event.preventDefault();
      message.textContent = '';
      return;
    }
  });
}

function setupFaq() {
  const faqButtons = document.querySelectorAll('.faq-question');
  if (!faqButtons.length) return;

  faqButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const item = button.parentElement;
      item.classList.toggle('active');
    });
  });
}

function setupServiceSearch() {
  const searchInput = document.getElementById('serviceSearch');
  const cards = document.querySelectorAll('.service-card');
  const emptyState = document.getElementById('serviceEmptyState');

  if (!searchInput || !cards.length || !emptyState) return;

  searchInput.addEventListener('input', () => {
    const query = searchInput.value.trim().toLowerCase();
    let visibleCount = 0;

    cards.forEach((card) => {
      const text = card.dataset.service.toLowerCase();
      const match = text.includes(query);
      card.classList.toggle('hidden', !match);
      if (match) visibleCount += 1;
    });

    emptyState.classList.toggle('hidden', visibleCount !== 0);
  });
}

function setupAvailabilityToggle() {
  const button = document.getElementById('toggleAvailability');
  const bookedDays = document.querySelectorAll('.calendar-day.booked');

  if (!button || !bookedDays.length) return;

  let showingAvailableOnly = false;

  button.addEventListener('click', () => {
    showingAvailableOnly = !showingAvailableOnly;

    bookedDays.forEach((day) => {
      day.classList.toggle('hidden', showingAvailableOnly);
    });

    button.textContent = showingAvailableOnly ? 'Show All Days' : 'Show Only Available';
  });
}

function setupRevealOnScroll() {
  const elements = document.querySelectorAll('.reveal');
  if (!elements.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });

  elements.forEach((element) => observer.observe(element));
}
