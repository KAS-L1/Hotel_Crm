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

                        <?php else: ?>
                            <div class="text-center">
                                <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">Onboarding</h1>
                                <p class="text-base font-bold leading-normal text-white-dark">Complete the requirement to approve your account.</p>
                            </div>
                            <form id="formDocuments">
                                <label for="business-license">Upload Business License:</label>
                                <input type="file" id="business_license" name="business_license" required>

                                <label for="tin_certificate">Upload Tax Identification Number (TIN):</label>
                                <input type="file" id="tin_certificate" name="tin_certificate">

                                <label for="certificate">Upload Certifications (e.g., ISO, FDA):</label>
                                <input type="file" id="certificate" name="certificate">

                                <label for="other_references">Upload Previous Work References (if applicable):</label>
                                <input type="file" id="other_references" name="other_references">

                                <div id="responseDocuments"></div>
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