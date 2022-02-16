<template>
    <div
        v-if="pager.pages && pager.pages.length"
        class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800"
    >
        <span class="flex items-center col-span-3">
            Showing
            {{
                pager.currentPage != 1
                    ? pager.currentPage * pageSize - (pageSize - 1)
                    : pager.currentPage
            }}-{{ pager.currentPage * pageSize }} of
            {{ pager.totalItems }}
        </span>
        <span class="col-span-2"></span>
        <!-- Pagination -->
        <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
            <nav aria-label="Table navigation">
                <ul class="inline-flex items-center">
                    <!-- <template v-for="(link, index) of links" :key="index"> -->
                    <li v-if="pager.currentPage != 1">
                        <button
                            @click="setPage(pager.currentPage - 1)"
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
                        </button>
                    </li>
                    <template v-for="page in pager.pages" :key="page">
                        <li v-if="page === pager.currentPage">
                            <button
                                @click="setPage(page)"
                                :class="activated(pager.currentPage == page)"
                            >
                                {{ page }}
                            </button>
                        </li>

                        <li
                            v-else-if="
                                page === pager.currentPage + 1 ||
                                $page === pager.currentPage + 2 ||
                                page === pager.currentPage - 1 ||
                                page === pager.currentPage - 2 ||
                                page === pager.totalPages ||
                                page === 1
                            "
                        >
                            <button
                                @click="setPage(page)"
                                :class="activated(pager.currentPage == page)"
                            >
                                {{ page }}
                            </button>
                        </li>

                        <li
                            v-if="
                                pager.currentPage < pager.totalPages - 3 &&
                                page === pager.totalPages - 1
                            "
                        >
                            <button disabled>...</button>
                        </li>
                    </template>

                    <li v-if="pager.currentPage != pager.totalPages">
                        <button
                            @click="setPage(pager.currentPage + 1)"
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
                        </button>
                    </li>
                    <!-- </template> -->
                </ul>
            </nav>
        </span>
    </div>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import paginate from "jw-paginate";
export default {
    components: {
        Link,
    },
    props: {
        items: {
            type: Array,
            required: true,
        },
        initialPage: {
            type: Number,
            default: 1,
        },
        pageSize: {
            type: Number,
            default: 10,
        },
        maxPages: {
            type: Number,
            default: 10,
        },
        modelValue: {
            type: Number,
            default: 11,
        },
    },
    data() {
        return {
            pager: {},
            state: 1,
        };
    },
    methods: {
        activated(status) {
            return status
                ? "px-3 py-1 text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600 rounded-md focus:outline-none focus:shadow-outline-purple"
                : "px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple";
        },
        replace(st, rep, repWith) {
            if (st == null) return "#";
            const result = st.split(rep).join(repWith);
            return result;
        },
        setPage(page) {
            const { items, pageSize, maxPages } = this;

            const pager = paginate(items.length, page, pageSize, maxPages);

            const pageOfItems = items.slice(
                pager.startIndex,
                pager.endIndex + 1
            );

            this.pager = pager;

            this.$emit("ChangePage", pageOfItems);
        },
    },
    created() {
        // if(!this.$listeners.pageChanged) {
        //     throw "Missing required event listener: pageChanged";
        // }

        if (this.items && this.items.length) {
            this.setPage(this.initialPage);
        }
    },

    updated() {
        console.log("updated");
        if (this.modelValue != this.state) {
            this.state = this.modelValue;
            if (this.items && this.items.length) {
                this.setPage(this.initialPage);
            }
        }
    },
};
</script>
