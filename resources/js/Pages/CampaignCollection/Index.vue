<template>
    <Head title="Campaign Collection" />

    <BreezeAuthenticatedLayout>
        <h2
            class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
            Campaign Collection
        </h2>

        <div class="my-6 flex space-x-3">
            <Link
                v-if="
                    $page.props.auth.permissions.includes('campaign-collection-create')
                "
                :href="route('campaign-collection.create')"
                :class="'px-4 py-2 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple'"
            >
                Create
            </Link>

            <!-- <div class="flex justify-end flex-1 lg:mr-32">
                <div
                    class="
                        relative
                        w-full
                        max-w-xl
                        mr-6
                        focus-within:text-purple-500
                    "
                >
                    <div class="absolute inset-y-0 flex items-center pl-2">
                        <svg
                            class="w-4 h-4"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                    </div>
                    <input
                        class="
                            w-full
                            pl-8
                            pr-2
                            text-sm text-gray-700
                            placeholder-gray-600
                            bg-gray-100
                            border-0
                            rounded-md
                            dark:placeholder-gray-500
                            dark:focus:shadow-outline-gray
                            dark:focus:placeholder-gray-600
                            dark:bg-gray-700
                            dark:text-gray-200
                            focus:placeholder-gray-500
                            focus:bg-white
                            focus:border-purple-300
                            focus:outline-none
                            shadow-outline-purple
                            form-input
                        "
                        name="search"
                        v-model="form.search"
                        type="text"
                        placeholder="Search"
                        aria-label="Search"
                        @keyup.enter="handleSearch"
                    />
                </div>
            </div> -->
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
                            <th class="px-4 py-3">Campaign</th>
                            <th class="px-4 py-3">Impressions</th>
                            <th class="px-4 py-3">Clicks</th>
                            <th class="px-4 py-3">Rate</th>
                            <th class="px-4 py-3">Date</th>
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
                                {{ item.campaign.name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ item.impressions }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ item.clicks }}
                            </td>
                            <td class="px-4 py-3 text-sm"> {{ item.rate }} </td>
                            <td class="px-4 py-3 text-sm"> {{ formatDate(item.date) }} </td>
                            <td class="px-4 py-3">
                                <div
                                    class="flex items-center space-x-4 text-sm"
                                >
                                    <BreezeAction
                                        :id="item.id"
                                        :section="'campaign-collection'"
                                        :isDelete="$page.props.auth.permissions.includes('campaign-collection-delete')"
                                        :isEdit="$page.props.auth.permissions.includes('campaign-collection-edit')"
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
                :param="
                    $page.url.split('?')[1] != null
                        ? '&' + $page.url.split('?')[1]
                        : ''
                "
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
        },
    },
    data() {
        return {
            form: this.$inertia.form({
                search: "",
            }),
        };
    },
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        BreezeDropdownTable,
        BreezeAction,
        BreezePaginate,
    },
    methods: {
        handleSearch() {
            this.$inertia.get(
                this.route(
                    "campaign-collection.index",
                    {
                        search: this.form.search,
                    },
                    { replace: true, preserveState: true }
                )
            );
        },
        active(i){
            return i == 0 ? '<span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">Inactive</span>' : '<span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Active</span>'
        },
        formatDate(date){
            return moment(date).format('LL')
        }
    },
    mounted() {

        const queryString = window.location.search;
        const parameters = new URLSearchParams(queryString);

        this.form.search = parameters.get("search") ?? "";


        if(this.$page.props.success.message != null){
            toastr.success(this.$page.props.success.message);
        }
    },
};
</script>
