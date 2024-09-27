<template>
    <button @click="openModal()" class="flex justify-center items-center text-center h-10 w-10 bg-sky-500 hover:bg-sky-600 text-white p-2 rounded">
        <img src="/imgs/icons/edit.svg" alt="Edit">
    </button>

    <!-- Main modal -->
    <div v-if="isModalOpen" tabindex="-1" class="fixed overflow-y-auto inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit a task</h3>
                    <button @click="closeModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form @submit.prevent="" class="p-4 md:p-5">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <Field
                                v-model="title"
                                type="text"
                                placeholder="Title"
                                :error="errors.title"
                            />
                        </div>
                        <div class="col-span-2">
                            <TextareaField
                                v-model="content"
                                placeholder="Write task description here"
                                :error="errors.content"
                             />               
                        </div>
                        <div class="col-span-2">
                            <TagsField 
                                v-model="tags"
                                placeholder="Write a new tag"
                                :error="errors.tags"
                             />
                        </div>
                        <div class="col-span-2">
                            <SelectStatus 
                                v-model="status"
                                placeholder="Select status"
                             />
                        </div>
                        <div class="col-span-2">
                            <label for="started_at" class="ml-1 font-semibold">Started:</label>
                            <Field
                                id="started_at"
                                v-model="startedAt"
                                type="datetime-local"
                            />
                        </div>
                        <div class="col-span-2">
                            <label for="finished_at" class="ml-1 font-semibold">Finished:</label>
                            <Field
                                id="finished_at"
                                v-model="finishedAt"
                                type="datetime-local"
                            />
                        </div>
                    </div>
                    <button :disabled="isDisabled" type="submit" class="flex items-center bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div> 
</template>

<script setup>
import { ref, computed, defineProps, onMounted } from 'vue'
import { useStore } from 'vuex'

import TagsField from '../../components/TagsField.vue'
import Field from '../../components/Field.vue'
import TextareaField from '../../components/TextareaField.vue'
import SelectStatus from '../../components/SelectStatus.vue'

const store = useStore()

const isModalOpen = ref(false)

const title = ref('')
const content = ref('')
const tags = ref([])
const status = ref('')
const startedAt = ref('')
const finishedAt = ref('')

const props = defineProps({
    task: {
        type: [Object],
        required: true
    }
})

onMounted(() => {
    title.value = props.task.title
    content.value = props.task.content
    tags.value = props.task.tags
    startedAt.value = props.task.started_at ? new Date(props.task.started_at).toISOString().slice(0, 16) : null
    finishedAt.value = props.task.finished_at ? new Date(props.task.finished_at).toISOString().slice(0, 16) : null
    status.value = props.task.status
})

const errors = computed(() => {
    return {
        title: store.getters.titleTaskError,
        content: store.getters.contentTaskError,
        tags: store.getters.tagsTaskError
        // status: store.getters.statusTaskError
    }
})

const isDisabled = computed(() => store.getters.isUpdatingTask)

const openModal = () => {
    isModalOpen.value = true
}

const closeModal = () => {
    isModalOpen.value = false
}
</script>
