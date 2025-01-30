 <ul class="horizontal-menu hidden border-t border-[#ebedf2] bg-white px-6 py-1.5 font-semibold text-black rtl:space-x-reverse dark:border-[#191e3a] dark:bg-[#0e1726] dark:text-white-dark lg:space-x-1.5 xl:space-x-8">
     <?php
        $currentRoute = $_SERVER['REQUEST_URI'];

        renderNavItem(
            'fa-solid fa-home',
            'Dashboard',
            '#',
            'dashboard',
            [],
            $currentRoute
        );

        renderNavItem(
            'fas fa-shopping-cart',
            'Purchase Requisition',
            '#',
            null,
            [],
            $currentRoute
        );

        renderNavItem(
            'fas fa-file-invoice-dollar',
            'Budget Approval',
            '#',
            null,
            [],
            $currentRoute
        );

        renderNavItem(
            'fas fa-file-alt',
            'Purchase Order',
            '#',
            null,
            [],
            $currentRoute
        );

        renderNavItem(
            'fas fa-tags',
            'Category Management',
            '/category',
            null,
            [],
            $currentRoute
        );

        renderNavItem(
            'fas fa-handshake',
            'Vendor Management',
            '/vendor-management',
            null,
            [],
            $currentRoute
        );

        renderNavItem(
            'fas fa-clipboard-list',
            'Request For Qoute',
            'request-for-qoute',
            null,
            [],
            $currentRoute
        );

        renderNavItem(
            'fas fa-sitemap',
            'Contract Management',
            '#',
            null,
            [],
            $currentRoute
        );

        renderNavItem(
            'fas fa-file-invoice',
            'Invoice',
            '#',
            null,
            [],
            $currentRoute
        );
        ?>
 </ul>