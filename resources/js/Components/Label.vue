<template>
    <label class="block text-sm">
        <span class="text-gray-700 dark:text-gray-400" v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>

<script>
export default {
    props: ['value']
}
</script>