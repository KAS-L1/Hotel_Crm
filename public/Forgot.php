<?php include_once("public/_template/Header.php") ?>

<div x-data="auth">


    <div class="relative flex min-h-screen items-center justify-center bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16">

        <div class="relative w-full max-w-[870px] rounded-md bg-[linear-gradient(45deg,#fff9f9_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,_#fff9f9_100%)] p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]">
            <div class="relative flex flex-col justify-center rounded-md bg-white/60 dark:bg-black/50 px-6 lg:min-h-[758px] py-20 shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]">


                <div class="mx-auto w-full max-w-[440px]">
                    <div class="mb-10">
                        <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">Forgot</h1>
                        <p class="text-base font-bold leading-normal text-white-dark">Enter your email to recover your account</p>
                    </div>
                    <form id="formForgot">
                        <div>
                            <label for="email" class="dark:text-white">Email</label>
                            <div class="relative text-white-dark">
                                <?= input("text", "email") ?>
                                <span class="absolute start-4 top-1/2 -translate-y-1/2">
                                    <svg width="18" height="18" viewbox="0 0 18 18" fill="none">
                                        <path opacity="0.5" d="M10.65 2.25H7.35C4.23873 2.25 2.6831 2.25 1.71655 3.23851C0.75 4.22703 0.75 5.81802 0.75 9C0.75 12.182 0.75 13.773 1.71655 14.7615C2.6831 15.75 4.23873 15.75 7.35 15.75H10.65C13.7613 15.75 15.3169 15.75 16.2835 14.7615C17.25 13.773 17.25 12.182 17.25 9C17.25 5.81802 17.25 4.22703 16.2835 3.23851C15.3169 2.25 13.7613 2.25 10.65 2.25Z" fill="currentColor"></path>
                                        <path d="M14.3465 6.02574C14.609 5.80698 14.6445 5.41681 14.4257 5.15429C14.207 4.89177 13.8168 4.8563 13.5543 5.07507L11.7732 6.55931C11.0035 7.20072 10.4691 7.6446 10.018 7.93476C9.58125 8.21564 9.28509 8.30993 9.00041 8.30993C8.71572 8.30993 8.41956 8.21564 7.98284 7.93476C7.53168 7.6446 6.9973 7.20072 6.22761 6.55931L4.44652 5.07507C4.184 4.8563 3.79384 4.89177 3.57507 5.15429C3.3563 5.41681 3.39177 5.80698 3.65429 6.02574L5.4664 7.53583C6.19764 8.14522 6.79033 8.63914 7.31343 8.97558C7.85834 9.32604 8.38902 9.54743 9.00041 9.54743C9.6118 9.54743 10.1425 9.32604 10.6874 8.97558C11.2105 8.63914 11.8032 8.14522 12.5344 7.53582L14.3465 6.02574Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <?= button("submit", "btnForgot", "Recover Password", null, true) ?>
                        <div class="mt-5 py-5 text-center dark:text-white">
                            Remember password?
                            <a href="/login?res=forgot" class="uppercase text-primary underline transition hover:text-black dark:hover:text-white">Login</a>
                        </div>
                    </form>
                    <div id="responseForgot"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once("public/_template/Footer.php") ?>