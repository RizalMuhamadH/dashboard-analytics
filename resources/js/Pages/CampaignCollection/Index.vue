<template>
    <Head title="Campaign Collection" />

    <BreezeAuthenticatedLayout>
        <h2
            class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
            Campaign Collection
        </h2>

        <div class="my-6 flex space-x-3">
            <Link
                v-if="
                    $page.props.auth.permissions.includes(
                        'campaign-collection-create'
                    )
                "
                :href="route('campaign-collection.create')"
                :class="'px-4 py-2 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple'"
            >
                Create
            </Link>
            <div class="w-1/5 mr-6 focus-within:text-purple-500">
                <litepie-datepicker
                    class="focus:border-purple-400 focus:outline-none focus:shadow-outline-purple"
                    v-model="date"
                    overlay
                    :formatter="formatter"
                    :placeholder="placeholder()"
                ></litepie-datepicker>
            </div>

            <!-- <BreezeInputSelect
                        v-model="form.campaign"
                        :option="campaigns"
                    /> -->

            <div class="flex justify-end flex-1 lg:mr-32 space-x-2">
                <div class="w-1/3 focus-within:text-purple-500">
                    <select
                        class="block w-full pr-2 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple text-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        name="campaign"
                        v-model="campaign"
                        type="text"
                        placeholder="Campaign"
                    >
                        <option
                            v-for="item of campaigns"
                            :key="item._id ?? item"
                            class="text-base"
                            :value="item._id ?? item"
                        >
                            {{ item.name ?? item }}
                        </option>
                    </select>
                </div>

                <div class="relative w-1/3 mr-6 focus-within:text-purple-500">
                    <!-- <div class="absolute inset-y-0 flex items-end pl-2">
                        <svg
                            class="w-4 h-4 tex"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                    </div> -->
                    <input
                        class="block w-full pr-2 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple text-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        name="search"
                        v-model="search"
                        type="text"
                        placeholder="Query"
                        @keyup.enter="handleSearch"
                    />
                </div>

                <div
                    class="relative mr-6 h-full focus-within:text-purple-500 flex tooltip tooltip-left tooltip-info"
                    data-tip="specific search using query field:value, example clicks:200. available fields: impressions, clicks, and rate."
                >
                    <svg
                        class="w-5 h-5 text-gray-700 self-center"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                        >
                            <th class="px-4 py-3">Campaign</th>
                            <th class="px-4 py-3">Impressions</th>
                            <th class="px-4 py-3">Clicks</th>
                            <th class="px-4 py-3">Rate</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody
                        class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                    >
                        <tr
                            class="text-gray-700 dark:text-gray-400"
                            v-for="(item, index) of list.data"
                            :key="index"
                        >
                            <td class="px-4 py-3 text-sm">
                                {{ item.campaign.name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ formatNumber(item.impressions) }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ formatNumber(item.clicks) }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ currency(item.rate) }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ formatDate(item.date) }}
                            </td>
                            <td class="px-4 py-3">
                                <div
                                    class="flex items-center space-x-4 text-sm"
                                >
                                    <BreezeAction
                                        :id="item.id"
                                        :section="'campaign-collection'"
                                        :isDelete="
                                            $page.props.auth.permissions.includes(
                                                'campaign-collection-delete'
                                            )
                                        "
                                        :isEdit="
                                            $page.props.auth.permissions.includes(
                                                'campaign-collection-edit'
                                            )
                                        "
                                        :component="false"
                                        :param="item._id"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <BreezePaginate
                :page="list.current_page"
                :perPage="list.per_page"
                :total="list.total"
                :to="list.to"
                :from="list.from"
                :links="list.links"
                :param="
                    $page.url.split('?')[1] != null
                        ? '&' + $page.url.split('?')[1]
                        : ''
                "
            />
        </div>
    </BreezeAuthenticatedLayout>
</template>
<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import BreezeDropdownTable from "@/Components/TableDropdown.vue";
import BreezePaginate from "@/Components/Paginate.vue";
import BreezeAction from "@/Components/Action.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import BreezeInputSelect from "@/Components/InputSelect.vue";
import LitepieDatepicker from "litepie-datepicker-tw3";
import moment from "moment";
export default {
    props: {
        list: {
            type: Object,
            required: true,
        },
        campaigns: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            campaign: "",
            search: "",
            date: {
                startDate: "",
                endDate: "",
            },
            initial: false,
            formatter: {
                date: "YYYY-MM-DD",
                month: "MMM",
            },
        };
    },
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        BreezeDropdownTable,
        BreezeAction,
        BreezePaginate,
        BreezeInputSelect,
        LitepieDatepicker,
    },
    watch: {
        campaign(newValue, oldValue) {
            if(!this.initial) {
                this.handleSearch();
            }
            this.initial = false;
        },
        date(newValue, oldValue) {
            if(!this.initial) {
                this.handleSearch();
            }
            this.initial = false;
        },
    },
    methods: {
        placeholder(){
            if(this.date.startDate != "" && this.date.endDate != "") {
                return `${this.date.startDate} ~ ${this.date.endDate}`;
            } 
        },
        formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        },
        currency(value) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
            }).format(value);
        },
        handleSearch() {
            this.$inertia.get(
                this.route(
                    "campaign-collection.index",
                    {
                        search: this.search,
                        startDate: this.date.startDate,
                        endDate: this.date.endDate,
                        campaign: this.campaign,
                    },
                    { replace: true, preserveState: true }
                )
            );
        },
        active(i) {
            return i == 0
                ? '<span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">Inactive</span>'
                : '<span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Active</span>';
        },
        formatDate(date) {
            return moment(date).format("LL");
        },
    },
    mounted() {
        // console.log(window.location.search);
        const queryString = window.location.search;
        if (queryString) {
            this.initial = true;
            const urlParams = new URLSearchParams(queryString);
            this.search = urlParams.get("search");
            this.date.startDate = urlParams.get("startDate");
            this.date.endDate = urlParams.get("endDate");
            this.campaign = urlParams.get("campaign") == "" ? "0" : urlParams.get("campaign");
        }

        if (this.$page.props.success.message != null) {
            toastr.success(this.$page.props.success.message);
        }
    },
};
</script>
