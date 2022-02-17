<template>
    <Head :title="action + ' Campaign Collection'" />

    <BreezeAuthenticatedLayout>
        <h2
            class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
            <a @click="back" class="cursor-pointer">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            {{ action + " Campaign Collection" }}
        </h2>
        <!-- CTA -->

        <!-- General elements -->
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Form
        </h4>
        <form @submit.prevent="formAction">
            <div
                class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800"
            >
                <div class="flex space-x-4">
                    <div class="w-2/3">
                        <BreezeInputSelect
                            :name="'Campaign'"
                            v-model="form.campaign"
                            :option="campaigns"
                            :required="true"
                        />

                        <label class="block text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-400"
                                >Impressions</span
                            >
                            <input
                                class="block w-full mt-1 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                v-model="form.impressions"
                                placeholder="impressions"
                                type="number"
                                :required="true"
                            />
                        </label>

                        <label class="block text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-400"
                                >Clicks</span
                            >
                            <input
                                class="block w-full mt-1 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                v-model="form.clicks"
                                placeholder="click"
                                type="number"
                                :required="true"
                            />
                        </label>

                        <label class="block text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-400"
                                >Rate</span
                            >
                            <input
                                class="block w-full mt-1 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                v-model="form.rate"
                                placeholder="rate"
                                type="number"
                                :required="true"
                            />
                        </label>

                        <BreezeInput
                            id="date"
                            :type="'date'"
                            :name="'Date'"
                            :placeholder="'date'"
                            v-model="form.date"
                            :required="true"
                        />
                    </div>

                    <!-- <div class="w-1/3">
                        <label class="block text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-400"
                                >RPM</span
                            >
                            <input
                                class="block w-full mt-1 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                v-model="form.rpm"
                                placeholder="rpm"
                                type="number"
                                :required="true"
                                step="0.0001"
                            />
                        </label>

                        <BreezeInputSelect
                            :name="'Active'"
                            v-model="form.status"
                            :option="status"
                            :required="true"
                        />

                        <label class="block text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-400"
                                >Subscribe</span
                            >
                            <input
                                class="block w-full mt-1 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                v-model="form.subscribe"
                                placeholder="subscribe"
                                type="number"
                                :required="true"
                            />
                        </label>

                        <label class="block text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-400"
                                >Revenue</span
                            >
                            <input
                                class="block w-full mt-1 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                v-model="form.revenue"
                                placeholder="revenue"
                                type="number"
                                :required="true"
                                step="0.0001"
                            />
                        </label>
                    </div> -->
                </div>
                <button
                    class="mt-4 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                    :disabled="form.processing"
                    type="submit"
                >
                    {{ action }}
                </button>
            </div>
        </form>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeInputSelect from "@/Components/InputSelect.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import moment from "moment";

export default {
    props: ["order", "action", "campaignCollection", "campaigns"],
    data() {
        return {
            form: this.$inertia.form({
                campaign: "",
                impressions: 0,
                clicks: 0,
                rate: 0,
                date: "",
            }),

        };
    },
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        BreezeInput,
        BreezeInputSelect,
    },

    methods: {
        back() {
            window.history.back();
        },
        formReset() {
            this.form.reset();
        },
        formAction() {
            if (this.action === "Edit") {
                this.form.patch(
                    this.route("campaign-collection.update", {
                        campaign_collection: this.campaignCollection._id,
                    }),
                    {
                        onFinish: () => this.formReset(),
                        onSuccess: (page) => {
                            this.formReset();
                            // toastr.success(this.$page.props.success.message);
                            // window.history.back();
                        },
                        onError: (errors) => {
                            console.log(errors);
                        },
                    }
                );
            } else {
                this.form.post(this.route("campaign-collection.store"), {
                    onFinish: () => this.formReset(),
                    onSuccess: (page) => {
                        this.formReset();
                        // toastr.success(this.$page.props.success.message);
                        // window.history.back();
                    },
                    onError: (errors) => {
                        console.log(errors);
                    },
                });
            }
        },
        formatDate(date){
            return moment(date).format('YYYY-MM-D');
        }
    },
    created() {
        console.log(this.collect);
        if (this.action == "Edit") {
            this.form.campaign = this.campaignCollection.campaign_id;
            this.form.impressions = this.campaignCollection.impressions;
            this.form.clicks = this.campaignCollection.clicks;
            this.form.rate = this.campaignCollection.rate;
            this.form.date = this.formatDate(this.campaignCollection.date_string);
        }
    },
};
</script>
