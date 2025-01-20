<?php

$application_id = $_GET['application_id']; // Get application ID
$application = $DB->SELECT_ONE_WHERE('vendors_application', '*', ["id" => $application_id]);

if (empty($application)) die(toast("error", "Application not found"));

$user = $DB->SELECT_ONE_WHERE('users', '*', ["user_id" => $application['vendor_id']]); // Get user details by vendor_id

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
            <h6 class="mb-5 text-lg font-bold">Vendor Application Details</h6>
            <div class="flex flex-col sm:flex-row">
                <div class="mb-5 w-full sm:w-2/12 ltr:sm:mr-4 rtl:sm:ml-4">
                    <img src="<?= DOMAIN ?>/upload/profile/<?= $user['picture'] ?>" alt="image" class="mx-auto h-20 w-20 rounded-full object-cover md:h-32 md:w-32">
                </div>
            </div>
            <!-- Document Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

                <!-- Business License -->
                <div class="flex flex-col gap-3">
                    <div class="relative w-full h-64 bg-white rounded-lg border border-gray-200 shadow-sm group overflow-hidden">
                        <embed src="<?= DOMAIN . '/upload/document/' . $application['business_license'] ?>" class="w-full h-full object-contain bg-white/60 p-2" />
                        <a href="<?= DOMAIN . '/upload/document/' . $application['business_license'] ?>" target="_blank" class="absolute inset-0 flex items-center justify-center bg-gray-900/0 group-hover:bg-gray-900/20 transition-all duration-200">
                            <i class="fa-solid fa-eye text-primary opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                        </a>
                    </div>
                    <p class="text-center text-sm font-medium text-gray-700">Business License</p>
                </div>

                <!-- TIN Certificate -->
                <div class="flex flex-col gap-3">
                    <div class="relative w-full h-64 bg-white rounded-lg border border-gray-200 shadow-sm group overflow-hidden">
                        <embed src="<?= DOMAIN . '/upload/document/' . $application['tin_certificate'] ?>" class="w-full h-full object-contain bg-white/60 p-2" />
                        <a href="<?= DOMAIN . '/upload/document/' . $application['tin_certificate'] ?>" target="_blank" class="absolute inset-0 flex items-center justify-center bg-gray-900/0 group-hover:bg-gray-900/20 transition-all duration-200">
                            <i class="fa-solid fa-eye text-primary opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                        </a>
                    </div>
                    <p class="text-center text-sm font-medium text-gray-700">TIN Certificate</p>
                </div>

                <!-- Certifications -->
                <div class="flex flex-col gap-3">
                    <div class="relative w-full h-64 bg-white rounded-lg border border-gray-200 shadow-sm group overflow-hidden">
                        <embed src="<?= DOMAIN . '/upload/document/' . $application['certificate'] ?>" class="w-full h-full object-contain bg-white/60 p-2" />
                        <a href="<?= DOMAIN . '/upload/document/' . $application['certificate'] ?>" target="_blank" class="absolute inset-0 flex items-center justify-center bg-gray-900/0 group-hover:bg-gray-900/20 transition-all duration-200">
                            <i class="fa-solid fa-eye text-primary opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                        </a>
                    </div>
                    <p class="text-center text-sm font-medium text-gray-700">Certifications</p>
                </div>

                <!-- Other References -->
                <div class="flex flex-col gap-3">
                    <div class="relative w-full h-64 bg-white rounded-lg border border-gray-200 shadow-sm group overflow-hidden">
                        <?php
                        // Get the file path from the database
                        $file_path = !empty($application['other_references']) ? DOMAIN . '/upload/document/' . $application['other_references'] : null;

                        if (!empty($application['other_references'])) {
                            $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);

                            // Check if the file is an image (jpg, jpeg, png, gif)
                            if (in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                // Render as an image
                                echo '<img src="' . htmlspecialchars($file_path) . '" class="w-full h-full object-contain rounded-lg" alt="Other References" />';
                            } else {
                                // Render as an embedded document (e.g., PDF)
                                echo '<embed src="' . htmlspecialchars($file_path) . '" class="w-full h-full object-contain bg-white/60 p-2" type="application/pdf" />';
                            }

                            // Add the view link
                            echo '<a href="' . htmlspecialchars($file_path) . '" target="_blank" class="absolute inset-0 flex items-center justify-center bg-gray-900/0 group-hover:bg-gray-900/20 transition-all duration-200">
                    <i class="fa-solid fa-eye text-primary opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                  </a>';
                        } else {
                            // Display a placeholder message if no file exists
                            echo '<div class="w-full h-full flex items-center justify-center text-gray-400">No document available</div>';
                        }
                        ?>
                    </div>
                    <p class="text-center text-sm font-medium text-gray-700">Other References</p>
                </div>
            </div>
            <div class="mt-8 flex justify-end space-x-4">
                <div class="flex items-center">
                    <!-- <i class="fa-solid fa-times mr-2"></i> -->
                    <?= button("submit", "btnDecline", "Decline", "btn-danger", false) ?>
                </div>

                <div class="flex items-center">
                    <!-- <i class="fa-solid fa-check mr-2"></i> -->
                    <?= button("submit", "btnApprove", "Approve", null, false) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="reponseApprove"></div>
<script>
    $('.btnApprove').click(function() {
        const application_id = $(this).data('application_id');
        $.post('api/onboarding/approve.php', {
            application_id: application_id
        }, function(res) {
            $('#reponseApprove').html(res);
        }).fail(function() {
            $('#reponseApprove').html('An error occurred. Please try again.');
        });
    });
</script>

<style>
    /* CSS to ensure responsive behavior */
    .custom-card {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        overflow-x: hidden;
    }

    .custom-card>div {
        width: calc(100% - 1rem);
        /* 100% on smaller screens */
        flex-basis: calc(100% - 1rem);
    }

    @media (min-width: 640px) {
        .custom-card>div {
            width: calc(50% - 1rem);
            /* 2 columns on sm screens */
        }
    }

    @media (min-width: 768px) {
        .custom-card>div {
            width: calc(33.33% - 1rem);
            /* 3 columns on md screens */
        }
    }

    @media (min-width: 1024px) {
        .custom-card>div {
            width: calc(25% - 1rem);
            /* 4 columns on lg screens */
        }
    }

    .custom-card img.object-contain {
        width: 100%;
        height: 100%;
        object-fit: contain;
        /* Ensures no cropping */
    }

    .custom-card .relative {
        height: 16rem;
    }

    .custom-card .relative img {
        border-radius: 0.5rem;
    }

    .custom-card .group:hover .fa-eye {
        opacity: 1;
    }

    .custom-card .group:hover {
        border-color: #2ec408;
    }

    /* Placeholder styling for empty content */
    .custom-card .flex.items-center.justify-center.text-gray-400 {
        font-size: 1rem;
        font-weight: bold;
        color: #6b7280;
        /* gray-400 */
    }

    .custom-placeholder {
        font-size: 1rem;
        font-weight: bold;
        color: #6b7280;
        /* gray-400 */
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
</style>