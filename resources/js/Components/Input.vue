<template>
    <!-- <input
        class="
            block
            w-full
            mt-1
            text-sm
            dark:border-gray-600 dark:bg-gray-700
            focus:border-purple-400
            focus:outline-none
            focus:shadow-outline-purple
            dark:text-gray-300 dark:focus:shadow-outline-gray
            form-input
        "
    /> -->
    <label class="block text-sm mb-2">
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
                text-gray-700
                dark:text-gray-300 dark:focus:shadow-outline-gray
                form-input
            "
            :class="disabled ? 'bg-purple-100' : ''"
            :placeholder="placeholder"
            :value="modelValue"
            :type="type"
            @input="$emit('update:modelValue', type == 'file' ? multiple ? $event.target.files : $event.target.files[0] : $event.target.value)"
            ref="input"
            :multiple="multiple"
            :disabled="disabled"
            :required="required"
        />
    </label>
</template>

<script>
export default {
    props: ["modelValue", "name", "placeholder", "type", "multiple", "disabled", "required"],

    emits: ["update:modelValue"],

    methods: {
        focus() {
            this.$refs.input.focus();
        },
    },
};
</script>
