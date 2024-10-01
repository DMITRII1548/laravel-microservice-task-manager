<template>
    <div class="flex flex-wrap gap-4">
        <button @click="openModal()" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Create profile</button>
    </div>

    <!-- Main modal -->
    <div v-if="isModalOpen" tabindex="-1" class="fixed overflow-y-auto inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Create profile</h3>
                    <button @click="closeModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form class="p-4 md:p-5">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2 flex justify-center">
                            <VueImageInput
                                size="size-76"
                                title="Drop your avatar here (optional)"
                                bgRounded="12px"
                                v-model:file="image"
                             />
                        </div>
                        <div class="col-span-2">
                            <Field
                                type="text"
                                placeholder="Name" 
                                v-model="name"
                             />
                        </div>
                        <div class="col-span-2">
                            <Field
                                type="text"
                                placeholder="Surname" 
                                v-model="surname"
                             />
                        </div>
                        <div class="col-span-2">
                            <Field
                                type="text"
                                placeholder="Patronymic" 
                                v-model="patronymic"
                             />
                        </div>
                        <div class="col-span-2">
                            <Field
                                type="number"
                                min="1"
                                placeholder="Age"
                                v-model="age" 
                             />
                        </div>
                    </div>
                    <button :disabled="isDisabled" type="submit" class="flex items-center bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">
                        Store profile
                    </button>
                </form>
            </div>
        </div>
    </div> 
</template>

<script setup>
import { ref, computed } from 'vue'
import { useStore } from 'vuex'
import { VueImageInput } from 'vue3-picture-input' 
import "vue3-picture-input/style.css"

import Field from '../Field.vue'

const store = useStore()

const isModalOpen = ref(false)
const image = ref(null)
const name = ref('')
const surname = ref('')
const patronymic = ref('')
const age = ref(null)

const isDisabled = computed(() => store.getters.isLoadingTask)

const openModal = () => {
    isModalOpen.value = true
}

const closeModal = () => {
    isModalOpen.value = false
}
</script>

<style>
.size-76 {
    width: 19rem !important;
}
</style>