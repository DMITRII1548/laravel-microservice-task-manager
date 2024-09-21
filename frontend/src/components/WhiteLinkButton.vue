<template>
    <router-link :to="to" class="px-2 py-1 rounded-lg border-white border-2 bg-slate-50 text-lg font-semibold hover:bg-white"><slot></slot></router-link>
</template>

<script setup>
const props = defineProps({
  to: {
    type: [String, Object],
    required: true
  }
});
</script>