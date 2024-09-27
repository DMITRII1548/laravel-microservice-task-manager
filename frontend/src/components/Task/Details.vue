<template>
    <button @click="openModal()" class="flex justify-center items-center text-center h-10 w-10 bg-green-600 hover:bg-green-700 text-white p-2 rounded">
        <img src="/imgs/icons/details.svg" alt="Details">
    </button>

    <!-- Main modal -->
    <div v-if="isModalOpen" tabindex="-1" class="fixed overflow-y-auto inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Details of task</h3>
                    <button @click="closeModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2 flex gap-2">
                            <strong>ID:</strong>
                            <span>{{ task.id }}</span>
                        </div>
                        <div class="col-span-2 flex gap-2">
                            <strong>Title:</strong>
                            <span>{{ task.title }}</span>
                        </div>
                        <div class="col-span-2 flex flex-col gap-2">
                            <strong>Content:</strong>
                            <p class="whitespace-pre-line border p-1 rounded">{{ task.content }}</p>
                        </div>
                        <div class="col-span-2 flex flex-col gap-2">
                            <strong>Tags:</strong>
                            <div class="flex flex-wrap gap-2">
                                <template v-if="task.tags">
                                    <div 
                                        v-for="tag in task.tags" 
                                        class="inline-flex items-center gap-2 px-2 py-1 border-2 border-white rounded-2xl bg-indigo-500 text-white cursor-pointer"
                                     >
                                        <span>{{ tag }}</span>
                                    </div>
                                </template>
                                <span v-else="task.tags">-</span>
                            </div>
                        </div>
                        <div class="col-span-2 flex gap-2">
                            <strong>Status:</strong>
                            <span :class="statusColor">{{ task.status }}</span>
                        </div>
                        <div class="col-span-2 flex gap-2">
                            <strong>Started:</strong>
                            <span>{{ task.started_at ?? '-' }}</span>
                        </div>
                        <div class="col-span-2 flex gap-2">
                            <strong>Finished:</strong>
                            <span>{{ task.finished_at ?? '-' }}</span>
                        </div>
                        <div class="col-span-2 flex gap-2">
                            <strong>Created:</strong>
                            <span>{{ task.created_at ?? '-' }}</span>
                        </div>
                        <div class="col-span-2 flex justify-between gap-2">
                            <button :disabled="isUpdating" @click="store.dispatch('changeToBackStatusTask', task.id)" class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                </svg>
                                <span>Back status</span>
                            </button>
                            <button :disabled="isUpdating" @click="store.dispatch('changeToNextStatusTask', task.id)" class="flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">
                                <span>Next status</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</template>

<script setup>
import { ref, computed } from 'vue'
import { useStore } from 'vuex'

const store = useStore()

const props = defineProps({
    task: {
        type: [Object],
        required: true
    },
    statusColor: {
        type: [String],
        default: ''
    }
});

const isModalOpen = ref(false)

const openModal = () => {
    isModalOpen.value = true
}

const closeModal = () => {
    isModalOpen.value = false
}

const isUpdating = computed(() => {
    return store.getters.isUpdatingTask
})
</script>