<template>
    <router-link :to="to" class="flex justify-center items-center text-center h-10 w-10 bg-green-600 hover:bg-green-700 text-white p-2 rounded">
        <img src="/imgs/icons/details.svg" alt="Details">
    </router-link>
</template>

<script setup>
const props = defineProps({
    to: {
        type: [String, Object],
        required: true
    }
});
</script>