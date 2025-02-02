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
            'fas fa-sitemap',
            'Contract Management',
            '/contract',
            null,
            [],
            $currentRoute
        );

        renderNavItem(
            'fa fa-file-contract',
            'Vendor Application',
            '/application',
            null,
            [],
            $currentRoute
        );

        ?>
 </ul>