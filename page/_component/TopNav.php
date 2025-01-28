 <ul class="horizontal-menu hidden border-t border-[#ebedf2] bg-white px-6 py-1.5 font-semibold text-black rtl:space-x-reverse dark:border-[#191e3a] dark:bg-[#0e1726] dark:text-white-dark lg:space-x-1.5 xl:space-x-8">
     <?php
        $currentRoute = $_SERVER['REQUEST_URI'];

        renderVerticalNavItem(
            'fa-solid fa-home',
            'Dashboard',
            '#',
            'dashboard',
            [
                ['title' => 'Data Analytics', 'route' => '/data-analytics'],
                ['title' => 'Predictive Analytics', 'route' => '/predictive-analytics']
            ],
            $currentRoute
        );

        renderVerticalNavItem(
            'fa fa-cart-shopping',
            'Procurement',
            '#',
            'procurement',
            [
                ['title' => 'Purchase Requisition', 'route' => '#'],
                ['title' => 'Budget Approval', 'route' => '#'],
                ['title' => 'Pruchase Order', 'route' => '#'],
                ['title' => 'Request For Qoute', 'route' => '#'],
                ['title' => 'Category Management', 'route' => '/category'],
                ['title' => 'Vendor Management', 'route' => '/vendor-management'],
                ['title' => 'Contract Management', 'route' => '#'],
                ['title' => '', 'route' => '#']
            ],
            $currentRoute
        );

        renderVerticalNavItem(
            'fa fa-clipboard-list',
            'Audit Management',
            '#',
            'auditManagement',
            [
                ['title' => 'Audit Schedule', 'route' => '#'],
                ['title' => 'Audit Findings', 'route' => '#'],
                ['title' => 'Audit Logs', 'route' => '#'],
                ['title' => 'Audit History', 'route' => '#'],
                ['title' => 'Reports', 'route' => '#'],

            ],
            $currentRoute
        );

        renderNavItem(
            'fa fa-file-contract',
            'Document Tracking',
            '#',
            null,
            [],
            $currentRoute
        );

        renderVerticalNavItem(
            'fa fa-building',
            'Vendor Portal',
            '#',
            'vendorPortal',
            [
                ['title' => 'Dashboard', 'route' => '#'],
                ['title' => 'Purchase Order', 'route' => '#'],
                ['title' => 'Request For Qoute', 'route' => '#'],
                ['title' => 'Product Catalog', 'route' => '#'],
                ['title' => 'Invoice', 'route' => '#'],
                ['title' => 'Delivery & Shipment Updates', 'route' => '#'],
                ['title' => 'Help & Support', 'route' => '#']
            ],
        );

        renderNavItem(
            'fa fa-file-contract',
            'Vendor Application',
            '/application',
            null,
            [],
            $currentRoute
        );

        renderVerticalNavItem(
            'fa fa-users',
            'UserManagement',
            '#',
            'userManagement',
            [
                ['title' => 'Role', 'route' => '#'],
                ['title' => 'Permission', 'route' => '#'],

            ],
            $currentRoute
        );
        ?>
 </ul>