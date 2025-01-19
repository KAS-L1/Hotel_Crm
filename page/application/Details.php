<?php

$vendor_id = $_GET['application_id'];
$user = $DB->SELECT_ONE_WHERE('users', '*', ["user_id" => $vendor_id]);
$application = $DB->SELECT_ONE_WHERE("vendors_application", "*", ["vendor_id" => $vendor_id]);
?>

<div class="page-content">

    <?php
    breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'Application', 'url' => '/application'],
        ['label' => 'Details', 'url' => '/application/details'],
    ]);
    ?>

    <div class="pt-5">
        <div class="panel">
            <h6 class="mb-5 text-lg font-bold">General Information</h6>
            <div class="flex flex-col sm:flex-row">
                <div class="mb-5 w-full sm:w-2/12 ltr:sm:mr-4 rtl:sm:ml-4">
                    <img src="<?= DOMAIN ?>/upload/profile/<?= $user['picture'] ?>" alt="image" class="mx-auto h-20 w-20 rounded-full object-cover md:h-32 md:w-32">
                </div>
            </div>
            <!-- Document Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 custom-card">

                <div class="flex flex-col gap-3">
                    <div
                        class="relative w-full h-64 bg-white rounded-lg border border-gray-200 shadow-sm group overflow-hidden">
                        <embed src="<?= DOMAIN . '/upload/document/' . $application['business_license'] ?>"
                            class="w-full h-full object-contain bg-white/60 p-2" />
                        <a href="<?= DOMAIN . '/upload/document/' . $application['business_license'] ?>" target="_blank"
                            class="absolute inset-0 flex items-center justify-center bg-gray-900/0 group-hover:bg-gray-900/20 transition-all duration-200">
                            <i
                                class="fa-solid fa-eye text-primary opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                        </a>
                    </div>
                    <p class="text-center text-sm font-medium text-gray-700">Business License</p>
                </div>

                <div class="flex flex-col gap-3">
                    <div
                        class="relative w-full h-64 bg-white rounded-lg border border-gray-200 shadow-sm group overflow-hidden">
                        <embed src="<?= DOMAIN . '/upload/document/' . $application['tin_certificate'] ?>"
                            class="w-full h-full object-contain bg-white/60 p-2" />
                        <a href="<?= DOMAIN . '/upload/document/' . $application['tin_certificate'] ?>" target="_blank"
                            class="absolute inset-0 flex items-center justify-center bg-gray-900/0 group-hover:bg-gray-900/20 transition-all duration-200">
                            <i
                                class="fa-solid fa-eye text-primary opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                        </a>
                    </div>
                    <p class="text-center text-sm font-medium text-gray-700">TIN Certificate</p>
                </div>

                <div class="flex flex-col gap-3">
                    <div
                        class="relative w-full h-64 bg-white rounded-lg border border-gray-200 shadow-sm group overflow-hidden">
                        <embed src="<?= DOMAIN . '/upload/document/' . $application['certificate'] ?>"
                            class="w-full h-full object-contain bg-white/60 p-2" />
                        <a href="<?= DOMAIN . '/upload/document/' . $application['certificate'] ?>" target="_blank"
                            class="absolute inset-0 flex items-center justify-center bg-gray-900/0 group-hover:bg-gray-900/20 transition-all duration-200">
                            <i
                                class="fa-solid fa-eye text-primary opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                        </a>
                    </div>
                    <p class="text-center text-sm font-medium text-gray-700">Certifications</p>
                </div>

                <div class="flex flex-col gap-3">
                    <div
                        class="relative w-full h-64 bg-white rounded-lg border border-gray-200 shadow-sm group overflow-hidden">
                        <img src="<?= DOMAIN . '/upload/document/' . $application['other_references'] ?>"
                            class="w-full h-full object-cover rounded-lg" alt="Other References" />
                        <a href="<?= DOMAIN . '/upload/document/' . $application['other_references'] ?>" target="_blank"
                            class="absolute inset-0 flex items-center justify-center bg-gray-900/0 group-hover:bg-gray-900/20 transition-all duration-200">
                            <i
                                class="fa-solid fa-eye text-primary opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                        </a>
                    </div>
                    <p class="text-center text-sm font-medium text-gray-700">Other References</p>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .custom-card {
        display: flex;
        /* Use flexbox for wrapping */
        flex-wrap: wrap;
        gap: 1rem;
        overflow-x: hidden;
    }

    .custom-card>div {
        width: calc(25% - 1rem);
        /* 4 columns with gap */
        flex-basis: calc(25% - 1rem);
    }

    .custom-card img.object-cover {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }

    .custom-card .relative {
        height: 16rem;
    }

    .custom-card .relative embed {
        object-fit: contain;
    }

    .custom-card .relative img {
        border-radius: 0.5rem;
    }

    .custom-card .group:hover .fa-eye {
        opacity: 1;
    }

    .custom-card .group:hover {
        border-color: #3182ce;
    }
</style>