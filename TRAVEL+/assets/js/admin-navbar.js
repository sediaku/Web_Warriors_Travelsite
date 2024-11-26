document.addEventListener('DOMContentLoaded', function() {
    const viewMoreBtn = document.getElementById('viewMoreBtn');
    const mainNav = document.getElementById('mainNav');
    const accountBtn = document.getElementById('accountBtn');
    const accountDropdown = document.getElementById('accountDropdown');

    // Create View More Modal
    const viewMoreModal = document.createElement('div');
    viewMoreModal.classList.add('nav-modal');
    viewMoreModal.id = 'viewMoreModal';

    // Clone navigation links to modal
    const navLinks = mainNav.querySelectorAll('a');
    navLinks.forEach(link => {
        const modalLink = link.cloneNode(true);
        viewMoreModal.appendChild(modalLink);
    });

    // Create Account Modal
    const accountModal = document.createElement('div');
    accountModal.classList.add('account-modal');
    accountModal.id = 'accountModal';

    // Create account modal links
    const adashboardLink = document.createElement('a');
    adashboardLink.href = '../../view/admin/admin-dashboard.php';
    adashboardLink.textContent = 'My Dashboard';

    const wishlistLink = document.createElement('a');
    wishlistLink.href = '../wishlist.php';
    wishlistLink.textContent = 'My Wishlist';

    const umanagementLink = document.createElement('a');
    umanagementLink.href = '../../view/admin/user-management.php';
    umanagementLink.textContent = 'User Management';

    const lmanagementLink = document.createElement('a');
    lmanagementLink.href = '../../view/admin/location-management.php';
    lmanagementLink.textContent = 'Location Management';

    const logoutLink = document.createElement('a');
    logoutLink.href = '../logout.php';
    logoutLink.textContent = 'Logout';

    accountModal.appendChild(adashboardLink);
    accountModal.appendChild(wishlistLink);
    accountModal.appendChild(umanagementLink);
    accountModal.appendChild(lmanagementLink);
    accountModal.appendChild(logoutLink);

    // Append modals to body
    document.body.appendChild(viewMoreModal);
    document.body.appendChild(accountModal);

    // View More Button Toggle
    viewMoreBtn.addEventListener('click', function(event) {
        event.stopPropagation();
        viewMoreModal.classList.toggle('show');
        accountModal.classList.remove('show');
    });

    // Account Button Toggle
    accountBtn.addEventListener('click', function(event) {
        event.stopPropagation();
        accountModal.classList.toggle('show');
        viewMoreModal.classList.remove('show');
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (!accountBtn.contains(event.target)) {
            accountModal.classList.remove('show');
        }
        if (!viewMoreBtn.contains(event.target)) {
            viewMoreModal.classList.remove('show');
        }
    });
});