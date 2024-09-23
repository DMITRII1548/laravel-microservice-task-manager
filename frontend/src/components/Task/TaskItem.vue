<template>
    <div class="flex gap-2 flex-col md:flex-row flex-wrap justify-between items-center border p-3 rounded-lg">
        <div><span>{{ id }}</span></div>
        <div><span class="w-48">{{ title }}</span></div>
        <div><span :class="statusColor">{{ status }}</span></div>
        <div class="flex gap-2">
            <DetailsButton to="#" />
            <EditButton to="#" />
            <DestroyButton to="#" />
        </div>
    </div>
</template>

<script setup>
import DetailsButton from '../../components/Task/DetailsButton.vue'
import EditButton from '../../components/Task/EditButton.vue'
import DestroyButton from '../../components/Task/DestroyButton.vue'

import { computed } from 'vue'

const props = defineProps({
    id: {
        type: [Number],
        required: true
    },
    title: {
        type: [String],
        required: true
    },
    status: {
        type: [String],
        required: true
    }
})

const statusColor = computed(() => {
    const colors = {
        CREATED: 'text-green-500',
        PROCESSING: 'text-sky-500',
        FINISHED: 'text-violet-500',
        CANCELED: 'text-red-500'
    }

    return colors[props.status] || ''
})
</script>