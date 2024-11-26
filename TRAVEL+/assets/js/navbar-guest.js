document.addEventListener('DOMContentLoaded', function() {
    // Function to create and set up dropdowns and modals
    function setupNavbarInteractivity() {
        const accountContainer = document.querySelector('.account-container');
        
        // If no account container, exit
        if (!accountContainer) return;

        const accountBtn = accountContainer.querySelector('.account');
        const accountDropdown = accountContainer.querySelector('.account-dropdown');
        const viewMoreBtn = document.getElementById('viewMoreBtn');

        // Exit if critical elements are missing
        if (!accountBtn || !accountDropdown) return;

        // Close dropdowns when clicking outside
        function closeDropdowns(event) {
            if (accountDropdown && 
                !accountContainer.contains(event.target)) {
                accountDropdown.classList.remove('show');
            }
        }

        // Remove any existing listeners to prevent duplicates
        document.removeEventListener('click', closeDropdowns);
        document.addEventListener('click', closeDropdowns);

        // Toggle account dropdown
        accountBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            accountDropdown.classList.toggle('show');
        });

        // View More Button (if exists)
        if (viewMoreBtn) {
            viewMoreBtn.addEventListener('click', function(event) {
                const navModal = document.querySelector('.nav-modal');
                if (navModal) {
                    event.stopPropagation();
                    navModal.classList.toggle('show');
                }
            });
        }
    }

    // Initial setup
    setupNavbarInteractivity();

    // Optional: Re-run setup for dynamically loaded content
    // Useful if navbar is included via PHP
    if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    setupNavbarInteractivity();
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
});