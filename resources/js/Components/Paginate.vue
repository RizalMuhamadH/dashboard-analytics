<template>
    <div
        v-if="total != 0"
        class="
            grid
            px-4
            py-3
            text-xs
            font-semibold
            tracking-wide
            text-gray-500
            uppercase
            border-t
            dark:border-gray-700
            bg-gray-50
            sm:grid-cols-9
            dark:text-gray-400 dark:bg-gray-800
        "
    >
        <span class="flex items-center col-span-3">
            Showing {{ from }}-{{ to }} of {{ total }}
        </span>
        <span class="col-span-2"></span>
        <!-- Pagination -->
        <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
            <nav aria-label="Table navigation">
                <ul class="inline-flex items-center">
                    <template v-for="(link, index) of links" :key="index">
                        <li v-if="index == 0 && link.url != null">
                            <Link
                                :href="replace(link.url, 'http', 'http')"
                                :class="'px-3 py-1 rounded-md rounded-l-lg'"
                            >
                                <svg
                                    class="w-4 h-4 fill-current"
                                    aria-hidden="true"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                        fill-rule="evenodd"
                                    ></path>
                                </svg>
                            </Link>
                        </li>
                        <li v-if="index > 0 && index < links.length - 1">
                            <Link
                                :href="replace(link.url, 'http', 'http')"
                                :class="activated(link.active)"
                            >
                                {{ link.label }}
                            </Link>
                        </li>

                        <li
                            v-if="index == links.length - 1 && link.url != null"
                        >
                            <Link
                                :href="replace(link.url, 'http', 'http')"
                                :class="'px-3 py-1 rounded-md focus:outline-none'"
                                aria-label="Next"
                            >
                                <svg
                                    class="w-4 h-4 fill-current"
                                    aria-hidden="true"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                        fill-rule="evenodd"
                                    ></path>
                                </svg>
                            </Link>
                        </li>
                    </template>
                </ul>
            </nav>
        </span>
    </div>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
    components: {
        Link,
    },
    props: {
        page: {
            type: Number,
            default: 0,
        },
        perPage: {
            type: Number,
            default: 10,
        },
        total: {
            type: Number,
            default: 0,
        },
        nextPage: {
            type: String,
            default: null,
        },
        previousPage: {
            type: String,
            default: null,
        },
        to: {
            type: Number,
            default: 0,
        },
        from: {
            type: Number,
            default: 0,
        },
        path: {
            type: String,
            default: null,
        },
        links: {
            type: Array,
            default: [],
        },
    },
    methods: {
        activated(status) {
            return status
                ? "px-3 py-1 text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600 rounded-md focus:outline-none focus:shadow-outline-purple"
                : "px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple";
        },
        replace(st, rep, repWith) {
            if(st == null) return '#';
            const result = st.split(rep).join(repWith);
            return result;
        },
    },
};
</script>
