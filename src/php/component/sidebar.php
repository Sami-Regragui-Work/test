<aside
    id="sidebar"
    class="body__aside fixed top-0 left-0 z-40 
           transition-all duration-300
           sm:h-screen sm:w-[60px] sm:overflow-hidden
           sm:hover:w-[256px]
           sm:translate-x-0
           w-full h-[60px] flex overflow-hidden
           sm:flex-col sm:overflow-x-hidden"
    aria-label="Sidebar">
    <nav
        class="h-full px-3 py-4 overflow-hidden bg-gray-50 dark:bg-gray-800
               w-full flex sm:block">
        <ul class="space-y-2 font-medium relative h-full flex gap-2">
            <!-- Dashboard -->
            <li>
                <a
                    href="#"
                    data-section="dashboard"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <!-- home / dashboard icon -->
                    <svg
                        class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24">
                        <path
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 10.5 12 3l9 7.5M5 10.5V21h5v-5h4v5h5V10.5" />
                    </svg>
                    <span class="flex-1 ms-5 whitespace-nowrap hidden sm:inline">Dashboard</span>
                </a>
            </li>

            <!-- Patients -->
            <li>
                <a
                    href="#"
                    data-section="patients"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <!-- user-group icon (patients) -->
                    <svg
                        class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24">
                        <path
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 7.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm11 0a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM3 20.25v-1.5A3.75 3.75 0 0 1 6.75 15h0A3.75 3.75 0 0 1 10.5 18.75v1.5M13.5 20.25v-1.5A3.75 3.75 0 0 1 17.25 15h0A3.75 3.75 0 0 1 21 18.75v1.5" />
                    </svg>
                    <span class="flex-1 ms-5 whitespace-nowrap hidden sm:inline">Patients</span>
                </a>
            </li>

            <!-- Doctors -->
            <li>
                <a
                    href="#"
                    data-section="doctors"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <!-- identification / badge icon (doctors) -->
                    <svg
                        class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24">
                        <path
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M7.5 3h9A1.5 1.5 0 0 1 18 4.5v15L12 18l-6 1.5v-15A1.5 1.5 0 0 1 7.5 3Z" />
                        <path
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9.75 8.25h4.5M10.5 11.25h3M10.5 14.25h3" />
                    </svg>
                    <span class="flex-1 ms-5 whitespace-nowrap hidden sm:inline">Doctors</span>
                </a>
            </li>

            <!-- Departments -->
            <li>
                <a
                    href="#"
                    data-section="departments"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <!-- squares grid (departments) -->
                    <svg
                        class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24">
                        <path
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z" />
                    </svg>
                    <span class="flex-1 ms-5 whitespace-nowrap hidden sm:inline">Departments</span>
                </a>
            </li>

            <!-- Bottom (language + login) -->
            <ul
                class="stick-bottom w-full">
                <!-- Language -->
                <li>
                    <a
                        href="#language"
                        class="flex  p-2 text-gray-900 rounded-lg dark:text-white group">
                        <!-- language / globe icon -->
                        <svg
                            class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24">
                            <path
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Z" />
                            <path
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4.5 9h15M4.5 15h15M12 3a15.5 15.5 0 0 1 0 18M12 3a15.5 15.5 0 0 0 0 18" />
                        </svg>

                        <!-- buttons group -->
                        <span
                            class="flex-1 ms-5 flex -mt-1 gap-2">
                            <button
                                type="button"
                                class="px-2 py-1 text-xs border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-500 dark:hover:bg-gray-600">
                                En
                            </button>
                            <button
                                type="button"
                                class="px-2 py-1 text-xs border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-500 dark:hover:bg-gray-600">
                                Fr
                            </button>
                            <button
                                type="button"
                                class="px-2 py-1 text-xs border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-500 dark:hover:bg-gray-600">
                                Ar
                            </button>
                        </span>
                    </a>
                </li>

                <!-- Log in -->
                <li>
                    <a
                        href="#login"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <!-- arrow-right-on-rectangle / login icon -->
                        <svg
                            class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24">
                            <path
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 8.25 12.75 12 9 15.75M12.75 12H3.75M14.25 4.5h3a2.25 2.25 0 0 1 2.25 2.25v10.5A2.25 2.25 0 0 1 17.25 19.5h-3" />
                        </svg>
                        <span class="flex-1 ms-5 whitespace-nowrap hidden sm:inline">Log in</span>
                    </a>
                </li>
            </ul>
        </ul>
    </nav>
</aside>