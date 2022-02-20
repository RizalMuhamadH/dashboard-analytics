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
                                        <li @click="filteringGraph('All')"><a>All</a></li>
                                        <li @click="filteringGraph('Clicks')"><a>Clicks</a></li>
                                        <li @click="filteringGraph('Impressions')">
                                        <a>Impression</a>
                                        </li>
                                        <li @click="filteringGraph('CTR')"><a>CTR</a></li>
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
                                        <li><a>CSV</a></li>
                                        <li><a>XLSX</a></li>
                                        <li><a>Google Sheet</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="basis-2/4">
                                <litepie-datepicker
                                    class="dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray"
                                    v-model="date"
                                    overlay
                                    :formatter="formatter"
                                    required
                                    :placeholder="date.startDate + ' ~ ' + date.endDate"
                                ></litepie-datepicker>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="p-6 min-w-0 bg-white rounded-lg shadow-xs dark:bg-gray-800"
                >
                    <BreezeChart
                        v-if="this.chartData.datasets[0].data.length > 0"
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
                                    <!-- <label class="block text-md mb-2">
                      <span class="text-gray-700 dark:text-white">
                        Column
                      </span>
                    </label>
                    <input
                      type="text"
                      placeholder="Type here"
                      class="
                        block
                        text-black
                        dark:text-gray-200
                        w-full
                        mt-1
                        text-sm
                        border border-gray-200
                        rounded-md
                        dark:border-gray-600 dark:bg-gray-700
                        focus:border-purple-400
                        focus:outline-none
                        focus:shadow-outline-purple
                        dark:text-gray-300 dark:focus:shadow-outline-gray
                        form-input
                      "
                      v-model="filter.column"
                    /> -->
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
                                    <!-- <label class="block text-md mb-2">
                      <span class="text-gray-700 dark:text-white">
                        Parameter
                      </span>
                    </label>
                    <input
                      type="text"
                      placeholder="Type here"
                      class="
                        block
                        text-black
                        dark:text-gray-200
                        w-full
                        mt-1
                        text-sm
                        border border-gray-200
                        rounded-md
                        dark:border-gray-600 dark:bg-gray-700
                        focus:border-purple-400
                        focus:outline-none
                        focus:shadow-outline-purple
                        dark:text-gray-300 dark:focus:shadow-outline-gray
                        form-input
                      "
                      v-model="filter.parameter"
                    /> -->
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
                <table class="w-full mt-3 whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800"
                        >
                            <th @click="changeOrder('name')">Campaign</th>
                            <th @click="changeOrder('deposit')">Date</th>
                            <th @click="changeOrder('goal')">Goal(%)</th>
                            <th @click="changeOrder('impression')">
                                Impression
                            </th>
                            <th @click="changeOrder('clicks')">Clicks</th>
                            <th @click="changeOrder('rate')">Rate</th>
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
                                class="px-4 py-3 text-sm hover:text-blue-500"
                                @click="redirectPage(item.campaign_id)"
                            >
                                {{ campaign.name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ formatDate(item.date) }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ campaign.goal }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ item.impressions }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ item.clicks }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ item.rate }}
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
import moment from "moment";

export default {
    props: ["statistics", "campaign", "operators", "statistics", "collection", "start", "end"],
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        BreezeChart,
        BreezeInput,
        BreezeInputSelect,
        LitepieDatepicker,
    },

    data() {
        return {
            data: this.collection,
            state: false,
            chartType: "line",
            date: {
                startDate: "",
                endDate: "",
            },
            chartData: {
                labels: [],
                datasets: [
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
    methods: {
        formatDate(date) {
            return moment(date).format("LL");
        },
        addNewFiltering: function () {
            if (
                this.filter.column == null ||
                this.filter.parameter == null ||
                this.filter.operator == null
            ) {
            } else {
                console.log(this.data);
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
            window.location.href = "/campaign-collection/detail/" + id;
        },
        filterArray() {
            let filterings = this.filterings;
            let collection = this.collection;
            this.data =
                filterings.length > 0
                    ? collection.filter(function (item) {
                          let result = false;
                          filterings.forEach(function (filtering) {
                              if (filtering.operator == "==") {
                                  if (
                                      item[filtering.column] ==
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                              } else if (filtering.operator == "!=") {
                                  if (
                                      item[filtering.column] !=
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                              } else if (filtering.operator == ">") {
                                  if (
                                      item[filtering.column] >
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                              } else if (filtering.operator == "<") {
                                  if (
                                      item[filtering.column] <
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                              } else if (filtering.operator == "<=") {
                                  if (
                                      item[filtering.column] <=
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                              } else if (filtering.operator == ">=") {
                                  if (
                                      item[filtering.column] >=
                                      filtering.parameter
                                  ) {
                                      result = true;
                                  }
                              }
                          });
                          return result;
                      })
                    : collection;
            return collection;
        },

        filterAction() {
            this.$inertia.get(
                this.route("campaign-collection.detail", {
                    id: this.campaign._id,
                }),
                {
                    start: this.date.startDate,
                    end: this.date.endDate,
                },
                {
                    onSuccess: (page) => {},
                    onError: (errors) => {
                        console.log(errors);
                    },
                }
            );
        },

        filteringGraph:function(graphic) {
            this.$inertia.get(
                this.route("dashboard.index"),
                {
                start: this.date.startDate,
                end: this.date.endDate,
                graphic: graphic
                },
                {
                onSuccess: (page) => {},
                onError: (errors) => {
                    console.log(errors);
                },
                }
            );
        },
    },
    mounted() {
        var s = this.statistics.sort((a, b) => {
            if (new Date(a._id.date) < new Date(b._id.date)) return -1;
            if (new Date(a._id.date) > new Date(b._id.date)) return 1;
            return 0;
        });
        for (let i = 0; i < this.statistics.length; i++) {
            this.chartData.labels.push(this.formatDate(s[i].date));
            this.chartData.datasets[0].data.push(s[i].clicks);
            this.chartData.datasets[1].data.push(s[i].impressions);
            this.chartData.datasets[2].data.push(s[i].rate);
        }

        this.date.startDate = this.start;
        this.date.endDate = this.end;
    },
};
</script>
