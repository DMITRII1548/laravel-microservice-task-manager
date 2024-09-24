<template>
    <router-link :to="to" class="flex justify-center items-center text-center h-10 w-10 bg-sky-500 hover:bg-sky-600 text-white p-2 rounded">
        <img src="/imgs/icons/edit.svg" alt="Edit">
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