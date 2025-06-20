/**
 * Loove Dating App - Core JavaScript
 */

// Utility function for DOM manipulation
const DOM = {
  get: selector => document.querySelector(selector),
  getAll: selector => document.querySelectorAll(selector),
  create: (element, className) => {
    const el = document.createElement(element);
    if (className) el.className = className;
    return el;
  }
};

// Application Class
class App {
  constructor() {
    this.initEventListeners();
  }
  
  initEventListeners() {
    // Mobile menu toggle
    const menuToggle = DOM.get('.menu-toggle');
    if (menuToggle) {
      menuToggle.addEventListener('click', () => {
        DOM.get('.nav-menu').classList.toggle('active');
      });
    }
    
    // Form validation
    const forms = DOM.getAll('form');
    forms.forEach(form => {
      form.addEventListener('submit', this.validateForm);
    });
  }
  
  validateForm(e) {
    const form = e.target;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
      if (!input.value.trim()) {
        isValid = false;
        this.showError(input, 'This field is required');
      } else {
        this.removeError(input);
      }
      
      // Email validation
      if (input.type === 'email' && input.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(input.value)) {
          isValid = false;
          this.showError(input, 'Please enter a valid email address');
        }
      }
      
      // Password validation
      if (input.id === 'password' && input.value) {
        if (input.value.length < 8) {
          isValid = false;
          this.showError(input, 'Password must be at least 8 characters');
        }
      }
      
      // Password confirmation
      if (input.id === 'confirm_password') {
        const password = form.querySelector('#password');
        if (password && input.value !== password.value) {
          isValid = false;
          this.showError(input, 'Passwords do not match');
        }
      }
    });
    
    if (!isValid) {
      e.preventDefault();
    }
  }
  
  showError(input, message) {
    const formGroup = input.parentElement;
    const errorElement = formGroup.querySelector('.error-message') || DOM.create('div', 'error-message');
    
    errorElement.textContent = message;
    if (!formGroup.querySelector('.error-message')) {
      formGroup.appendChild(errorElement);
    }
    
    input.classList.add('is-invalid');
  }
  
  removeError(input) {
    const formGroup = input.parentElement;
    const errorElement = formGroup.querySelector('.error-message');
    
    if (errorElement) {
      formGroup.removeChild(errorElement);
    }
    
    input.classList.remove('is-invalid');
  }
}

// Initialize App when DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
  const app = new App();
});
