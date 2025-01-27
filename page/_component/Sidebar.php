<div :class="{ 'dark text-white-dark': $store.app.semidark }">
    <nav x-data="sidebar"
        class="sidebar fixed bottom-0 top-0 z-50 h-full min-h-screen w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] transition-all duration-300">
        <div class="h-full bg-white dark:bg-[#0e1726]">
            <div class="flex items-center justify-between px-4 py-3">
                <a href="/dashboard" class="main-logo flex shrink-0 items-center">
                    <img class="ml-[5px] w-8 flex-none rounded-full" src="<?= APP_LOGO_NAV ?>" alt="<?= APP_LOGO_NAV ?>">
                    <span
                        class="align-middle text-2xl font-semibold ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light lg:inline"><?= APP_NAME ?></span>
                </a>
                <a href="javascript:;"
                    class="collapse-icon flex h-8 w-8 items-center rounded-full transition duration-300 hover:bg-gray-500/10 rtl:rotate-180 dark:text-white-light dark:hover:bg-dark-light/10"
                    @click="$store.app.toggleSidebar()">
                    <svg class="m-auto h-5 w-5" width="20" height="20" viewbox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            </div>
            <ul class="perfect-scrollbar relative h-[calc(100vh-80px)] space-y-0.5 overflow-y-auto overflow-x-hidden p-4 py-0 font-semibold"
                x-data="{ activeDropdown: '' }">

                <li class="nav-item">
                    <?php
                    $currentRoute = $_SERVER['REQUEST_URI'];

                    renderNavItem(
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

                    renderSectionHeader('LOGISTICS');

                    renderNavItem(
                        'fa fa-cart-shopping',
                        'Procurement',
                        '#',
                        'procurement',
                        [
                            ['title' => 'Purchase Requisition', 'route' => '#'],
                            ['title' => 'Budget Approval', 'route' => '#'],
                            ['title' => 'Pruchase Order', 'route' => '#'],
                            ['title' => 'Category Management', 'route' => '/category'],
                            ['title' => 'Vendor Management', 'route' => '/vendor-management'],
                            ['title' => 'Request For Qoute', 'route' => '#'],
                            ['title' => 'Contract Management', 'route' => '#'],
                            ['title' => 'Invoice', 'route' => '#']
                        ],
                        $currentRoute
                    );

                    renderNavItem(
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

                    renderNavItem(
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

                    renderSectionHeader('SETTINGS');

                    renderNavItem(
                        'fa fa-file-contract',
                        'Vendor Application',
                        '/application',
                        null,
                        [],
                        $currentRoute
                    );

                    renderNavItem(
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
                </li>
            </ul>
        </div>
    </nav>
</div>