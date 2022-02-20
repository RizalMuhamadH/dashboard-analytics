<template>
    <Head title="Campaign Page" />

    <BreezeAuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-visible shadow-sm sm:rounded-lg">
                    <div class="px-4 py-3 mb-8 rounded-lg">
                        <div class="flex space-x-4 overflow-visible">
                            <div class="basis-2/4">
                                <div class="dropdown">
                                    <label tabindex="0" class="m-1 btn"
                                        >Filter Graph</label
                                    >
                                    <ul
                                        tabindex="0"
                                        class="p-2 shadow menu dropdown-content bg-base-100 rounded-box w-52"
                                    >
                                        <li @click="filteringGraph('All')">
                                            <a>All</a>
                                        </li>
                                        <li @click="filteringGraph('Clicks')">
                                            <a>Clicks</a>
                                        </li>
                                        <li
                                            @click="
                                                filteringGraph('Impressions')
                                            "
                                        >
                                            <a>Impression</a>
                                        </li>
                                        <li @click="filteringGraph('CTR')">
                                            <a>CTR</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="dropdown">
                                    <label tabindex="0" class="m-1 btn"
                                        >Export</label
                                    >
                                    <ul
                                        tabindex="0"
                                        class="p-2 shadow menu dropdown-content bg-base-100 rounded-box w-52"
                                    >
                                        <li @click="handleExport('csv')">
                                            <a>CSV</a>
                                        </li>
                                        <li @click="handleExport('xlsx')">
                                            <a>XLSX</a>
                                        </li>
                                    </ul>
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
                                </div>
                            </div>

                            <div class="basis-2/4">
                                <litepie-datepicker
                                    class="dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray"
                                    v-model="date"
                                    overlay
                                    :formatter="formatter"
                                    required
                                    :placeholder="
                                        date.startDate + ' ~ ' + date.endDate
                                    "
                                ></litepie-datepicker>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="p-6 min-w-0 bg-white rounded-lg shadow-xs dark:bg-gray-800"
                >
                    <BreezeChart
                        v-if="this.chartData.datasets.length > 0"
                        :chartData="chartData"
                        :chartOptions="chartOptions"
                        :chartType="chartType"
                        :style="'h-60'"
                    />
                </div>
                <!-- </div> -->
            </div>
        </div>
        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    tabindex="0"
                    class="collapse bg-white rounded-lg shadow-xs dark:bg-gray-800 collapse-plus"
                >
                    <input type="checkbox" />
                    <div
                        class="collapse-title text-xl font-medium text-gray-500 dark:text-gray-400"
                    >
                        Advanced Filtering
                    </div>
                    <div class="collapse-content">
                        <div class="px-4 py-3 mb-8 rounded-lg">
                            <div class="flex space-x-4">
                                <div class="basis-1/6">
                                    <BreezeInputSelect
                                        :name="'Column'"
                                        v-model="filter.column"
                                        :option="columns"
                                        :required="true"
                                    />
                                </div>
                                <div class="basis-1/6">
                                    <BreezeInputSelect
                                        :name="'Operator'"
                                        v-model="filter.operator"
                                        :option="operators"
                                        :required="true"
                                    />
                                </div>
                                <div class="basis-2/4">
                                    <BreezeInput
                                        :type="'text'"
                                        :name="'Parameter'"
                                        :placeholder="'Masukan Parameter'"
                                        v-model="filter.parameter"
                                        :required="true"
                                        class="text-black dark:text-white"
                                        autofocus
                                    />
                                </div>
                                <div class="basis-1/4">
                                    <label class="block mb-2"> </label>
                                    <button
                                        type="button"
                                        class="btn btn-sm bg-blue-500 mt-4"
                                        @click="addNewFiltering"
                                    >
                                        Add New
                                    </button>
                                </div>
                            </div>
                            <h1 class="text-black mt-3">List Filter</h1>
                            <div class="flex flex-wrap mt-2">
                                <div
                                    v-for="(filters, index) in filterings"
                                    :key="index"
                                >
                                    <div class="mr-4 mb-2">
                                        <button
                                            class="btn"
                                            @click="removeFiltering(index)"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                class="inline-block w-4 h-4 stroke-current"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                ></path>
                                            </svg>
                                            <div class="badge">
                                                {{ filters.column }}
                                                {{ filters.operator }}
                                                {{ filters.parameter }}
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-5 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <table
                    class="table table-compact w-full mt-3 whitespace-no-wrap"
                >
                    <thead class="text-black bg-white">
                        <tr
                            class="dark:text-black text-xs font-semibold tracking-wide text-left text-white uppercase border-b dark:border-gray-700 dark:bg-white"
                        >
                            <th
                                @click="changeOrder('name')"
                                class="hover:cursor-pointer"
                            >
                                Campaign
                            </th>
                            <th
                                @click="changeOrder('start_date')"
                                class="hover:cursor-pointer"
                            >
                                Start
                            </th>
                            <th
                                @click="changeOrder('end_date')"
                                class="hover:cursor-pointer"
                            >
                                End
                            </th>
                            <th
                                @click="changeOrder('deposit')"
                                class="hover:cursor-pointer"
                            >
                                Budget
                            </th>
                            <th
                                @click="changeOrder('goal')"
                                class="hover:cursor-pointer"
                            >
                                Goal(%)
                            </th>
                            <th
                                @click="changeOrder('impressions')"
                                class="hover:cursor-pointer"
                            >
                                Impression
                            </th>
                            <th
                                @click="changeOrder('clicks')"
                                class="hover:cursor-pointer"
                            >
                                Clicks
                            </th>
                            <th
                                @click="changeOrder('rate')"
                                class="hover:cursor-pointer"
                            >
                                Rate
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                    >
                        <tr
                            class="text-gray-700 dark:text-gray-400"
                            v-for="(item, index) of data"
                            :key="index"
                        >
                            <td
                                class="px-4 py-3 text-sm hover:text-blue-500 cursor-pointer"
                                @click="redirectPage(item.campaign._id)"
                            >
                                {{ item.campaign.name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ formatDateTime(item.campaign.start_date) }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ formatDateTime(item.campaign.end_date) }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ currency(item.campaign.deposit) }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ item.campaign.goal }}
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
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import BreezeChart from "@/Components/Chart.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeInputSelect from "@/Components/InputSelect.vue";
import LitepieDatepicker from "litepie-datepicker-tw3";
import PaginationCollection from "@/Components/PaginateCollection.vue";
import moment from "moment";
import { saveExcel } from "@progress/kendo-vue-excel-export";

