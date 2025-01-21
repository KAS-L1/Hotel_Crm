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
	?>
		<input type="<?=$type?>" name="<?=$name?>" id="<?= $name?>" value="<?= $value?>" placeholder="<?=$placeholder?>" class="form-input <?=!empty($icon) ? 'ps-10' : 'ps-0' ?> placeholder:text-white-dark <?=$class?>" <?= $attributes?>>
	<?php
}

function passwordInput($name, $value = null, $placeholder = null, $class = null, $icon = true, $attributes = null)
{
    ?>
    <div class="password-input-wrapper relative">
        <input 
            type="password" 
            name="<?= $name ?>" 
            id="<?= $name ?>" 
            value="<?= $value ?>" 
            placeholder="<?= $placeholder ?>" 
            class="form-input <?= !empty($icon) ? 'ps-10' : 'ps-0' ?> placeholder:text-white-dark <?= $class ?>" 
            <?= $attributes ?> 
        />
        <button 
            type="button" 
            class="toggle-password absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-primary focus:outline-none" 
            onmouseover="startCloseTimer('<?= $name ?>', this)"
            onclick="togglePasswordVisibility('<?= $name ?>', this)"
        >
            <i class="fa-solid fa-eye"></i>
        </button>
    </div>
    <script>
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function startCloseTimer(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            
            // Change icon color on hover
            button.classList.add('text-primary');

            // Set timer to revert back after 2 seconds
            
            if (input.type === 'text') {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
            button.classList.remove('text-primary');
            
        }
    </script>
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


function renderMenuItem($menuItem)
{
    $isActive = $menuItem['route'] === $_SERVER['REQUEST_URI']; // Example for active route detection
    $hasChildren = !empty($menuItem['children']);
    ?>
    <li class="menu nav-item">
        <?php if ($hasChildren): ?>
            <button type="button" class="nav-link group <?= $isActive ? 'active' : '' ?>"
                :class="{ 'active': activeDropdown === '<?= $menuItem['id'] ?>' }"
                @click="activeDropdown === '<?= $menuItem['id'] ?>' ? activeDropdown = null : activeDropdown = '<?= $menuItem['id'] ?>'">
                <div class="flex items-center">
                    <i class="<?= $menuItem['icon'] ?> shrink-0 group-hover:text-primary text-current opacity-50"></i>
                    <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                        <?= $menuItem['label'] ?>
                    </span>
                </div>
                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === '<?= $menuItem['id'] ?>' }">
                    <svg width="16" height="16" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </button>
            <ul x-cloak="" x-show="activeDropdown === '<?= $menuItem['id'] ?>'" x-collapse="" class="sub-menu text-gray-500">
                <?php foreach ($menuItem['children'] as $child): ?>
                    <li><a href="<?= $child['route'] ?>" class="<?= $child['route'] === $_SERVER['REQUEST_URI'] ? 'active' : '' ?>"><?= $child['label'] ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <a href="<?= $menuItem['route'] ?>" class="nav-link group <?= $isActive ? 'active' : '' ?>">
                <div class="flex items-center">
                    <i class="<?= $menuItem['icon'] ?> shrink-0 group-hover:text-primary text-current opacity-50"></i>
                    <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                        <?= $menuItem['label'] ?>
                    </span>
                </div>
            </a>
        <?php endif; ?>
    </li>
    <?php
}







