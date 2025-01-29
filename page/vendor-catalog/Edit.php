<?php
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$product = GetSingleProduct($product_id);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<div>

    <?php
    breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'Product Catalog', 'url' => '/vendor-catalog'],
        ['label' => 'Create', 'url' => '/vendor-catalog/create'],
    ]);
    ?>

    <div class="pt-5">

        <div class="mb-5 grid grid-cols-1 gap-5 lg:grid-cols-3 xl:grid-cols-4">
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <h5 class="text-lg font-semibold dark:text-white-light">Edit Product Image</h5>
                </div>
                <div class="mb-5">
                    <div class="flex flex-col items-center justify-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-[100px] mb-3" id="previewPicture">
                                <img src="<?= DOMAIN ?>/upload/product/<?= isset($_SESSION['PRODUCT_THUMBNAIL']) ? $_SESSION['PRODUCT_THUMBNAIL'] : 'default.png' ?>" alt="image" id="profileImage" class="mb-4 h-24 w-24 rounded-full object-cover">
                                <div class="w-[100px] mb-3">
                                    <!-- Minimalist upload button -->
                                    <label for="image" class="btn btn-primary flex items-center justify-center cursor-pointer">
                                        <i class="bi bi-camera text-md mr-1"></i>
                                        <span>Upload</span>
                                    </label>
                                    <input type="file" id="image" accept=".jpg, .jpeg, .png" class="hidden">
                                </div>
                            </div>

                            <!-- Modal -->
                            <div id="cropModal" class="hidden fixed inset-0 flex items-center justify-center z-50">
                                <div class="bg-white p-6 rounded-md shadow-lg transform scale-95 transition-transform duration-300 ease-out opacity-0 w-[90%] max-w-md"
                                    id="modalContent">
                                    <div id="imageCropContainer" class="w-full h-[300px] overflow-hidden bg-gray-100 flex items-center justify-center border border-gray-300 rounded-md">
                                        <!-- The cropped image will be placed here -->
                                    </div>
                                    <div id="responseUploadImage"></div>
                                    <div class="mt-4 flex justify-end space-x-3">
                                        <button id="cropImage" class="btn btn-success">Crop & Save</button>
                                        <button id="cancelCrop" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </div>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    let cropper;
                                    const cropModal = $('#cropModal');
                                    const modalContent = $('#modalContent');
                                    const imageCropContainer = document.getElementById('imageCropContainer');
                                    const profileImage = $('#profileImage');

                                    // Show modal with animation
                                    const showModal = () => {
                                        cropModal.removeClass('hidden').css({
                                            backgroundColor: 'rgba(0, 0, 0, 0.5)'
                                        });
                                        setTimeout(() => {
                                            modalContent.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
                                        }, 50);
                                    };

                                    // Hide modal with animation
                                    const hideModal = () => {
                                        modalContent.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                                        setTimeout(() => {
                                            cropModal.addClass('hidden');
                                        }, 300);
                                    };

                                    // Handle file input change
                                    $("#image").on('change', function() {
                                        const file = this.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = function(event) {
                                                // Remove previous cropper instance if it exists
                                                if (cropper) cropper.destroy();

                                                // Create a new image element
                                                const img = new Image();
                                                img.src = event.target.result;
                                                img.className = "max-w-full max-h-full object-contain";

                                                // Add the image to the crop container
                                                imageCropContainer.innerHTML = '';
                                                imageCropContainer.appendChild(img);

                                                // Initialize Cropper.js
                                                cropper = new Cropper(img, {
                                                    aspectRatio: 1,
                                                    viewMode: 2,
                                                });

                                                showModal();
                                            };
                                            reader.readAsDataURL(file);
                                        } else {
                                            alert("Please select a valid image file.");
                                        }
                                    });

                                    // Handle crop image confirmation
                                    $("#cropImage").click(function() {
                                        const btn = $(this);
                                        const originalText = btn.text();
                                        btn.text('Processing...').prop('disabled', true);

                                        const canvas = cropper.getCroppedCanvas({
                                            width: 200,
                                            height: 200,
                                        });

                                        const croppedImage = canvas.toDataURL();

                                        // Send cropped image to the server
                                        $.ajax({
                                            url: "../api/vendor-catalog/update_image.php",
                                            type: "POST",
                                            data: {
                                                image: croppedImage
                                            },
                                            success: function(res) {
                                                profileImage.attr('src', croppedImage); // Update the profile image preview
                                                hideModal();
                                                btn.text(originalText).prop('disabled', false);
                                                $('#responseUploadImage').html(res);
                                            },
                                            error: function() {
                                                alert("An error occurred while saving the image.");
                                                btn.text(originalText).prop('disabled', false);
                                            }
                                        });
                                    });

                                    // Cancel cropping
                                    $("#cancelCrop").click(function() {
                                        hideModal();
                                        if (cropper) cropper.destroy(); // Destroy the Cropper instance
                                    });
                                });
                            </script>
                        </div>
                        </ul>
                        <div class="text-xl font-semibold text-primary"><?= "No." . $product['id'] . " ". ucfirst($product['name']) ?></div>

                        <ul class="m-auto mt-5 flex max-w-[160px] flex-col space-y-4 font-semibold text-white-dark">
                            <li class="flex items-center gap-2">
                                <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0">
                                    <path d="M2.3153 12.6978C2.26536 12.2706 2.2404 12.057 2.2509 11.8809C2.30599 10.9577 2.98677 10.1928 3.89725 10.0309C4.07094 10 4.286 10 4.71612 10H15.2838C15.7139 10 15.929 10 16.1027 10.0309C17.0132 10.1928 17.694 10.9577 17.749 11.8809C17.7595 12.057 17.7346 12.2706 17.6846 12.6978L17.284 16.1258C17.1031 17.6729 16.2764 19.0714 15.0081 19.9757C14.0736 20.6419 12.9546 21 11.8069 21H8.19303C7.04537 21 5.9263 20.6419 4.99182 19.9757C3.72352 19.0714 2.89681 17.6729 2.71598 16.1258L2.3153 12.6978Z" stroke="currentColor" stroke-width="1.5"></path>
                                    <path opacity="0.5" d="M17 17H19C20.6569 17 22 15.6569 22 14C22 12.3431 20.6569 11 19 11H17.5" stroke="currentColor" stroke-width="1.5"></path>
                                    <path opacity="0.5" d="M10.0002 2C9.44787 2.55228 9.44787 3.44772 10.0002 4C10.5524 4.55228 10.5524 5.44772 10.0002 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M4.99994 7.5L5.11605 7.38388C5.62322 6.87671 5.68028 6.0738 5.24994 5.5C4.81959 4.9262 4.87665 4.12329 5.38382 3.61612L5.49994 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M14.4999 7.5L14.6161 7.38388C15.1232 6.87671 15.1803 6.0738 14.7499 5.5C14.3196 4.9262 14.3767 4.12329 14.8838 3.61612L14.9999 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <?= "Product " . "- " . $product['product_id'] ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="panel lg:col-span-2 xl:col-span-3">
                <div class="mb-5">
                    <form id="formEditProduct">
                        <?= csrfProtect('generate'); ?>
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                        <div class="mb-5">
                            <h5 class="text-lg font-semibold dark:text-white-light">Edit Product</h5>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <div class="grid flex-1 grid-cols-1 gap-5 sm:grid-cols-2 ml-2">
                                <div>
                                    <label for="name">Product Name</label>
                                    <div class="relative text-white-dark mb-4">
                                        <?= input("text", "name", $product['name'], null, null, null, 'required') ?>
                                    </div>
                                </div>
                                <div>
                                    <label for="category_id">Category</label>
                                    <div class="relative text-white-dark mb-4">
                                        <?= select("category_id", getCategoryOptions(), $product['category'], null, 'required') ?>
                                    </div>
                                </div>
                                <div>
                                    <label for="status">Status</label>
                                    <div class="relative text-white-dark mb-4">
                                        <?= select("status", getProductStatus(), $product['status'], null, 'required') ?>
                                    </div>
                                </div>
                                <div>
                                    <label for="unit_price">Unit Price</label>
                                    <div class="relative text-white-dark mb-4">
                                        <?= input("number", "unit_price", $product['unit_price'], null, null, null, 'required') ?>
                                    </div>
                                </div>
                                <div>
                                    <label for="stock">Stock</label>
                                    <div class="relative text-white-dark mb-4">
                                        <?= input("number", "stock", $product['stock'], null, null, null, 'required') ?>
                                    </div>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="description">Description</label>
                                    <div class="relative text-white-dark mb-4">
                                        <?= textarea("description", $product['description'], null, null, null, null, 'required') ?>
                                    </div>
                                </div>
                                <input type="hidden" name="image" id="productImageInput">
                                <div class="sm:col-span-2">
                                    <?= button("submit", "btnSubmit", "Save Product", null) ?>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div id="responseEditProduct"></div>
                    <script>
                        $('#formEditProduct').submit(function(e) {
                            e.preventDefault();
                            btnLoading('#btnSubmit');
                            $.post('../api/vendor-catalog/update_product.php', $('#formEditProduct').serialize(), function(res) {
                                $('#responseEditProduct').html(res);
                                btnLoadingReset('#btnSubmit');
                            })
                        });
                    </script>
                </div>
            </div>
        </div>

    </div>

</div>