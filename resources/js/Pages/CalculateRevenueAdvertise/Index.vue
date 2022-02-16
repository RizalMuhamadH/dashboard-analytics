<template>
    <Head title="Calculate Revenue Advertise" />

    <BreezeAuthenticatedLayout>
        <h2
            class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
            Calculate Revenue Advertise
        </h2>

        <div class="my-6 flex justify-between">
            <form @submit.prevent="formAction" class="flex space-x-2">
                <litepie-datepicker
                    class="dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray"
                    v-model="date"
                    overlay
                    :formatter="formatter"
                    required
                ></litepie-datepicker>

                <button
                    class="px-4 py-2 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                    type="submit"
                >
                    Record
                </button>
            </form>

            <button
                class="px-4 py-2 flex font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple ease-in-out"
                @click="handleExport"
            >
                <svg
                    v-if="loading"
                    class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                Export
            </button>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                        >
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Page Views</th>
                            <th class="px-4 py-3">Views</th>
                            <th class="px-4 py-3">RPM</th>
                            <th class="px-4 py-3">Revenue</th>
                        </tr>
                    </thead>
                    <tbody
                        class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                    >
                        <tr
                            class="text-gray-700 dark:text-gray-400"
                            v-for="(item, index) of pageOfItems"
                            :key="index"
                        >
                            <td class="px-4 py-3 text-sm">
                                {{ item._id.name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ item.page_views }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ item.views }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ item.rpm }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ item.revenue }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <pagination-collection
                v-if="items.length != 0"
                :items="items"
                :pageSize="10"
                :maxPages="items.length"
                @ChangePage="onChangePage"
                v-model="state"
            ></pagination-collection>
        </div>
    </BreezeAuthenticatedLayout>
</template>
<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import BreezeDropdownTable from "@/Components/TableDropdown.vue";
import PaginationCollection from "@/Components/PaginateCollection.vue";
import LitepieDatepicker from "litepie-datepicker-tw3";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { saveExcel } from "@progress/kendo-vue-excel-export";
export default {
    props: {
        revenue: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            date: {
                startDate: "",
                endDate: "",
            },
            formatter: {
                date: "YYYY-MM-DD",
                month: "MMM",
            },
            items: [],
            pageOfItems: [],
            loading: false,
            state: 0,
        };
    },
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        BreezeDropdownTable,
        LitepieDatepicker,
        PaginationCollection,
    },
    methods: {
        formAction() {
            this.$inertia.get(
                this.route("revenue-advertise.record"),
                { start: this.date.startDate, end: this.date.endDate },
                {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        this.items = page.props.data.sort((a, b) => {
                            if (a._id.name < b._id.name) return -1;
                            if (a._id.name > b._id.name) return 1;
                            return 0;
                        });
                        console.log(this.items);

                        if (this.items.length == 0) {
                            this.pageOfItems = [];
                        }
                        this.state = this.state+1;
                    },
                    onError: (errors) => {
                        console.log(errors);
                    },
                }
            );
        },

        onChangePage(pageOfItems) {
            this.pageOfItems = pageOfItems;
        },

        handleExport() {
            this.loading = true;
            var record = this.items.map((item) => {
                return {
                    name: item._id.name,
                    page_views: item.page_views,
                    views: item.views,
                    rpm: item.rpm,
                    revenue: item.revenue,
                };
            });

            saveExcel({
                data: record,
                fileName:
                    "revenue_programmatic_" +
                    this.date.startDate +
                    "-" +
                    this.date.endDate +
                    ".xlsx",
                columns: [
                    { field: "name", title: "Name" },
                    { field: "page_views", title: "Page Views" },
                    { field: "views", title: "Views" },
                    { field: "rpm", title: "RPM" },
                    { field: "revenue", title: "Revenue" },
                ],
            });
            this.loading = false;
        },
    },
    mounted() {},
};
</script>
