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
        $color = 'warning';
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
	$value = old($name, $value);
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

function old($key, $default = null)
{
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	return isset($_SESSION['old'][$key]) ? htmlspecialchars($_SESSION['old'][$key]) : $default;
}


function breadcrumb($items) {
    // Get current route (you would need some custom routing logic for this)
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    echo '<ul class="flex space-x-2 rtl:space-x-reverse">';

    foreach ($items as $index => $item) {
        $isActive = false;
        if (isset($item['route'])) {
            // If route is defined, check if current route matches
            $isActive = (rtrim($item['route'], '/') === rtrim($currentPath, '/'));
        } elseif (isset($item['url'])) {
            // If URL is defined, check if current URL matches
            $isActive = (rtrim($item['url'], '/') === rtrim($currentPath, '/'));
        }

        // Set classes for active item and other items
        $activeClass = $isActive ? 'text-primary font-semibold' : 'text-[#888EA8]';
        $separatorClass = $index !== 0 ? 'before:content-[\'/\'] ltr:before:mr-1 rtl:before:ml-1' : '';

        echo "<li class=\"$separatorClass\">";
        
        if (isset($item['route'])) {
            // If route is set, create a link
            echo '<a href="' . $item['route'] . '" class="hover:underline ' . $activeClass . '">' . $item['label'] . '</a>';
        } elseif (isset($item['url'])) {
            // If URL is set, create a link
            echo '<a href="' . $item['url'] . '" class="hover:underline ' . $activeClass . '">' . $item['label'] . '</a>';
        } else {
            // If neither route nor URL is set, it's a static breadcrumb (current page)
            echo '<span class="font-semibold ' . $activeClass . '">' . $item['label'] . '</span>';
        }

        echo '</li>';
    }

    echo '</ul>';
}

function badge($status, $outline = false) {
    // Define the base class based on the outline flag
    $baseClass = $outline ? 'badge-outline-' : 'bg-';
    
    // Determine the class based on the status
    switch ($status) {
        case 'Pending':
            $classes = $baseClass . 'dark';
            break;
        case 'Approved':
            $classes = $baseClass . 'primary';
            break;
        case 'Rejected':
            $classes = $baseClass . 'danger';
            break;
        case 'Low':
            $classes = $baseClass . 'warning';
            break;
        case 'High':
            $classes = $baseClass . 'danger';
            break;
        default:
            $classes = $baseClass . 'secondary';
    }

    // Return the badge HTML with the appropriate classes
    echo '<span class="badge ' . $classes . ' rounded-full">' . htmlspecialchars($status) . '</span>';
}










