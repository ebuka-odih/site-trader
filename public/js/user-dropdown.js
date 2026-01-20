// User Dropdown Functionality

document.addEventListener('DOMContentLoaded', function() {
    const userDropdownButton = document.getElementById('userDropdownButton');
    const userDropdownMenu = document.getElementById('userDropdownMenu');
    const userDropdownArrow = document.getElementById('userDropdownArrow');

    if (userDropdownButton && userDropdownMenu && userDropdownArrow) {
        userDropdownButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isOpen = userDropdownMenu.classList.contains('opacity-100');
            
            if (isOpen) {
                // Close dropdown
                userDropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                userDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                userDropdownArrow.style.transform = 'rotate(0deg)';
            } else {
                // Open dropdown
                userDropdownMenu.classList.remove('opacity-0', 'invisible', 'scale-95');
                userDropdownMenu.classList.add('opacity-100', 'visible', 'scale-100');
                userDropdownArrow.style.transform = 'rotate(180deg)';
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userDropdownButton.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                userDropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                userDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                userDropdownArrow.style.transform = 'rotate(0deg)';
            }
        });

        // Close dropdown when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                userDropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                userDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                userDropdownArrow.style.transform = 'rotate(0deg)';
            }
        });
    }
});
