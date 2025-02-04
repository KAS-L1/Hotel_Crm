    <?php require("../../app/init.php") ?>
    <?php
    $contract_id = $_POST['contract_id'] ?? null;
    $contract = $DB->SELECT_ONE_WHERE('contracts', "*", ['contract_id' => $contract_id]);
    ?>

    <div id="modalUploadSigned" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto flex items-start justify-center min-h-screen px-4">
        <div class="panel border-0 py-1 px-4 rounded-lg overflow-hidden w-full max-w-sm my-8">
            <div class="flex items-center justify-between p-5 font-semibold text-lg dark:text-white">
                Upload Signed Contract
                <button type="button" onclick="closeModal('modalUploadSigned')" class="text-white-dark hover:text-dark">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="p-5">
                <div id="responseSignedContract"></div>
                <form id="formSignedContract" enctype="multipart/form-data">
                    <?= csrfProtect('generate') ?>
                    <input type="hidden" id="contract_id" name="contract_id" value="<?= $contract['contract_id'] ?>">

                    <div class="relative mb-4">
                        <input type="file" id="contract_file" name="contract_file" class="form-input w-full" accept=".pdf,.doc,.docx" required>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeModal('modalUploadSigned')" class="btn btn-danger">Cancel</button>
                        <button type="submit" id="btnUpload" class="btn btn-primary">Upload</button>
                    </div>

                    <div id="responseSignedContract"></div>
                    <script>
                        $('#formSignedContract').submit(function(e) {
                            e.preventDefault();
                            var file = $('#contract_file')[0].files[0];
                            var formData = new FormData();
                            btnLoading('#btnUpload');
                            formData.append('contract_file', file);
                            formData.append('csrf_token', $('#csrf_token').val());
                            formData.append('contract_id', $('#contract_id').val());
                            console.log(formData);
                            $.ajax({
                                url: '<?= ROUTE('api/contract/upload_contract.php') ?>',
                                type: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                                cache: false,
                                success: function(res) {
                                    $('#responseSignedContract').html(res);
                                    btnLoadingReset('#btnUpload');
                                },
                                error: function(err) {
                                    console.log(err);
                                }
                            });
                        });
                    </script>

                </form>
            </div>
        </div>
    </div>