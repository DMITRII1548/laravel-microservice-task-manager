<template>
    <div class="flex flex-wrap gap-4">
        <button @click="openModal()" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">Create task</button>
    </div>

    <!-- Main modal -->
    <div v-if="isModalOpen" tabindex="-1" class="fixed overflow-y-auto inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Create New Task</h3>
                    <button @click="closeModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form @submit.prevent="storeTask()" class="p-4 md:p-5">
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
                    </div>
                    <button :disabled="isDisabled" type="submit" class="flex items-center bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        Add new task
                    </button>
                </form>
            </div>
        </div>
    </div> 
</template>

<script setup>
import { ref, computed } from 'vue'
import { useStore } from 'vuex'

import TagsField from '../../components/TagsField.vue'
import Field from '../../components/Field.vue'
import TextareaField from '../../components/TextareaField.vue'


const store = useStore()

const isModalOpen = ref(false)

const title = ref('')
const content = ref('')
const tags = ref([])

const errors = computed(() => {
    return {
        title: store.getters.titleTaskError,
        content: store.getters.contentTaskError,
        tags: store.getters.tagsTaskError
    }
})

const isDisabled = computed(() => store.getters.isLoadingTask)

const openModal = () => {
    isModalOpen.value = true
}

const closeModal = () => {
    isModalOpen.value = false
}

const storeTask = async () => {
    store.dispatch('storeTask', {
        title: title.value,
        content: content.value,
        tags: tags.value
    })
        .then(res => {
            title.value = ''
            content.value = ''
            tags.value = []

            closeModal()        
        })
}
</script>
