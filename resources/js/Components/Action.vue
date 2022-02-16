<template>
    <div class="flex items-center space-x-4 text-sm">
        <Link
            v-if="isEdit"
            :href="route(section + '.edit', param)"
            :class="'flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray'"
            aria-label="Edit"
        >
            <svg
                class="w-5 h-5"
                aria-hidden="true"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path
                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                ></path>
            </svg>
        </Link>
        <Link
            v-if="isShow"
            :href="route(section + '.show', param)"
            :class="'flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray'"
            aria-label="Show"
        >
            <i class="fa-solid fa-eye"></i>
        </Link>
        <Link
            v-if="component"
            :href="route('components.index', param)"
            :class="'flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray'"
            aria-label="Component"
        >
            <i class="fas fa-cubes"></i>
        </Link>
        <form @submit.prevent="actionDelete" v-if="isDelete">
            <button
                type="submit"
                class="
                    flex
                    items-center
                    justify-between
                    px-2
                    py-2
                    text-sm
                    font-medium
                    leading-5
                    text-purple-600
                    rounded-lg
                    dark:text-gray-400
                    focus:outline-none focus:shadow-outline-gray
                "
                aria-label="Delete"
            >
                <svg
                    class="w-5 h-5"
                    aria-hidden="true"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                        clip-rule="evenodd"
                    ></path>
                </svg>
            </button>
        </form>
    </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
    components: {
        Link,
    },
    props: ["param", "section", "isDelete", "component", "isEdit", "isShow"],
    methods: {
        actionDelete() {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete(
                        this.route(this.section + ".destroy", this.param),
                        {
                            onSuccess: (page) => {
                                toastr.success(this.$page.props.flash.message);
                            },
                        }
                    );
                }
            });
        },
    },
};
</script>
