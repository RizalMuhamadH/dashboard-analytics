<template>
    <Head :title="action + ' Advertise Collection'" />

    <BreezeAuthenticatedLayout>
        <h2
            class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
            <a @click="back" class="cursor-pointer">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            {{ action + " Advertise Collection" }}
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
                            :name="'Programmatic'"
                            v-model="form.advertise"
                            :option="advertises"
                            :required="true"
                        />

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
                                >Page Views</span
                            >
                            <input
                                class="block w-full mt-1 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                v-model="form.pageViews"
                                placeholder="page views"
                                type="number"
                                :required="true"
                            />
                        </label>

                        <label class="block text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-400"
                                >Views</span
                            >
                            <input
                                class="block w-full mt-1 text-sm border border-gray-200 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                v-model="form.views"
                                placeholder="views"
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

                    <div class="w-1/3">
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
import BreezeInputSelect from "@/Components/InputSelect.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default {
    props: ["order", "action", "collect", "websites", "advertises"],
    data() {
        return {
            form: this.$inertia.form({
                name: "",
                pageViews: 0,
                views: 0,
                rpm: 0,
                revenue: 0,
                subscribe: 0,
                status: "",
                date: "",
                advertise: "",
            }),

            status: ["Inactive", "Active"],
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
                this.form.post(
                    this.route("advertise-collection.update", {
                        website: this.data._id,
                    }),
                    {
                        onFinish: () => this.formReset(),
                        onSuccess: (page) => {
                            this.formReset();
                            toastr.success(this.$page.props.success.message);
                            window.history.back();
                        },
                        onError: (errors) => {
                            console.log(errors);
                        },
                    }
                );
            } else {
                this.form.post(this.route("advertise-collection.store"), {
                    onFinish: () => this.formReset(),
                    onSuccess: (page) => {
                        this.formReset();
                        toastr.success(this.$page.props.success.message);
                        window.history.back();
                    },
                    onError: (errors) => {
                        console.log(errors);
                    },
                });
            }
        },
    },
    created() {
        console.log(this.collect);
        if (this.action == "Edit") {
            this.form.name = this.collect.name;
            this.form.pageViews = this.collect.page_views;
            this.form.views = this.collect.views;
            this.form.rpm = this.collect.rpm;
            this.form.revenue = this.collect.revenue;
            this.form.subscribe = this.collect.subscribe;
            this.form.status = this.collect.status;
            this.form.date = this.collect.date;
            this.form.advertise = this.collect.advertise_id;
        }
    },
};
</script>

<style>
.select2.select2-container {
    width: 100% !important;
}

.select2.select2-container .select2-selection {
    border: 1px solid rgba(229, 231, 235, 1);
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    height: 34px;
    margin-bottom: 15px;
    outline: none !important;
    transition: all 0.15s ease-in-out;
}

.select2.select2-container .select2-selection .select2-selection__rendered {
    color: #333;
    line-height: 32px;
    padding-right: 33px;
}

.select2.select2-container .select2-selection .select2-selection__arrow {
    background: #f8f8f8;
    border-left: 1px solid #ccc;
    -webkit-border-radius: 0 3px 3px 0;
    -moz-border-radius: 0 3px 3px 0;
    border-radius: 0 3px 3px 0;
    height: 32px;
    width: 33px;
}

.select2.select2-container.select2-container--open
    .select2-selection.select2-selection--single {
    background: #f8f8f8;
}

.select2.select2-container.select2-container--open
    .select2-selection.select2-selection--single
    .select2-selection__arrow {
    -webkit-border-radius: 0 3px 0 0;
    -moz-border-radius: 0 3px 0 0;
    border-radius: 0 3px 0 0;
}

.select2.select2-container.select2-container--open
    .select2-selection.select2-selection--multiple {
    border: 1px solid rgba(229, 231, 235, 1);
}

.select2.select2-container .select2-selection--multiple {
    height: auto;
    min-height: 34px;
}

.select2.select2-container
    .select2-selection--multiple
    .select2-search--inline
    .select2-search__field {
    margin-top: 0;
    height: 32px;
}

.select2.select2-container
    .select2-selection--multiple
    .select2-selection__rendered {
    display: block;
    padding: 0 4px;
    line-height: 29px;
}

.select2.select2-container
    .select2-selection--multiple
    .select2-selection__choice {
    background-color: #f8f8f8;
    border: 1px solid #ccc;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    margin: 4px 4px 0 0;
    padding: 0 6px 0 22px;
    height: 24px;
    line-height: 24px;
    font-size: 12px;
    position: relative;
}

.select2.select2-container
    .select2-selection--multiple
    .select2-selection__choice
    .select2-selection__choice__remove {
    position: absolute;
    top: 0;
    left: 0;
    height: 22px;
    width: 22px;
    margin: 0;
    text-align: center;
    color: #e74c3c;
    font-weight: bold;
    font-size: 16px;
}

.select2-container .select2-dropdown {
    background: transparent;
    border: none;
    margin-top: -5px;
}

.select2-container .select2-dropdown .select2-search {
    padding: 0;
}

.select2-container .select2-dropdown .select2-search input {
    outline: none !important;
    border: 1px solid #34495e !important;
    border-bottom: none !important;
    padding: 4px 6px !important;
}

.select2-container .select2-dropdown .select2-results {
    padding: 0;
}

.select2-container .select2-dropdown .select2-results ul {
    background: #fff;
    border: 1px solid #34495e;
}

.select2-container
    .select2-dropdown
    .select2-results
    ul
    .select2-results__option--highlighted[aria-selected] {
    background-color: #3498db;
}

.select2.select2-container.select2-container--default.select2-container--below.select2-container--focus,
.select2.select2-container.select2-container--default {
    width: 100%;
}
</style>
