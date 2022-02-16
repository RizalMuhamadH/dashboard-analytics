<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
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
                        label: "Revenue",
                        backgroundColor: "#7e3af2",
                        borderColor: "#7e3af2",
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
