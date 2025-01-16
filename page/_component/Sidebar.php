<div :class="{ 'dark text-white-dark': $store.app.semidark }">
    <nav x-data="sidebar"
        class="sidebar fixed bottom-0 top-0 z-50 h-full min-h-screen w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] transition-all duration-300">
        <div class="h-full bg-white dark:bg-[#0e1726]">
            <div class="flex items-center justify-between px-4 py-3">
                <a href="/dashboard" class="main-logo flex shrink-0 items-center">
                    <img class="ml-[5px] w-8 flex-none rounded-full" src="<?= APP_LOGO_NAV ?>" alt="<?= APP_LOGO_NAV ?>">
                    <span
                        class="align-middle text-2xl font-semibold ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light lg:inline"><?= APP_NAME ?></span>
                </a>
                <a href="javascript:;"
                    class="collapse-icon flex h-8 w-8 items-center rounded-full transition duration-300 hover:bg-gray-500/10 rtl:rotate-180 dark:text-white-light dark:hover:bg-dark-light/10"
                    @click="$store.app.toggleSidebar()">
                    <svg class="m-auto h-5 w-5" width="20" height="20" viewbox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            </div>
            <ul class="perfect-scrollbar relative h-[calc(100vh-80px)] space-y-0.5 overflow-y-auto overflow-x-hidden p-4 py-0 font-semibold"
                x-data="{ activeDropdown: 'dashboard' }">

                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{ 'active': activeDropdown === 'dashboard' }"
                        @click="activeDropdown === 'dashboard' ? activeDropdown = null : activeDropdown = 'dashboard'">
                        <div class="flex items-center">
                            <i class="fa-solid fa-home shrink-0 group-hover:text-primary text-current opacity-50"></i>


                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Dashboard</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'dashboard' }">
                            <svg width="16" height="16" viewbox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak="" x-show="activeDropdown === 'dashboard'" x-collapse=""
                        class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('data-analytics') }}" class="active">Data Analytics</a>
                        </li>
                        <li>
                            <a href="{{ route('predictive-analytics') }}">Predictive Analytics</a>
                        </li>
                    </ul>
                </li>

                <h2
                    class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>LOGISTICS</span>
                </h2>

                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'procurement' }"
                                @click="activeDropdown === 'procurement' ? activeDropdown = null : activeDropdown = 'procurement'">
                                <div class="flex items-center">

                                    <i
                                        class="fa fa-cart-shopping shrink-0 group-hover:text-primary text-current opacity-50"></i>


                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Procurement</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'procurement' }">
                                    <svg width="16" height="16" viewbox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak="" x-show="activeDropdown === 'procurement'" x-collapse=""
                                class="sub-menu text-gray-500">
                                <li>
                                    <a href="{{ route('requisitions.index') }}">Purchase Requisition</a>
                                </li>
                                <li>
                                    <a href="#">Budget Approval</a>
                                </li>
                                <li>
                                    <a href="#">Purchase Order</a>
                                </li>
                                <li>
                                    <a href="#">Request For Qoute</a>
                                </li>
                                <li>
                                    <a href="#">Contract Management</a>
                                </li>
                                <li>
                                    <a href="#">Invoice</a>
                                </li>
                            </ul>
                        </li>

                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'audit' }"
                                @click="activeDropdown === 'audit' ? activeDropdown = null : activeDropdown = 'audit'">
                                <div class="flex items-center">
                                    <i
                                        class="fa fa-clipboard-list shrink-0 group-hover:text-primary text-current opacity-50"></i>

                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Audit
                                        Management</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'audit' }">
                                    <svg width="16" height="16" viewbox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak="" x-show="activeDropdown === 'audit'" x-collapse=""
                                class="sub-menu text-gray-500">
                                <li>
                                    <a href="#">Audit Schedule</a>
                                </li>
                                <li>
                                    <a href="#">Audit Findings</a>
                                </li>
                                <li>
                                    <a href="#">Audit Logs</a>
                                </li>
                                <li>
                                    <a href="#">Audit History</a>
                                </li>
                                <li>
                                    <a href="#">Reports</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="apps-calendar.html" class="group">
                                <div class="flex items-center">
                                    <i
                                        class="fa fa-file-contract shrink-0 group-hover:text-primary text-current opacity-50"></i>

                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Document
                                        Tracking</span>
                                </div>
                            </a>
                        </li>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'vendor' }"
                                @click="activeDropdown === 'vendor' ? activeDropdown = null : activeDropdown = 'vendor'">
                                <div class="flex items-center">
                                    <i
                                        class="fa fa-building shrink-0 group-hover:text-primary text-current opacity-50"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Vendor
                                        Portal</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'vendor' }">
                                    <svg width="16" height="16" viewbox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak="" x-show="activeDropdown === 'vendor'" x-collapse=""
                                class="sub-menu text-gray-500">
                                <li>
                                    <a href="#">Dashboard</a>
                                </li>
                                <li>
                                    <a href="#">Purchase Order</a>
                                </li>
                                <li>
                                    <a href="#">Request For Qoute</a>
                                </li>
                                <li>
                                    <a href="#">Product Catalog</a>
                                </li>
                                <li>
                                    <a href="#">Invoice</a>
                                </li>
                                <li>
                                    <a href="#">Delivery & Shipment Updates</a>
                                </li>
                                <li>
                                    <a href="#">Help & Support</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <h2
                    class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>SETTINGS</span>
                </h2>

                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'users' }"
                        @click="activeDropdown === 'users' ? activeDropdown = null : activeDropdown = 'users'">
                        <div class="flex items-center">
                            <i class="fa fa-users shrink-0 group-hover:text-primary text-current opacity-50"></i>

                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">User
                                Management</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'users' }">
                            <svg width="16" height="16" viewbox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak="" x-show="activeDropdown === 'users'" x-collapse=""
                        class="sub-menu text-gray-500">
                        <li>
                            <a href="#">Role</a>
                        </li>
                        <li>
                            <a href="#">Permission</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>