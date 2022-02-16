<template>
    <Head :title="action + ' Role'" />

    <BreezeAuthenticatedLayout>
        <h2
            class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
            <a @click="back" class="cursor-pointer">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            {{ action + " Role" }}
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
                    <div class="w-full">
                        <BreezeInput
                            id="name"
                            :type="'text'"
                            :name="'Name'"
                            :placeholder="'name'"
                            v-model="form.name"
                            required
                            autofocus
                        />
                        <div class="flex flex-col space-y-2 my-4">
                            <label
                                class="text-sm mb-2"
                                v-for="(parent, index) of listPermissions"
                                :key="index"
                            >
                                <span
                                    class="text-gray-700 dark:text-gray-400 mb-1"
                                    >{{ index }}</span
                                >

                                <div class="flex flex-wrap space-x-2">
                                    <label
                                        class="px-2 py-1 mb-2 font-semibold leading-tight text-white rounded-full dark:text-green-100 cursor-pointer"
                                        :class="
                                            form.permission.includes(child.name)
                                                ? 'dark:bg-purple-700 bg-purple-400'
                                                : 'dark:bg-gray-500 bg-gray-400'
                                        "
                                        v-for="(child, i) of parent"
                                        :key="i"
                                    >
                                        <input
                                            class="hidden"
                                            v-model="form.permission"
                                            aria-describedby="flowbite"
                                            type="checkbox"
                                            :value="child.name"
                                        />
                                        {{ child.name }}
                                    </label>
                                </div>
                            </label>
                        </div>
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
    props: ["action", "role", "permissions", "rolePermission"],
    data() {
        return {
            form: this.$inertia.form({
                name: "",
                permission: [],
            }),
            listPermissions: [],
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
            this.form.reset(["name", "email", "role", "password"]);
        },
        formAction() {
            if (this.action === "Edit") {
                this.form.patch(
                    this.route("roles.update", { role: this.role._id }),
                    {
                        onFinish: () => this.formReset(),
                        onSuccess: (page) => {
                            this.formReset();
                            toastr.success(this.$page.props.flash.message);
                            window.history.back();
                        },
                        onError: (errors) => {
                            console.log(errors);
                        },
                    }
                );
            } else {
                this.form.post(this.route("roles.store"), {
                    onFinish: () => this.formReset(),
                    onSuccess: (page) => {
                        this.formReset();
                        toastr.success(this.$page.props.flash.message);
                        window.history.back();
                    },
                    onError: (errors) => {
                        console.log(errors);
                    },
                });
            }
        },
    },
    mounted() {
        if (this.action == "Edit") {
            this.form.name = this.role.name;
            this.form.permission = this.rolePermission;
            // this.form.role = this.user.roles[0].id;
        }

        var p = this.permissions;

        this.listPermissions = p.reduce((group, value) => {
            var name = value.name;
            var sub = name.lastIndexOf("-");
            name = name.substring(0, sub);
            group[name] = group[name] ?? [];
            group[name].push(value);
            return group;
        }, {});
    },
};
</script>
