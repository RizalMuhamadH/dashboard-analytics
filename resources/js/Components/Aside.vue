<template>
    <div>
        <!-- Desktop sidebar -->
        <aside
            class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0 h-full"
        >
            <div class="py-4 text-gray-500 dark:text-gray-400">
                <a
                    class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200"
                    href="#"
                >
                    Analitycs
                </a>
                <ul>
                    <!-- v-if="$page.props.auth.roles.includes('super-admin')" -->
                    <li class="relative px-6 py-3 mt-6">
                        <NavLink
                            :href="route('dashboard.index')"
                            :active="
                                url.startsWith('/dashboard') ? true : false
                            "
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                ></path>
                            </svg>
                            <span class="ml-4">Dashboard</span>
                        </NavLink>
                    </li>

                    <li class="relative px-6 py-3" v-if="$page.props.auth.roles.includes('admin')">
                        <NavLink
                            :href="route('users.index')"
                            :active="url.startsWith('/users') ? true : false"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                ></path>
                            </svg>
                            <span class="ml-4">Users</span>
                        </NavLink>
                    </li>
                    <li class="relative px-6 py-3" v-if="$page.props.auth.roles.includes('admin')">
                        <NavLink
                            :href="route('roles.index')"
                            :active="url.startsWith('/roles') ? true : false"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                ></path>
                            </svg>
                            <span class="ml-4">Roles</span>
                        </NavLink>
                    </li>

                    <li class="relative px-6 py-3" v-if="$page.props.auth.permissions.includes('advertisement-list')">
                        <NavLink
                            :href="route('advertisements.index')"
                            :active="
                                url.startsWith('/advertisements') ? true : false
                            "
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
                                ></path>
                            </svg>
                            <span class="ml-4">Advertise</span>
                        </NavLink>
                    </li>

                    <li class="relative px-6 py-3" v-if="$page.props.auth.permissions.includes('campaign-list')">
                        <NavLink
                            :href="route('campaigns.index')"
                            :active="
                                url.startsWith('/campaigns') ? true : false
                            "
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"
                                ></path>
                            </svg>
                            <span class="ml-4">Campaigns</span>
                        </NavLink>
                    </li>

                    <li class="relative px-6 py-3" v-if="$page.props.auth.permissions.includes('campaign-collection-list')">
                        <NavLink
                            :href="route('campaign-collection.index')"
                            :active="
                                url.startsWith('/campaign-collection')
                                    ? true
                                    : false
                            "
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"
                                ></path>
                            </svg>
                            <span class="ml-4">Campaign Collection</span>
                        </NavLink>
                    </li>

                    <li class="relative px-6 py-3">
                        <NavLink
                            :href="route('campaign-collection.dashboard')"
                            :active="
                                url.startsWith('/campaign-collection/dashboard')
                                    ? true
                                    : false
                            "
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"
                                ></path>
                            </svg>
                            <span class="ml-4">Campaign Dashboard</span>
                        </NavLink>
                    </li>
                </ul>
            </div>
        </aside>
        <!-- Mobile sidebar -->
        <!-- Backdrop -->
        <div
            v-if="isSideMenuOpen"
            class="transition ease-in-out duration-150 fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
        ></div>
        <aside
            class="transition ease-in-out duration-150 fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white dark:bg-gray-800 md:hidden"
            v-if="isSideMenuOpen"
            @click="closeSideMenu"
            @keydown.escape="closeSideMenu"
        >
            <div class="py-4 text-gray-500 dark:text-gray-400">
                <a
                    class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200"
                    href="#"
                >
                    Promedia
                </a>
                <ul class="mt-6">
                    <!-- v-if="$page.props.auth.roles.includes('super-admin')" -->
                    <li class="relative px-6 py-3 mt-6">
                        <NavLink
                            :href="route('dashboard.index')"
                            :active="
                                url.startsWith('/dashboard') ? true : false
                            "
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                ></path>
                            </svg>
                            <span class="ml-4">Dashboard</span>
                        </NavLink>
                    </li>

                    <li class="relative px-6 py-3" v-if="$page.props.auth.roles.includes('admin')">
                        <NavLink
                            :href="route('users.index')"
                            :active="url.startsWith('/users') ? true : false"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                ></path>
                            </svg>
                            <span class="ml-4">Users</span>
                        </NavLink>
                    </li>
                    <li class="relative px-6 py-3" v-if="$page.props.auth.roles.includes('admin')">
                        <NavLink
                            :href="route('roles.index')"
                            :active="url.startsWith('/roles') ? true : false"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                ></path>
                            </svg>
                            <span class="ml-4">Roles</span>
                        </NavLink>
                    </li>

                    <li class="relative px-6 py-3" v-if="$page.props.auth.permissions.includes('advertisement-list')">
                        <NavLink
                            :href="route('advertisements.index')"
                            :active="
                                url.startsWith('/advertisements') ? true : false
                            "
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
                                ></path>
                            </svg>
                            <span class="ml-4">Advertise</span>
                        </NavLink>
                    </li>

                    <li class="relative px-6 py-3" v-if="$page.props.auth.permissions.includes('campaign-list')">
                        <NavLink
                            :href="route('campaigns.index')"
                            :active="
                                url.startsWith('/campaigns') ? true : false
                            "
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"
                                ></path>
                            </svg>
                            <span class="ml-4">Campaigns</span>
                        </NavLink>
                    </li>

                    <li class="relative px-6 py-3" v-if="$page.props.auth.permissions.includes('campaign-collection-list')">
                        <NavLink
                            :href="route('campaign-collection.index')"
                            :active="
                                url.startsWith('/campaign-collection')
                                    ? true
                                    : false
                            "
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"
                                ></path>
                            </svg>
                            <span class="ml-4">Campaign Collection</span>
                        </NavLink>
                    </li>

                    <li class="relative px-6 py-3">
                        <NavLink
                            :href="route('campaign-collection.dashboard')"
                            :active="
                                url.startsWith('/campaign-collection/dashboard')
                                    ? true
                                    : false
                            "
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"
                                ></path>
                            </svg>
                            <span class="ml-4">Campaign Dashboard</span>
                        </NavLink>
                    </li>
                </ul>
            </div>
        </aside>
    </div>
</template>

<script>
import NavLink from "@/Components/NavLink.vue";
export default {
    components: { NavLink },
    props: ["isSideMenuOpen"],
    data() {
        return {
            // isSideMenuOpen: false,
            isPagesMenuOpen: false,
            url: "",
        };
    },
    methods: {
        currentLink() {
            return this.$route.query.page;
        },
    },
    created() {
        this.url = window.location.pathname;
    },
};
</script>
