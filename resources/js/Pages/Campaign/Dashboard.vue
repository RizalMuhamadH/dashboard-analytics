<template>
    <Head title="Campaign Page" />

    <BreezeAuthenticatedLayout>
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
                        <form @submit.prevent="formAction">
                            <div class="px-4 py-3 mb-8 rounded-lg">
                                <div class="flex space-x-4">
                                    <div>
                                        <label class="block text-md mb-2">
                                            <button
                                                type="button"
                                                class="btn btn-primary mt-6"
                                                @click="addNewFiltering"
                                            >
                                                Add Filter
                                            </button>
                                        </label>
                                    </div>
                                </div>
                                <div v-for="(filtering, index) in filterings">
                                    <div class="flex space-x-4">
                                        <div class="">
                                            <label class="block text-md mb-2">
                                                <span
                                                    class="text-gray-700 dark:text-white"
                                                >
                                                    Column
                                                </span>
                                                <input
                                                    type="text"
                                                    placeholder="Type here"
                                                    class="block w-full mt-1 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                    v-model="filtering.column"
                                                    :key="index"
                                                    name="filterings[][column]"
                                                />
                                            </label>
                                        </div>
                                        <div class="">
                                            <label class="block text-md mb-2">
                                                <span
                                                    class="text-gray-700 dark:text-white"
                                                >
                                                    Parameter
                                                </span>
                                                <input
                                                    type="text"
                                                    placeholder="Type here"
                                                    class="block w-full mt-1 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                    v-model="
                                                        filtering.parameter
                                                    "
                                                    name="filterings[][parameter]"
                                                    :key="index"
                                                />
                                            </label>
                                        </div>
                                        <div>
                                            <label class="block text-md mb-2">
                                                <button
                                                    type="button"
                                                    class="btn btn-danger mt-6"
                                                    @click="
                                                        removeFiltering(index)
                                                    "
                                                >
                                                    Remove
                                                </button>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"> -->
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
        <div class="py-5 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <table class="w-full mt-3 whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800"
                        >
                            <th @click="changeOrder('name')">Campaign</th>
                            <th @click="changeOrder('deposit')">Budget</th>
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
                            v-for="(item, index) of campaigns"
                            :key="index"
                        >
                            <td class="px-4 py-3 text-sm">
                                {{ item.campaign.name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ item.campaign.deposit }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ item.campaign.goal }}
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
import { Head } from "@inertiajs/inertia-vue3";
import BreezeChart from "@/Components/Chart.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeInputSelect from "@/Components/InputSelect.vue";
import moment from "moment";

export default {
    props: ["statistics", "campaigns"],
    components: {
        BreezeAuthenticatedLayout,
        Head,
        BreezeChart,
        BreezeInput,
        BreezeInputSelect,
    },

    data() {
        return {
            state: false,
            chartType: "line",
            chartData: {
                labels: [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December",
                ],
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
            filtering: {
                column: "",
                parameter: "",
            },
            filterings: [],
        };
    },
    methods: {
        formatDate(date) {
            return moment(date).format("LL");
        },
        addNewFiltering: function () {
            this.filterings.push(this.filtering);
        },
        removeFiltering: function (index) {
            this.filterings.splice(index, 1);
        },
    },
    mounted() {
        var s = this.statistics.sort((a, b) => {
            if (new Date(a._id.date) < new Date(b._id.date)) return -1;
            if (new Date(a._id.date) > new Date(b._id.date)) return 1;
            return 0;
        });
        for (let i = 0; i < this.statistics.length; i++) {
            this.chartData.labels.push(this.formatDate(s[i]._id.date));
            this.chartData.datasets[0].data.push(s[i].clicks);
            this.chartData.datasets[1].data.push(s[i].impressions);
            this.chartData.datasets[2].data.push(s[i].rate);
        }
    },
};
</script>
