#!/bin/bash

# Usage function to display help message
usage() {
    echo "Usage: $0 [up|down]"
    echo "  up   - Make site available"
    echo "  down - Put site in maintenance mode"
    exit 1
}

# Check if command argument is provided
if [ $# -eq 0 ]; then
    usage
fi

# File paths
MAINTENANCE_FILE="maintenance.php"
HTACCESS_FILE=".htaccess"
STORAGE_DIR="storage/backups"
DATE=$(date +%Y%m%d_%H%M%S)

# Create maintenance page that bypasses the template system
MAINTENANCE_PAGE='<?php
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

</html>'

case "$1" in
  up)
    echo "Making site available..."

    # Restore original .htaccess
    echo 'RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [L,QSA]' > "$HTACCESS_FILE"

    # Remove maintenance file
    if [ -f "$MAINTENANCE_FILE" ]; then
      rm "$MAINTENANCE_FILE"
    fi

    # Clear cache if exists
    if [ -d "app/cache" ]; then
      rm -rf app/cache/*
    fi

    # Optionally clear storage directory (modify $CLEAR_STORAGE variable)
    if [[ "$CLEAR_STORAGE" == "true" ]]; then
      rm -rf "$STORAGE_DIR"
      echo "Storage directory cleared."
    fi

    echo "Site is now available!"
    ;;

  down)
    echo "Putting site in maintenance mode..."

    # Create storage directory if it doesn't exist
    mkdir -p "$STORAGE_DIR"

    # Backup important directories
    echo "Creating backups..."
    tar -czf "$STORAGE_DIR/app_backup_$DATE.tar.gz" app/ 2>/dev/null
    tar -czf "$STORAGE_DIR/upload_backup_$DATE.tar.gz" upload/ 2>/dev/null

    # Create maintenance page
    echo "$MAINTENANCE_PAGE" > "$MAINTENANCE_FILE"

    # Update .htaccess to bypass template system
    echo "RewriteEngine On

# Allow localhost access
RewriteCond %{REMOTE_ADDR} !^127\.0\.0\.1$
RewriteCond %{REMOTE_ADDR} !^::1$

# Allow static assets
RewriteCond %{REQUEST_URI} \.(css|js|jpg|jpeg|png|gif)$ [NC]
RewriteRule ^ - [L]

# Redirect everything else to maintenance page
RewriteCond %{REQUEST_URI} !^/$MAINTENANCE_FILE$
RewriteRule ^(.*)$ $MAINTENANCE_FILE [L]

# Original routing (only reached by localhost)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [L,QSA]" > "$HTACCESS_FILE"

    echo "Site is now in maintenance mode!"
    echo "Backups created in $STORAGE_DIR"
    ;;

  *)
    usage
    ;;
esac