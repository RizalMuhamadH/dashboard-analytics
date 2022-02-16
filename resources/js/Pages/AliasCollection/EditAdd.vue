<template>
    <Head :title="action + ' Data Alias'" />

    <BreezeAuthenticatedLayout>
        <h2
            class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
            <a @click="back" class="cursor-pointer">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            {{ action + " Alias" }}
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
                        <!-- <BreezeInput
                            id="name"
                            :type="'text'"
                            :name="'Name'"
                            :placeholder="'name'"
                            v-model="form.name"
                            :required="true"
                            autofocus
                        /> -->

                        <BreezeInput
                            id="alias"
                            :type="'text'"
                            :name="'Alias'"
                            :placeholder="'alias'"
                            v-model="form.alias"
                            :required="true"
                        />
                    </div>

                    <div class="w-1/3">
                        <label class="block text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-400"
                                >Websites</span
                            >
                            <Multiselect
                                v-model="form.websiteId"
                                :options="allWebsites"
                                :searchable="true"
                                :closeOnSelect="true"
                                :classes="classes"
                            />
                        </label>
                        <BreezeInputSelect
                            :name="'Programmatic'"
                            v-model="form.advertiseId"
                            :option="advertises"
                            :required="true"
                        />
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
// import Select2 from "vue3-select2-component";

import Multiselect from "@vueform/multiselect";

export default {
    props: ["order", "action", "aliasCollection", "websites", "advertises"],
    data() {
        return {
            form: this.$inertia.form({
                name: "",
                alias: "",
                websiteId: "",
                advertiseId: "",
            }),
            allWebsites: [],
            classes: {
                container:
                    "relative mx-auto mt-1 w-full flex items-center justify-end box-border cursor-pointer border border-gray-200 rounded-md dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-300 leading-snug outline-none",
                containerDisabled: "cursor-default bg-gray-100",
                containerOpen: "rounded-b-none",
                containerOpenTop: "rounded-t-none",
                containerActive:
                    "border-purple-400 outline-none shadow-outline-purple",
                singleLabel:
                    "flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 pr-16 box-border",
                singleLabelText:
                    "overflow-ellipsis overflow-hidden block whitespace-nowrap max-w-full",
                multipleLabel:
                    "flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5",
                search: "w-full absolute inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-sm text-gray-700 dark:text-gray-300 font-sans bg-white dark:bg-gray-700 rounded pl-3.5",
                tags: "flex-grow flex-shrink flex flex-wrap items-center mt-1 pl-2",
                tag: "bg-purple-500 text-gray-700 dark:text-gray-300 text-sm font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap",
                tagDisabled: "pr-2 opacity-50",
                tagRemove:
                    "flex items-center justify-center p-1 mx-0.5 rounded-sm hover:bg-black hover:bg-opacity-10 group",
                tagRemoveIcon:
                    "bg-multiselect-remove bg-center bg-no-repeat opacity-30 inline-block w-3 h-3 group-hover:opacity-60",
                tagsSearchWrapper:
                    "inline-block relative mx-1 mb-1 flex-grow flex-shrink h-full",
                tagsSearch:
                    "absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-sm font-sans box-border w-full",
                tagsSearchCopy:
                    "invisible whitespace-pre-wrap inline-block h-px",
                placeholder:
                    "flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-700 dark:text-gray-300",
                caret: "bg-multiselect-caret bg-center bg-no-repeat w-2.5 h-4 py-px box-content mr-3.5 relative z-10 opacity-40 flex-shrink-0 flex-grow-0 transition-transform transform pointer-events-none",
                caretOpen: "rotate-180 pointer-events-auto",
                clear: "pr-3.5 relative z-10 opacity-40 transition duration-300 flex-shrink-0 flex-grow-0 flex hover:opacity-80",
                clearIcon:
                    "bg-multiselect-remove bg-center bg-no-repeat w-2.5 h-4 py-px box-content inline-block",
                spinner:
                    "bg-multiselect-spinner bg-center bg-no-repeat w-4 h-4 z-10 mr-3.5 animate-spin flex-shrink-0 flex-grow-0",
                dropdown:
                    "max-h-60 absolute -left-px -right-px bottom-0 transform translate-y-full border border-gray-300 -mt-px overflow-y-scroll z-50 bg-white dark:bg-gray-700 flex flex-col rounded-b",
                dropdownTop:
                    "-translate-y-full top-px bottom-auto flex-col-reverse rounded-b-none rounded-t",
                dropdownHidden: "hidden",
                options: "flex flex-col p-0 m-0 list-none",
                optionsTop: "flex-col-reverse",
                group: "p-0 m-0",
                groupLabel:
                    "flex text-sm text-gray-700 dark:text-gray-300 box-border items-center justify-start text-left py-1 px-3 font-semibold bg-gray-200 cursor-default leading-normal",
                groupLabelPointable: "cursor-pointer",
                groupLabelPointed:
                    "bg-gray-300 text-gray-700 dark:text-gray-300",
                groupLabelSelected:
                    "bg-purple-600 text-gray-700 dark:text-gray-300",
                groupLabelDisabled:
                    "bg-gray-100 text-gray-700 dark:text-gray-300 cursor-not-allowed",
                groupLabelSelectedPointed:
                    "bg-purple-600 text-gray-700 dark:text-gray-300 opacity-90",
                groupLabelSelectedDisabled:
                    "text-gray-700 dark:text-gray-300 bg-purple-600 bg-opacity-50 cursor-not-allowed",
                groupOptions: "p-0 m-0",
                option: "flex items-center justify-start box-border text-left cursor-pointer text-sm text-gray-700 dark:text-gray-300 leading-snug py-2 px-3",
                optionPointed: "text-gray-700 dark:text-gray-300 bg-purple-500",
                optionSelected: "text-white bg-purple-700",
                optionDisabled:
                    "text-gray-700 dark:text-gray-300 cursor-not-allowed",
                optionSelectedPointed: "text-white bg-purple-500 opacity-90",
                optionSelectedDisabled:
                    "text-green-100 bg-purple-500 bg-opacity-50 cursor-not-allowed",
                noOptions:
                    "py-2 px-3 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 text-left",
                noResults:
                    "py-2 px-3 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 text-left",
                fakeInput:
                    "bg-transparent absolute left-0 right-0 -bottom-px w-full h-px border-0 p-0 appearance-none outline-none text-transparent",
                spacer: "h-9 py-px box-content",
            },
        };
    },
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        BreezeInput,
        BreezeInputSelect,
        Multiselect,
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
                    this.route("alias-collection.update", {
                        website: this.data.id,
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
                this.form.post(this.route("alias-collection.store"), {
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
    },
    created() {
        if (this.action == "Edit") {
            this.form.alias = this.aliasCollection.alias;
            this.form.websiteId = this.aliasCollection.website_id;
            this.form.advertiseId = this.aliasCollection.advertise_id;
        }
    },
    mounted() {
        this.allWebsites = this.websites.map((website) => {
            return {
                value: website._id,
                label: website.name,
            };
        });
    },
};
</script>
