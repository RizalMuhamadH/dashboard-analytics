<template>
    <Head :title="action + ' User'" />

    <BreezeAuthenticatedLayout>
        <h2
            class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
            <a @click="back" class="cursor-pointer">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            {{ action + " User" }}
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
                    <div class="w-full">
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
                            id="email"
                            :type="'email'"
                            :name="'Email'"
                            :placeholder="'email'"
                            v-model="form.email"
                            :required="true"
                        />

                        <BreezeInputSelect
                            :name="'Role'"
                            v-model="form.role"
                            :option="roles"
                            :required="true"
                        />

                        <BreezeInput
                            id="password"
                            :type="'password'"
                            :name="'Password'"
                            :placeholder="'password'"
                            v-model="form.password"
                            :required="false"
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
    props: ["action", "user", "roles", "userRole"],
    data() {
        return {
            form: this.$inertia.form({
                name: "",
                email: "",
                role: "",
                password: "",
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
                    this.route("users.update", { user: this.user._id }),
                    {
                        onFinish: () => this.formReset(),
                        onSuccess: (page) => {
                            this.formReset()
                            // toastr.success(this.$page.props.success.message);
                            // window.history.back();
                        },
                        onError: (errors) => {
                            console.log(errors);
                        },
                    }
                );
            } else {
                this.form.post(this.route("users.store"), {
                    onFinish: () => this.formReset(),
                    onSuccess: (page) => {
                            this.formReset()
                            // toastr.success(this.$page.props.success.message);
                            // window.history.back();
                        },
                    onError: (errors) => {
                        console.log(errors);
                    },
                });
            }
        }
    },
    mounted() {

        if (this.action == "Edit") {
            this.form.name = this.user.name;
            this.form.email = this.user.email;
            this.form.role = this.userRole[0];
            // this.form.role = this.user.roles[0].id;
        }
    },
};
</script>
