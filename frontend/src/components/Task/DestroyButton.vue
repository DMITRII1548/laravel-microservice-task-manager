<template>
    <router-link :to="to" class="flex justify-center items-center text-center h-10 w-10 bg-red-500 hover:bg-red-600 text-white p-2 rounded">
        <img src="/imgs/icons/delete.svg" alt="Delete">
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