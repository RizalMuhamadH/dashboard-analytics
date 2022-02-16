<template>
    <div :class="chartType">
        <canvas :class="style"></canvas>
    </div>
</template>

<script>
import {
    Chart,
    ArcElement,
    LineElement,
    BarElement,
    PointElement,
    BarController,
    BubbleController,
    DoughnutController,
    LineController,
    PieController,
    PolarAreaController,
    RadarController,
    ScatterController,
    CategoryScale,
    LinearScale,
    LogarithmicScale,
    RadialLinearScale,
    TimeScale,
    TimeSeriesScale,
    Decimation,
    Filler,
    Legend,
    Title,
    Tooltip,
    SubTitle,
} from "chart.js";

Chart.register(
    ArcElement,
    LineElement,
    BarElement,
    PointElement,
    BarController,
    BubbleController,
    DoughnutController,
    LineController,
    PieController,
    PolarAreaController,
    RadarController,
    ScatterController,
    CategoryScale,
    LinearScale,
    LogarithmicScale,
    RadialLinearScale,
    TimeScale,
    TimeSeriesScale,
    Decimation,
    Filler,
    Legend,
    Title,
    Tooltip,
    SubTitle
);

export default {
    props: {
        chartType: String,
        chartData: Object,
        chartOptions: Object,
        style: String,
    },
    methods: {
        chartConstructor(chartType, chartData, chartOptions) {
            const chartElement = document.querySelector(
                `.${this.chartType} canvas`
            );
            const chart = new Chart(chartElement, {
                type: chartType,
                data: chartData,
                options: chartOptions,
            });
        },
    },

    mounted() {
        let { chartType, chartData, chartOptions } = this;
        this.chartConstructor(chartType, chartData, chartOptions);
    },
};
</script>
