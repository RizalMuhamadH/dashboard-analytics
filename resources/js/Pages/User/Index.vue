<template>
    <Head title="Users" />

    <BreezeAuthenticatedLayout>
        <h2
            class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
            Users
        </h2>

        <div class="my-6">
            <Link
                :href="route('users.create')"
                :class="'px-4 py-2 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple'"
            >
                Create
            </Link>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="
                                text-xs
                                font-semibold
                                tracking-wide
                                text-left text-gray-500
                                uppercase
                                border-b
                                dark:border-gray-700
                                bg-gray-50
                                dark:text-gray-400 dark:bg-gray-800
                            "
                        >
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Roles</th>
                            <th class="px-4 py-3">Created At</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody
                        class="
                            bg-white
                            divide-y
                            dark:divide-gray-700 dark:bg-gray-800
                        "
                    >
                        <tr
                            class="text-gray-700 dark:text-gray-400"
                            v-for="(item, index) of list.data"
                            :key="index"
                        >
                            <td class="px-4 py-3 text-sm">
                                {{ item.name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ item.email }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span v-for="(role, i) of item.roles" :key="i"
                                    class="
                                        px-2
                                        py-1
                                        font-semibold
                                        leading-tight
                                        text-green-700
                                        bg-green-100
                                        rounded-full
                                        dark:bg-green-700 dark:text-green-100
                                    "
                                >
                                    {{ role.name }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ formatDate(item.created_at) }}
                            </td>
                            <td class="px-4 py-3">
                                <div
                                    class="flex items-center space-x-4 text-sm"
                                >
                                    <BreezeAction
                                        :id="item.id"
                                        :section="'users'"
                                        :isDelete="true"
                                        :isEdit="true"
                                        :component="false"
                                        :param="item._id"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <BreezePaginate
                :page="list.current_page"
                :perPage="list.per_page"
                :total="list.total"
                :to="list.to"
                :from="list.from"
                :links="list.links"
            />
        </div>
    </BreezeAuthenticatedLayout>
</template>
<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import BreezeDropdownTable from "@/Components/TableDropdown.vue";
import BreezePaginate from "@/Components/Paginate.vue";
import BreezeAction from "@/Components/Action.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import moment from "moment";
export default {
    props: {
        list: {
            type: Object,
            required: true,
        }
    },
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        BreezeDropdownTable,
        BreezeAction,
        BreezePaginate,
    },
    mounted() {
        if(this.$page.props.success.message != null){
            toastr.success(this.$page.props.success.message);
        }

    },
    methods: {
        formatDate(date) {
            return moment(date).format("LLLL");
        }
    }
};
</script>
