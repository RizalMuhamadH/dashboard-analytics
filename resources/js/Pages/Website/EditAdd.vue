<template>
    <Head :title="action + ' Website'" />

    <BreezeAuthenticatedLayout>
        <h2
            class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
            <a @click="back" class="cursor-pointer">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            {{ action + " Website" }}
        </h2>
        <!-- CTA -->

        <!-- General elements -->
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Form
        </h4>
        <form @submit.prevent="formAction">
            <div
                class="
                    px-4
                    py-3
                    mb-8
                    bg-white
                    rounded-lg
                    shadow-md
                    dark:bg-gray-800
                "
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

                        <BreezeInput
                            id="alias"
                            :type="'text'"
                            :name="'Alias'"
                            :placeholder="'alias'"
                            v-model="form.alias"
                            :required="true"
                        />

                        <BreezeInput
                            id="url"
                            :type="'text'"
                            :name="'Url'"
                            :placeholder="'url'"
                            v-model="form.url"
                            :required="true"
                        />
                    </div>

                    <div class="w-1/3">
                        <BreezeInput
                            id="wid"
                            :type="'number'"
                            :name="'Web ID'"
                            :placeholder="'Web ID'"
                            v-model="form.wid"
                            :required="true"
                        />

                        <BreezeInputSelect
                            :name="'Active'"
                            v-model="form.isActive"
                            :option="status"
                            :required="true"
                        />

                        <BreezeInput
                            id="path"
                            :type="'text'"
                            :name="'Path'"
                            :placeholder="'path'"
                            v-model="form.path"
                            :required="true"
                        />

                    </div>
                </div>
                <button
                    class="
                        mt-4
                        px-4
                        py-2
                        text-sm
                        font-medium
                        leading-5
                        text-white
                        transition-colors
                        duration-150
                        bg-purple-600
                        border border-transparent
                        rounded-lg
                        active:bg-purple-600
                        hover:bg-purple-700
                        focus:outline-none focus:shadow-outline-purple
                    "
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
    props: ["order", "action", "data", "websites"],
    data() {
        return {
            form: this.$inertia.form({
                name: "",
                url: "",
                alias: "",
                path: "",
                parentId: null,
                wid: 0,
                isActive: 0,
            }),

            status: [
                {
                    id: 0,
                    name: "Inactive",
                },
                {
                    id: 1,
                    name: "Active",
                },
            ],
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
            this.form.reset([
                "name",
                "url",
                "alias",
                "path",
                "parentId",
                "wid",
                "isActive",
                "template",
                "phone",
                "address",
                "regionId",
                "typeId",
            ]);
        },
        formAction() {
            if (this.action === "Edit") {
                this.form.post(
                    this.route("websites.update", {
                        website: this.data.id,
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
                this.form.post(this.route("websites.store"), {
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
        if (this.action == "Edit") {
            this.form.name = this.data.name;
            this.form.alias = this.data.alias;
            this.form.url = this.data.url;
            this.form.wid = this.data.wid;
            this.form.path = this.data.path;
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
