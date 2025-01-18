<?php
// Prevent normal template loading
define("MAINTENANCE_MODE", true);

header("HTTP/1.1 503 Service Temporarily Unavailable");
header("Status: 503 Service Temporarily Unavailable");
header("Retry-After: 3600");

require_once("app/init.php")
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= APP_TITLE ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?= APP_ICON ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/perfect-scrollbar.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/style.css">
    <link defer="" rel="stylesheet" type="text/css" media="screen" href="assets/css/animate.css">
    <script src="assets/js/perfect-scrollbar.min.js"></script>
    <script defer="" src="assets/js/popper.min.js"></script>
    <script defer="" src="assets/js/tippy-bundle.umd.min.js"></script>
    <script defer="" src="assets/js/sweetalert.min.js"></script>
</head>

<body x-data="main" class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased" :class="[ $store.app.sidebar ? toggle-sidebar : , $store.app.theme === dark || $store.app.isDarkMode ?  dark : , $store.app.menu, $store.app.layout,$store.app.rtlClass]">

    <div class="main-container min-h-screen text-black dark:text-white-dark">
        <!-- start main content section -->
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden">
            <div class="px-6 py-16 text-center font-semibold before:container before:absolute before:left-1/2 before:aspect-square before:-translate-x-1/2 before:rounded-full before:bg-[linear-gradient(180deg,var(--primary-color)_0%,rgba(67,97,238,0)_50.73%)] before:opacity-10 md:py-20">
                <div class="relative">
                    <div class="-mt-8 font-semibold dark:text-white">
                        <h2 class="mb-5 text-3xl font-bold text-primary md:text-5xl">Under Maintenance</h2>
                        <h4 class="mb-7 text-xl sm:text-2xl">Thank you for visiting us.</h4>
                        <p class="text-base">
                            We are currently working on making some improvements <br class="hidden sm:block">to give you better user experience. <br>
                            <br>Please visit us again shortly.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- end main content section -->
    </div>

    <script src="assets/js/alpine-collaspe.min.js"></script>
    <script src="assets/js/alpine-persist.min.js"></script>
    <script defer="" src="assets/js/alpine-ui.min.js"></script>
    <script defer="" src="assets/js/alpine-focus.min.js"></script>
    <script defer="" src="assets/js/alpine.min.js"></script>

    <script src="assets/js/custom.js"></script>

</body>

</html>
