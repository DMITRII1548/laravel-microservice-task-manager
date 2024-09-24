<template>
    <div class="flex gap-2 flex-col md:flex-row flex-wrap justify-between items-center border p-3 rounded-lg">
        <div><span>{{ task.id }}</span></div>
        <div><span class="w-48">{{ task.title }}</span></div>
        <div><span :class="statusColor">{{ task.status }}</span></div>
        <div class="flex gap-2">
            <DetailsButton to="#" />
            <EditButton to="#" />
            <DestroyButton 
                :id="task.id"
                :title="task.title"
             />
        </div>
    </div>
</template>

<script setup>
import DetailsButton from '../../components/Task/DetailsButton.vue'
import EditButton from '../../components/Task/EditButton.vue'
import DestroyButton from '../../components/Task/DestroyButton.vue'

import { computed } from 'vue'

const props = defineProps({
    task: {
        type: [Object],
        required: true
    },
})

const statusColor = computed(() => {
    const colors = {
        CREATED: 'text-green-500',
        PROCESSING: 'text-sky-500',
        FINISHED: 'text-violet-500',
        CANCELED: 'text-red-500'
    }

    return colors[props.task.status] || ''
})
</script>