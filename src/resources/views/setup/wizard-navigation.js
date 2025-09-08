/**
 * Wizard Navigation JavaScript
 * 
 * This file contains JavaScript functions for navigating through the setup wizard.
 */

// Initialize wizard navigation
document.addEventListener('DOMContentLoaded', function() {
    initWizardNavigation();
    initFormValidation();
    initTooltips();
});

/**
 * Initialize wizard navigation
 */
function initWizardNavigation() {
    // Get all step links
    const stepLinks = document.querySelectorAll('.wizard-step-link');
    
    // Add click event listeners to step links
    stepLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Only allow navigation to completed steps or the active step
            if (!link.classList.contains('disabled')) {
                // Do nothing, let the link navigate
            } else {
                e.preventDefault();
            }
        });
    });
    
    // Get all next buttons
    const nextButtons = document.querySelectorAll('.wizard-next');
    
    // Add click event listeners to next buttons
    nextButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const form = button.closest('form');
            
            // If the button is in a form, validate the form before proceeding
            if (form) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    form.reportValidity();
                }
            }
        });
    });
    
    // Get all previous buttons
    const prevButtons = document.querySelectorAll('.wizard-prev');
    
    // Add click event listeners to previous buttons
    prevButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // No validation needed for previous buttons
        });
    });
}

/**
 * Initialize form validation
 */
function initFormValidation() {
    // Get all forms
    const forms = document.querySelectorAll('form.needs-validation');
    
    // Add submit event listeners to forms
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            form.classList.add('was-validated');
        });
    });
}

/**
 * Initialize tooltips
 */
function initTooltips() {
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Update step indicator
 * 
 * @param {number} currentStep - Current step index (1-based)
 * @param {number} totalSteps - Total number of steps
 */
function updateStepIndicator(currentStep, totalSteps) {
    const steps = document.querySelectorAll('.step-indicator .step');
    
    steps.forEach((step, index) => {
        // Convert to 0-based index
        const stepIndex = index + 1;
        
        // Remove all classes
        step.classList.remove('active', 'completed');
        
        // Add appropriate class
        if (stepIndex < currentStep) {
            step.classList.add('completed');
        } else if (stepIndex === currentStep) {
            step.classList.add('active');
        }
    });
}

/**
 * Show loading spinner
 * 
 * @param {string} message - Loading message
 */
function showLoading(message = 'Processing...') {
    // Create loading overlay
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay';
    overlay.innerHTML = `
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="loading-message mt-3">${message}</div>
        </div>
    `;
    
    // Add loading overlay to body
    document.body.appendChild(overlay);
    document.body.classList.add('loading');
}

/**
 * Hide loading spinner
 */
function hideLoading() {
    // Remove loading overlay
    const overlay = document.querySelector('.loading-overlay');
    if (overlay) {
        overlay.remove();
    }
    
    document.body.classList.remove('loading');
}
