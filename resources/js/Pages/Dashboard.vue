<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <div class="py-5">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="shadow stats stats-vertical lg:stats-horizontal">
                    <div class="stat py-6 w-40 bg-blue-500">
                        <div class="stat-title">Clicks</div>
                        <div class="stat-value">0</div>
                    </div>

                    <div class="stat py-6 w-40 bg-red-600">
                        <div class="stat-title">Impressions</div>
                        <div class="stat-value">0</div>

                    </div>

                    <div class="stat py-6 w-40 bg-yellow-300 text-black">
                        <div class="stat-title">Avg. CPC </div>
                        <div class="stat-value">0</div>
                    </div>

                     <div class="stat py-6 w-40 bg-green-500">
                        <div class="stat-title">CTR</div>
                        <div class="stat-value">0</div>
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
        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-4 gap-4">
                <div class="col-span-2">
                    <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <div class="flex flex-row">
                            <div class="basis-3/4">
                                <h2 class="card-title">List Campaign</h2>
                            </div>
                            <div class="absolute right-10 card-actions">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </div>
                        </div>
                        <table class="w-full mt-3 whitespace-no-wrap">
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                <div class="col-span-2">
                    <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <div class="flex flex-row">
                            <div class="basis-3/4">
                                <h2 class="card-title">Campaigns</h2>
                            </div>
                            <div class="absolute right-10 card-actions">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </div>
                        </div>
                        <hr class="text-white">
                        <table class="w-full mt-3 whitespace-no-wrap">
                            <thead>
                                <tr>
                                    <th>Campaign</th>
                                    <th>Cost</th>
                                    <th>Clicks</th>
                                    <th>CTR</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import BreezeChart from "@/Components/Chart.vue";

export default {
    props: ["statistics"],
    components: {
        BreezeAuthenticatedLayout,
        Head,
        BreezeChart,
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
                        label: "Avg. CPC",
                        backgroundColor: "#fac815",
                        borderColor: "#fac815",
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
        };
    },
    mounted() {
        var s = this.statistics.sort((a, b) => {
            if (a._id.month < b._id.month) return -1;
            if (a._id.month > b._id.month) return 1;
            return 0;
        });
        for (let i = 0; i < 12; i++) {
            var index = i + 1;

            if(s[i] == undefined) {
                this.chartData.datasets[0].data.push(0);
            } else if(s[i]._id.month == index) {
                this.chartData.datasets[0].data.push(s[i].revenue);
            } else {
                this.chartData.datasets[0].data.push(0);
            }

        }
    },
};
</script>
