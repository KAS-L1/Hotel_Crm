<?php

$application_id = $_GET['application_id']; // Get application ID
$application = $DB->SELECT_ONE_WHERE('vendors_application', '*', ["vendor_id" => $application_id]);

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
            <div class="flex justify-between">
                <div>
                    <h6 class="mb-5 text-lg font-bold">Application Details</h6>
                </div>
                <div class="sm:ml-2 sm:mt-2">
                    <?php badge($application['status']); ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Profile Column -->
                <div class="w-full">
                    <div class="flex flex-col items-center p-4 border border-primary rounded-lg h-full">
                        <img
                            src="<?= DOMAIN ?>/upload/profile/<?= $user['picture'] ?>"
                            alt="Profile picture"
                            class="h-32 w-32 md:h-40 md:w-40 rounded-full object-cover mt-5">
                        <div class="mt-6 text-center space-y-3">
                            <div class="text-xl font-semibold">
                                <?= $user['first_name'] ?> <?= $user['last_name'] ?>
                            </div>
                            <div class="text-base text-gray-600">@<?= $user['username'] ?></div>
                            <div class="text-sm">
                                <span class="px-2 py-1 bg-primary-100 rounded-full"><?= $user['role'] ?> - <?= $user['user_id'] ?></span>
                            </div>
                            <div class="text-base text-gray-600"><?= $user['contact'] ?></div>
                            <div class="text-base text-gray-600"><?= $user['address'] ?? 'No address provided' ?></div>
                        </div>
                    </div>
                </div>

                <!-- Remarks Section -->
                <?php if (in_array($application['status'], ["Pending", "Declined"])) { ?>
                    <div class="w-full">
                        <div class="border border-primary rounded-lg p-4 h-full">
                            <h6 class="text-lg mb-4">Remarks</h6>
                            <div id="remarksEditor" class="w-full h-[300px] border rounded"></div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Document Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-4">
                <!-- Business License -->
                <div class="flex flex-col gap-3">
                    <div class="relative w-full h-64 bg-white rounded-lg border border-primary shadow-sm group overflow-hidden">
                        <embed src="<?= DOMAIN . '/upload/document/' . $application['business_license'] ?>" class="w-full h-full object-contain bg-white/60 p-2" />
                        <a href="<?= DOMAIN . '/upload/document/' . $application['business_license'] ?>" target="_blank" class="absolute inset-0 flex items-center justify-center bg-gray-900/0 group-hover:bg-gray-900/20 transition-all duration-200">
                            <i class="fa-solid fa-eye text-primary opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                        </a>
                    </div>
                    <p class="text-center text-sm font-medium text-gray-700">Business License</p>
                </div>

                <!-- TIN Certificate -->
                <div class="flex flex-col gap-3">
                    <div class="relative w-full h-64 bg-white rounded-lg border border-primary shadow-sm group overflow-hidden">
                        <embed src="<?= DOMAIN . '/upload/document/' . $application['tin_certificate'] ?>" class="w-full h-full object-contain bg-white/60 p-2" />
                        <a href="<?= DOMAIN . '/upload/document/' . $application['tin_certificate'] ?>" target="_blank" class="absolute inset-0 flex items-center justify-center bg-gray-900/0 group-hover:bg-gray-900/20 transition-all duration-200">
                            <i class="fa-solid fa-eye text-primary opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                        </a>
                    </div>
                    <p class="text-center text-sm font-medium text-gray-700">TIN Certificate</p>
                </div>

                <!-- Certifications -->
                <div class="flex flex-col gap-3">
                    <div class="relative w-full h-64 bg-white rounded-lg border border-primary shadow-sm group overflow-hidden">
                        <embed src="<?= DOMAIN . '/upload/document/' . $application['certificate'] ?>" class="w-full h-full object-contain bg-white/60 p-2" />
                        <a href="<?= DOMAIN . '/upload/document/' . $application['certificate'] ?>" target="_blank" class="absolute inset-0 flex items-center justify-center bg-gray-900/0 group-hover:bg-gray-900/20 transition-all duration-200">
                            <i class="fa-solid fa-eye text-primary opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                        </a>
                    </div>
                    <p class="text-center text-sm font-medium text-gray-700">Certifications</p>
                </div>

                <!-- Other References -->
                <?php if (!empty($application['other_references'])) { ?>
                    <div class="flex flex-col gap-3">
                        <div class="relative w-full h-64 bg-white rounded-lg border border-primary shadow-sm group overflow-hidden">
                            <embed src="<?= DOMAIN . '/upload/document/' . $application['other_references'] ?>" class="w-full h-full object-contain bg-white/60 p-2" />
                            <a href="<?= DOMAIN . '/upload/document/' . $application['other_references'] ?>" target="_blank" class="absolute inset-0 flex items-center justify-center bg-gray-900/0 group-hover:bg-gray-900/20 transition-all duration-200">
                                <i class="fa-solid fa-eye text-primary opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                            </a>
                        </div>
                        <p class="text-center text-sm font-medium text-gray-700">Product Catalog</p>
                    </div>
                <?php } ?>
            </div>

            <?php if ($application['status'] == "Pending") { ?>
                <div class="mt-8 flex justify-end space-x-4">
                    <div class="flex items-center">
                        <!-- <i class="fa-solid fa-times mr-2"></i> -->
                        <?= button("button", "btnDecline", "Decline", "btn-danger", false,) ?>
                    </div>
                    <div class="flex items-center">
                        <!-- <i class="fa-solid fa-check mr-2"></i> -->
                        <?= button("button", "btnApprove", "Approve", "btn-primary", false,) ?>
                    </div>
                </div>
            <?php } else if ($application['status'] == "Declined") { ?>
                <div class="mt-8 flex justify-end space-x-4">
                    <div class="flex items-center">
                        <!-- <i class="fa-solid fa-check mr-2"></i> -->
                        <?= button("button", "btnApprove", "Approve", "btn-primary", false,) ?>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>

<div id="response"></div>

<script>
    $('#btnApprove').click(function() {
        const application_id = '<?= $application_id ?>';
        const remarks = quill.root.innerHTML;
        btnLoading('#btnApprove');
        $.post('../api/onboarding/approve.php', {
            application_id: application_id,
            remarks: remarks
        }, function(res) {
            $('#response').html(res);
            btnLoadingReset('#btnApprove');
        }).fail(function() {
            $('#response').html('An error occurred. Please try again.');
        });
    });

    $('#btnDecline').click(function() {
        const application_id = '<?= $application_id ?>';
        const remarks = quill.root.innerHTML;
        btnLoading('#btnDecline');
        $.post('../api/onboarding/decline.php', {
            application_id: application_id,
            remarks: remarks
        }, function(res) {
            $('#response').html(res);
            btnLoadingReset('#btnDecline');
        }).fail(function() {
            $('#response').html('An error occurred. Please try again.');
        });
    });
</script>

<script>
    // Initialize Quill editor for remarks
    var quill = new Quill('#remarksEditor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['link', 'blockquote'],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'align': []
                }],
                ['clean']
            ]
        }
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

<style>
    /* Fix the remarks editor overflow */
    #remarksEditor {
        height: 300px;
        /* Fixed height */
        overflow-y: auto;
        /* Allow scrolling if the content exceeds the height */
    }

    /* Responsive behavior for the remarks editor */
    @media (max-width: 768px) {
        #remarksEditor {
            height: 200px;
            /* Smaller height for smaller screens */
        }
    }

    @media (min-width: 768px) {
        #remarksEditor {
            height: 300px;
            /* Larger height for medium and large screens */
        }
    }
</style>