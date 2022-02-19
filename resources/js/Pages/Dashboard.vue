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
        <div class="p-6 min-w-0 bg-white rounded-lg shadow-xs dark:bg-gray-800">
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
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                <div class="flex flex-row">
                    <div class="basis-3/4">
                    <h2 class="card-title">Campaigns</h2>
                    </div>
                    <div class="absolute right-10 card-actions">
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                        ></path>
                    </svg>
                    </div>
                </div>
                <hr class="text-white" />
                <table class="w-full mt-3 whitespace-no-wrap">
                    <thead>
                    <tr>
                        <th @click="changeOrder('name')">Campaign</th>
                        <th @click="changeOrder('deposit')">Budget</th>
                        <th @click="changeOrder('goal')">Goal(%)</th>
                        <th @click="changeOrder('impression')">Impression</th>
                        <th @click="changeOrder('clicks')">Clicks</th>
                        <th @click="changeOrder('rate')">Rate</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) of campaigns.data" :key="index">
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
        </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import BreezeChart from "@/Components/Chart.vue";
import moment from "moment";

export default {
  props: ["statistics", "campaigns"],
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
      y: {
        display: true,
        scaleLabel: {
          display: true,
          labelString: "Value",
        },
      },
      order:{
          column: '',
          ascending: true
      },
    };
  },
  methods: {
    formatDate(date) {
      return moment(date).format("LL");
    },
    changeOrder(column){
        this.order.column   = column;
        this.order.ascending     != this.order.ascending;
    }
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
