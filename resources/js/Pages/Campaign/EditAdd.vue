<template>
    <Head :title="action + ' Campaign'" />

    <BreezeAuthenticatedLayout>
        <h2
            class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
            <a @click="back" class="cursor-pointer">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            {{ action + " Campaign" }}
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
                        <BreezeInput
                            id="name"
                            :type="'text'"
                            :name="'Name'"
                            :placeholder="'name'"
                            v-model="form.name"
                            :required="true"
                            autofocus
                        />

                        <label class="block text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-400"
                                >Deposit</span
                            >
                            <input
                                class="block w-full mt-1 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 text-gray-700 dark:focus:shadow-outline-gray form-input"
                                v-model="form.deposit"
                                placeholder="deposit"
                                type="number"
                                :required="true"
                            />
                        </label>

                        <BreezeInput
                            :type="'datetime-local'"
                            :name="'Start Date'"
                            :placeholder="'start date'"
                            v-model="form.startDate"
                            :required="true"
                            autofocus
                        />

                        <BreezeInput
                            :type="'datetime-local'"
                            :name="'End Date'"
                            :placeholder="'end date'"
                            v-model="form.endDate"
                            :required="true"
                            autofocus
                        />
                    </div>

                    <div class="w-1/3">
                        <BreezeInputSelect
                            :name="'Programmatic'"
                            v-model="form.advertise"
                            :option="advertises"
                            :required="true"
                        />

                        <label class="block text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-400"
                                >Goal(%)</span
                            >
                            <input
                                class="block w-full mt-1 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 text-gray-700 dark:focus:shadow-outline-gray form-input"
                                v-model="form.goal"
                                placeholder="goal"
                                type="number"
                                :required="true"
                            />
                        </label>

                    </div>
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
import LitepieDatepicker from "litepie-datepicker-tw3";
import BreezeInputSelect from "@/Components/InputSelect.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import moment from "moment";

export default {
    props: ["order", "action", "campaign", "advertises"],
    data() {
        return {
            form: this.$inertia.form({
                name: "",
                startDate: "",
                endDate: "",
                deposit: 0,
                goal: 0,
                advertise: 0,
            }),

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
        BreezeInput,
        LitepieDatepicker,
        BreezeInputSelect
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
                    this.route("campaigns.update", {
                        campaign: this.campaign._id,
                    }),
                    {
                        onFinish: () => this.formReset(),
                        onSuccess: (page) => {
                            this.formReset();
                        },
                        onError: (errors) => {
                            console.log(errors);
                        },
                    }
                );
            } else {
                this.form.post(this.route("campaigns.store"), {
                    onFinish: () => this.formReset(),
                    onSuccess: (page) => {
                        this.formReset();
                    },
                    onError: (errors) => {
                        console.log(errors);
                    },
                });
            }
        },
        formatDate(date){
            return moment(date).format('YYYY-MM-DThh:mm');
        }
    },
    created() {
        if (this.action == "Edit") {
            this.form.name = this.campaign.name;
            this.form.startDate= this.formatDate(this.campaign.start_date);
            this.form.endDate= this.formatDate(this.campaign.end_date);
            this.form.deposit = this.campaign.deposit;
            this.form.goal = this.campaign.goal;
            this.form.advertise = this.campaign.advertise_id;
        }
    },
};
</script>