export default {
    props: ["statistics", "campaigns", "operators", "start", "end"],
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        BreezeChart,
        BreezeInput,
        BreezeInputSelect,
        LitepieDatepicker,
        PaginationCollection,
    },

    data() {
        return {
            data: this.campaigns,
            state: false,
            loading: false,
            chartType: "line",
            date: {
                startDate: "",
                endDate: "",
            },
            graphic: "",
            sorting: {
                column: "",
                ascending: "",
            },
            chartData: {
                labels: [],
                datasets: [],
            },
            chartOptions: {
                responsive: true,
                legend: {
                    display: false,
                },
                tooltips: {
                    mode: "index",
                    intersect: false,
                },
                hover: {
                    mode: "nearest",
                    intersect: true,
                },
                scales: {
                    x: {
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: "Month",
                        },
                    },
                    y: {
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: "Value",
                        },
                    },
                },
            },
            filterings: [],
            filter: {
                column: null,
                operator: null,
                parameter: null,
            },
            formatter: {
                date: "YYYY-MM-DD",
                month: "MMM",
            },
            columns: [
                "name",
                "deposit",
                "goal",
                "impression",
                "clicks",
                "rate",
            ],
            dataset: [
                {
                    label: "Clicks",
                    backgroundColor: "#3f83f8",
                    borderColor: "#3f83f8",
                    data: [],
                    fill: false,
                },
                {
                    label: "Impressions",
                    backgroundColor: "#e02424",
                    borderColor: "#e02424",
                    data: [],
                    fill: false,
                },
                {
                    label: "CTR",
                    backgroundColor: "#0e9f6f",
                    borderColor: "#0e9f6f",
                    data: [],
                    fill: false,
                },
            ],
        };
    },
    watch: {
        date(newValue, oldValue) {
            // this.date.startDate = newValue.startDate;
            // this.date.endDate = newValue.endDate;
            this.filterAction();
        },
        filterings: {
            handler(newValue, oldValue) {
                this.filterArray();
            },
            deep: true,
        },
    },
    computed: {},
    methods: {
        formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        },
        currency(value) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
            }).format(value);
        },
        formatDate(date) {
            return moment(date).format("LL");
        },
        formatDateTime(date) {
            return moment(date).format("LLL");
        },
        addNewFiltering: function () {
            if (
                this.filter.column == null ||
                this.filter.parameter == null ||
                this.filter.operator == null
            ) {
            } else {
                this.filterings.push({ ...this.filter });
                this.filter.column = null;
                this.filter.parameter = null;
                this.filter.operator = null;
            }
        },
        removeFiltering: function (index) {
            this.filterings.splice(index, 1);
        },
        redirectPage: function (id) {
            // window.location.href = "/campaign-collection/detail/"+id
            this.$inertia.get(
                this.route("campaign-collection.detail", { id: id }),
                {},
                {
                    onSuccess: (page) => {},
                    onError: (errors) => {
                        console.log(errors);
                    },
                }
            );
        },

        filterArray() {
            let filterings = this.filterings;
            let campaigns = this.campaigns;
            this.data =
                filterings.length > 0
                    ? campaigns.filter(function (campaign) {
                          let result = false;
                          filterings.forEach(function (filtering) {
                              if (filtering.operator == "==") {
                                  if (
                                      campaign[filtering.column] ==
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                                  if (
                                      campaign.campaign[filtering.column] ==
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                              } else if (filtering.operator == "!=") {
                                  if (
                                      campaign[filtering.column] !=
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                                  if (
                                      campaign.campaign[filtering.column] !=
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                              } else if (filtering.operator == ">") {
                                  if (
                                      campaign[filtering.column] >
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                                  if (
                                      campaign.campaign[filtering.column] >
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                              } else if (filtering.operator == "<") {
                                  if (
                                      campaign[filtering.column] <
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                                  if (
                                      campaign.campaign[filtering.column] <
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                              } else if (filtering.operator == "<=") {
                                  if (
                                      campaign[filtering.column] <=
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                                  if (
                                      campaign.campaign[filtering.column] <=
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                              } else if (filtering.operator == ">=") {
                                  if (
                                      campaign[filtering.column] >=
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                                  if (
                                      campaign.campaign[filtering.column] >=
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                              }
                          });
                          return result;
                      })
                    : campaigns;
            return campaigns;
        },

        filterAction() {
            this.$inertia.get(
                this.route("dashboard.index"),
                {
                    start: this.date.startDate,
                    end: this.date.endDate,
                    graphic: this.graphic,
                },
                {
                    onSuccess: (page) => {},
                    onError: (errors) => {
                        console.log(errors);
                    },
                }
            );
        },

        filteringGraph(graphic) {
            this.graphic = graphic;
            this.$inertia.get(
                this.route("dashboard.index"),
                {
                    start: this.date.startDate,
                    end: this.date.endDate,
                    graphic: graphic,
                },
                {
                    onSuccess: (page) => {},
                    onError: (errors) => {
                        console.log(errors);
                    },
                }
            );
        },

        changeOrder(column) {
            if (this.sorting.column != column) {
                this.sorting.column = column;
                this.sorting.ascending = true;
            } else if (this.sorting.column == column) {
                if (this.sorting.ascending == true) {
                    this.sorting.ascending = false;
                }
            }
            this.$inertia.get(
                this.route("dashboard.index"),
                {
                    start: this.date.startDate,
                    end: this.date.endDate,
                    graphic: this.graphic,
                    column: this.sorting.column,
                    ascending: this.sorting.ascending,
                },
                {
                    onSuccess: (page) => {},
                    onError: (errors) => {
                        console.log(errors);
                    },
                }
            );
        },

        handleExport(type) {
            this.loading = true;
            var record = this.data.map((item) => {
                return {
                    name: item.campaign.name,
                    start_date: this.formatDateTime(item.campaign.start_date),
                    end_date: this.formatDateTime(item.campaign.end_date),
                    deposit: item.campaign.deposit,
                    goal: item.campaign.goal + "%",
                    impressions: item.impressions,
                    clicks: item.clicks,
                    rate: item.rate,
                };
            });

            if (type == "xlsx") {
                saveExcel({
                    data: record,
                    fileName:
                        "analytics_ayo_media_network" +
                        this.date.startDate +
                        "-" +
                        this.date.endDate +
                        ".xlsx",
                    columns: [
                        { field: "name", title: "Name" },
                        { field: "start_date", title: "Start Date" },
                        { field: "end_date", title: "End Date" },
                        { field: "deposit", title: "Deposit" },
                        { field: "goal", title: "Goal" },
                        { field: "impressions", title: "Impressions" },
                        { field: "clicks", title: "Clicks" },
                        { field: "rate", title: "Rate" },
                    ],
                });
            } else if (type == "csv") {
                saveExcel({
                    data: record,
                    fileName:
                        "analytics_ayo_media_network" +
                        this.date.startDate +
                        "-" +
                        this.date.endDate +
                        ".csv",
                    columns: [
                        { field: "name", title: "Name" },
                        { field: "start_date", title: "Start Date" },
                        { field: "end_date", title: "End Date" },
                        { field: "budget", title: "Budget" },
                        { field: "goal", title: "Goal" },
                        { field: "impressions", title: "Impressions" },
                        { field: "clicks", title: "Clicks" },
                        { field: "rate", title: "Rate" },
                    ],
                });
            }

            this.loading = false;
        },
    },
    mounted() {
        var s = this.statistics.sort((a, b) => {
            if (new Date(a._id.date) < new Date(b._id.date)) return -1;
            if (new Date(a._id.date) > new Date(b._id.date)) return 1;
            return 0;
        });
        this.chartData.datasets.push(this.dataset[0]);
        this.chartData.datasets.push(this.dataset[1]);
        this.chartData.datasets.push(this.dataset[2]);
        for (let i = 0; i < this.statistics.length; i++) {
            this.chartData.labels.push(this.formatDate(s[i]._id.date));
            this.chartData.datasets[0].data.push(s[i].clicks);
            this.chartData.datasets[1].data.push(s[i].impressions);
            this.chartData.datasets[2].data.push(s[i].rate);
        }
        this.date.startDate = this.start;
        this.date.endDate = this.end;
    },
};
</script>
