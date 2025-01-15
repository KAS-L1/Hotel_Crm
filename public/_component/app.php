<?php
/**
 * APP CUSTOM COMPONENTS
**/

function Loading($text = null){
    ?>
        <div class="d-flex justify-content-center py-2">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    <?php
}

function DataEmpty($text, $action = null, $url = null){
    ?>
        <div class="text-center py-5">
            <img src="<?=ROOT?>/assets/images/resource/data-empty.png" width="100">
            <p class="my-2 fw-semibold"><?=$text?></p>
            <?php if(!empty($action)){ ?>
                <div>
                    <a href="<?=$url?>" class="btn btn-sm btn-primary px-4"><?=$action?></a>
                </div>
            <?php } ?>
        </div>
    <?php
}

function PageHeader($title){
    ?>
        <div class="page-header py-0">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0"><?=$title?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
}

function UploadImage($text = null){
    ?>
        <label for="image" id="previewFile" class="card card__upload-file mb-0 touchable" title="Choose file">
            <div class="text-center p-4">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                <div><?=$text ?? 'Attach a File'?></div>
                <div class="small text-muted">Click here to upload</div>
            </div>
        </label>
        <input type="file" name="image" id="image" style="opacity:0; height: 1px; width: 100%;" accept=".png, .jpg, .jpeg" onchange="previewFile(event)" required>
        <script>
            function previewFile(event){
                try {
                    var reader = new FileReader();
                    reader.readAsDataURL(event.target.files[0]);
                    var src = URL.createObjectURL(event.target.files[0]);
                  	reader.onload = function(){
                    	$('#previewFile').html(`
                    	    <div class="file__upload-body">
                        	    <img src="${src}" class="file__upload-source">
                            </div>
                    	`);
                  	}
                }catch(err) {
                    $('#previewFile').html(`
                        <div class="text-center p-4">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <div><?=$text ?? 'Attach a File'?></div>
                            <div class="small text-muted">Click here to upload</div>
                        </div>
                    `);
                }
            }
        </script>
    <?php
}





