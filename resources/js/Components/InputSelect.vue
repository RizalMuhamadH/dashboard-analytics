<template>
    <label
        class="
            block
            text-sm
            mb-2
        "
        for="grid-state"
    >
        <span class="text-gray-700 dark:text-gray-400">{{ name }}</span>
        <div class="relative">
            <select
                class="
                    block
                    w-full
                    mt-1
                    text-sm
                    border border-gray-200
                    rounded-md
                    text-gray-700
                    leading-tight
                    focus:border-purple-400
                    focus:outline-none
                    focus:shadow-outline-purple
                    dark:focus:shadow-outline-gray
                    dark:text-gray-300
                    dark:border-gray-600
                    dark:bg-gray-700
                    form-input
                "
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                ref="input"
                :required="required"
            >
                <option v-for="item of option" :key="item._id ?? item" class="text-base" :value="item._id ?? item">{{ item.name ?? item }}</option>
            </select>
            <div
                class="
                    pointer-events-none
                    absolute
                    inset-y-0
                    right-0
                    flex
                    items-center
                    px-2
                    text-gray-700
                "
            >
                <svg
                    class="
                        fill-current
                        h-4
                        w-4
                        dark:focus:shadow-outline-gray
                        dark:text-gray-300
                        dark:border-gray-600
                        dark:bg-gray-700
                    "
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                    />
                </svg>
            </div>
        </div>
    </label>
    <!-- <label class="block text-sm">
        <span class="text-gray-700 dark:text-gray-400">{{ name }}</span>
        <input
            class="
                block
                w-full
                mt-1
                text-sm
                border border-gray-200
                rounded-md
                dark:border-gray-600 dark:bg-gray-700
                focus:border-purple-400
                focus:outline-none
                focus:shadow-outline-purple
                dark:text-gray-300 dark:focus:shadow-outline-gray
                form-input
            "
            :placeholder="placeholder"
            :value="modelValue"
            :type="type"
            @input="$emit('update:modelValue', $event.target.value)"
            ref="input"
        />
    </label> -->
</template>

<script>
export default {
    props: ["modelValue", "name", "option", "required"],

    emits: ["update:modelValue"],

    methods: {
        focus() {
            this.$refs.input.focus();
        },
    },
};
</script>
