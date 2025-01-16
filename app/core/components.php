<?php

/**
 * REUSABLE GLOBAL COMPONENTS
 **/

function refresh($seconds = null)
{
	if (!isset($seconds)) {
		?>
		<script>
			location.reload()
		</script>
		<?php
	} else {
		?>
		<script>
			setTimeout(function () {
				location.reload()
			}, <?= $seconds ?>)
		</script>
		<?php
	}
}

function redirect($url, $seconds = null)
{
	if (!isset($seconds)) {
		?>
		<script>
			window.location.href = "<?= $url ?>"
		</script>
		<?php
	} else {
		?>
		<script>
			setTimeout(function () {
				location.href = "<?= $url ?>"
			}, "<?= $seconds ?>")
		</script>
		<?php
	}
}


// COMPONENTS
function required()
{
	?><span class="text-danger app__required">*</span><?php
}

function alert($type, $text)
{
	?>
	<div class="alert alert-<?= $type ?> app__alert animate__animated animate__fadeIn"><?= $text ?></div>
	<?php
}

function success($text)
{
	?>
	<div class="alert alert-success app__alert animate__animated animate__fadeIn"><?= $text ?></div>
	<?php
}

function warning($text)
{
	?>
	<div class="alert alert-warning app__alert animate__animated animate__fadeIn"><?= $text ?></div>
	<?php
}

function error($text)
{
	?>
	<div class="alert alert-danger app__alert animate__animated animate__fadeIn"><?= $text ?></div>
	<?php
}

function swal($type, $title, $buttonText = null, $text = null)
{
	if ($type == "success") {
		$color = 'var(--bs-success)';
	} else if ($type == "warning") {
		$color = 'var(--bs-warning)';
	} else if ($type == "error") {
		$color = 'var(--bs-danger)';
	} else {
		$color = 'var(--bs-primary)';
	}
	?>
	<script>
		Swal.fire({
			icon: '<?= $type ?>',
			title: '<?= $title ?>',
			text: '<?= $text ?>',
			confirmButtonText: '<?= isset($buttonText) ? $buttonText : 'Okay' ?>',
			confirmButtonColor: '<?= $color ?>'
		})
	</script>
	<?php
}

function swalAction($url, $type, $title, $buttonText = null, $text = null)
{
	if ($type == "success") {
		$color = 'var(--bs-success)';
	} else if ($type == "warning") {
		$color = 'var(--bs-warning)';
	} else if ($type == "error") {
		$color = 'var(--bs-danger)';
	} else {
		$color = 'var(--bs-primary)';
	}
	?>
	<script>
		Swal.fire({
			icon: '<?= $type ?>',
			title: '<?= $title ?>',
			text: '<?= $text ?>',
			confirmButtonText: '<?= isset($buttonText) ? $buttonText : 'Okay' ?>',
			confirmButtonColor: '<?= $color ?>'
		}).then(function () {
			window.location = '<?= $url ?>';
		})
	</script>
	<?php
}

function toast($type, $message, $position = null, $timer = null)
{
	if ($type == "success") {
		$color = 'success';
	} else if ($type == "warning") {
		$color = 'warning)';
	} else if ($type == "error") {
		$color = 'danger';
	} else {
		$color = 'primary';
	}
	?>
	<script>
		$(function () {
			var Toast = Swal.mixin({
				toast: true,
				position: '<?= isset($position) ? $position : 'top-right' ?>',
				showConfirmButton: false,
				timerProgressBar: true,
				timer: <?= isset($timer) ? $timer : 2000 ?>,
				customClass: {
					popup: 'color-<?=$color?>',
				},
			});
			Toast.fire({
				icon: '<?= $type ?>',
				title: '<?= $message ?>'
			})
		})
	</script>
	<?php
}


function input($type, $name, $value= null, $placeholder = null, $class = null, $icon = true, $attributes= null)
{
	?>
		<input type="<?=$type?>" name="<?=$name?>" id="<?= $name?>" value="<?= $value?>" placeholder="<?=$placeholder?>" class="form-input <?=!empty($icon) ? 'ps-10' : 'ps-0' ?> placeholder:text-white-dark <?=$class?>" <?= $attributes?>>
	<?php
}

function button($type, $id, $text, $class = null, $width = null, $attributes= null)
{
	?>
		<button type="<?=$type?>" id="<?=$id?>" class="btn btn-primary !mt-6 <?=!empty($width) ? 'w-full' : '' ?> border-0 uppercase <?=$class?>" <?= $attributes?>>
			<?= $text ?>
		</button>
	<?php
}
