 <ul class="horizontal-menu hidden border-t border-[#ebedf2] bg-white px-6 py-1.5 font-semibold text-black rtl:space-x-reverse dark:border-[#191e3a] dark:bg-[#0e1726] dark:text-white-dark lg:space-x-1.5 xl:space-x-8">
    <?php
    $currentRoute = $_SERVER['REQUEST_URI'];

    renderNavItem(
        'fa-solid fa-home',
        'Dashboard',
        '#',
        null,
        [],
        $currentRoute
    );

    renderNavItem(
        'fas fa-file-invoice-dollar',
        'Purchase Order',
        '#',
        null,
        [],
        $currentRoute
    );

    renderNavItem(
        'fas fa-file-signature',
        'Request For Qoute',
        '/vendor-rfq',
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
        'fas fa-book-open',
        'Product Catalog',
        '/vendor-catalog',
        null,
        [],
        $currentRoute
    );

    renderNavItem(
        'fas fa-receipt',
        'Invoice',
        '#',
        null,
        [],
        $currentRoute
    );

    renderNavItem(
        'fas fa-shipping-fast',
        'Delivery & Shipment Updates',
        '#',
        null,
        [],
        $currentRoute
    );

    renderNavItem(
        'fas fa-question-circle',
        'Help & Support',
        '#',
        null,
        [],
        $currentRoute
    );

    ?>
 </ul>