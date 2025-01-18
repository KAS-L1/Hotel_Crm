<?php include_once("public/_template/Header.php") ?>
<?php if (isset($_COOKIE['_xsrf-token'])) redirect("/dashboard?res=redirect-register") ?>

<div x-data="auth">

    <div class="relative flex min-h-screen items-center justify-center bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16">

        <div class="relative w-full max-w-[870px] rounded-md bg-[linear-gradient(45deg,#fff9f9_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,_#fff9f9_100%)] p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]">
            <div class="relative flex flex-col justify-center rounded-md bg-white/60 dark:bg-black/50 px-6 lg:min-h-[758px] py-20 shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]">

                <div class="mx-auto w-full max-w-[440px]">
                    <div class="mb-10">
                        <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">Sign in</h1>
                        <p class="text-base font-bold leading-normal text-white-dark">Enter your email and password to login</p>
                    </div>
                    <div id="responseRegister"></div>
                    <form id="formRegister" class="space-y-5 dark:text-white">
                        <?= csrfProtect('generate'); ?>
                        <div class="text-md">Personal Information <?= required() ?></div>
                        <div class="grid grid-cols-2 gap-6 pt-5 lg:grid-cols-2">
                            <div>
                                <label for="first_name">First Name</label>
                                <?= input('text', "first_name", $_SESSION['old']['first_name'] ?? null, null, null, null, "required") ?>
                            </div>
                            <div>
                                <label for="last_name">Last Name</label>
                                <?= input('text', "last_name", $_SESSION['old']['last_name'] ?? null, null, null, null, "required") ?>
                            </div>
                        </div>
                        <div>
                            <label for="company">Company</label>
                            <?= input('text', "company", $_SESSION['old']['company'] ?? null, null, null, null, "required") ?>
                        </div>
                        <div>
                            <label for="contact">Contact</label>
                            <?= input('text', "contact", $_SESSION['old']['contact'] ?? null, null, null, null, "required") ?>
                        </div>
                        <div>
                            <label for="address">Address</label>
                            <?= input('text', "address", $_SESSION['old']['address'] ?? null, null, null, null, "required") ?>
                        </div>
                        <div class="text-md">Account Information <?= required() ?></div>
                        <div>
                            <label for="email">Email</label>
                            <?= input('text', "email", $_SESSION['old']['email'] ?? null, null, null, null, "required") ?>
                        </div>
                        <div>
                            <label for="username">Username</label>
                            <?= input('text', "username", $_SESSION['old']['username'] ?? null, null, null, null, "required") ?>
                        </div>
                        <div>
                            <label for="password">Password</label>
                            <?= passwordInput("password", null, null, null, null, "required") ?>
                        </div>
                        <div>
                            <label for="confirm_password">Confirm Password</label>
                            <?= passwordInput("confirm_password", null, null, null, null, "required") ?>
                        </div>
                        <?= button("submit", "btnRegister", "Register", null, true) ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(e) {
        // default
        var els = document.querySelectorAll(".selectize");
        els.forEach(function(select) {
            NiceSelect.bind(select);
        });
    });
</script>

<?php include_once("public/_template/Footer.php") ?>