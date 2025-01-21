    <?php

    $application = $DB->SELECT_ONE_WHERE("vendors_application", "*", ["vendor_id" => AUTH_USER_ID]);

    ?>

    <div class="page-content">
        <div class="relative flex min-h-screen items-center justify-center bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16">
            <div class="relative w-full max-w-[870px] rounded-md bg-[linear-gradient(45deg,#fff9f9_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,_#fff9f9_100%)] p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]">
                <div class="relative flex flex-col justify-center rounded-md bg-white/60 dark:bg-black/50 px-6 lg:min-h-[758px] py-20 shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]">
                    <div class="mx-auto w-full max-w-[870px]">
                        <?php if (!empty($application)): ?>
                            <div class="text-center mb-8">
                                <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">Processing</h1>
                                <p class="text-base font-bold leading-normal text-white-dark">Please wait for admin approval.</p>
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
                                <?php if (!empty($application['other_references'])) { ?>
                                    <div class="flex flex-col gap-3">
                                        <div class="relative w-full h-64 bg-white rounded-lg border border-gray-200 shadow-sm group overflow-hidden">
                                            <embed src="<?= DOMAIN . '/upload/document/' . $application['other_references'] ?>" class="w-full h-full object-contain bg-white/60 p-2" />
                                            <a href="<?= DOMAIN . '/upload/document/' . $application['other_references'] ?>" target="_blank" class="absolute inset-0 flex items-center justify-center bg-gray-900/0 group-hover:bg-gray-900/20 transition-all duration-200">
                                                <i class="fa-solid fa-eye text-primary opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                                            </a>
                                        </div>
                                        <p class="text-center text-sm font-medium text-gray-700">Other References</p>
                                    </div>
                                <?php } ?>

                            </div>
                        <?php else: ?>
                            <div class="text-center">
                                <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">Onboarding</h1>
                                <p class="text-base font-bold leading-normal text-white-dark">Complete the requirement to approve your account.</p>
                            </div>
                            <form id="formDocuments" class="space-y-6">

                                <?= csrfProtect('generate'); ?>

                                <div>
                                    <label for=" business_license" class="block text-sm font-medium text-gray-700 mb-1">Business License</label>
                                    <input
                                        type="file"
                                        id="business_license"
                                        name="business_license"
                                        required
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm text-sm focus:ring-primary focus:border-primary file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded file:bg-gray-100 file:text-sm file:font-semibold file:text-gray-700">
                                </div>

                                <div>
                                    <label for="tin_certificate" class="block text-sm font-medium text-gray-700 mb-1">Tax Identification Number (TIN)</label>
                                    <input
                                        type="file"
                                        id="tin_certificate"
                                        name="tin_certificate"
                                        required
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm text-sm focus:ring-primary focus:border-primary file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded file:bg-gray-100 file:text-sm file:font-semibold file:text-gray-700">
                                </div>

                                <div>
                                    <label for="certificate" class="block text-sm font-medium text-gray-700 mb-1">Certifications (e.g., ISO, FDA)</label>
                                    <input
                                        type="file"
                                        id="certificate"
                                        name="certificate"
                                        required
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm text-sm focus:ring-primary focus:border-primary file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded file:bg-gray-100 file:text-sm file:font-semibold file:text-gray-700">
                                </div>

                                <div>
                                    <label for="other_references" class="block text-sm font-medium text-gray-700 mb-1">Previous Work References (if applicable)</label>
                                    <input
                                        type="file"
                                        id="other_references"
                                        name="other_references"
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm text-sm focus:ring-primary focus:border-primary file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded file:bg-gray-100 file:text-sm file:font-semibold file:text-gray-700">
                                </div>

                                <div id="responseDocuments" class="text-sm text-red-500"></div>

                                <?= button("submit", "btnSubmitDocuments", "Submit Documents", null, true) ?>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#formDocuments').submit(function(e) {
            e.preventDefault();
            btnLoading('#btnSubmitDocuments');
            const formData = new FormData(formDocuments);
            $.ajax({
                url: "api/onboarding/documents.php",
                method: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $('#responseDocuments').html(res);
                    btnLoadingReset('#btnSubmitDocuments');
                },
                error: function() {
                    alert("An error occurred please try again.");
                }
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