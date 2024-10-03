<template>
    <button @click="openModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded">Edit profile</button>

    <!-- Main modal -->
    <div v-if="isModalOpen" tabindex="-1" class="fixed overflow-y-auto inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit profile</h3>
                    <button @click="closeModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form @submit.prevent="updateProfile()" class="p-4 md:p-5">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2 flex flex-col gap-1 items-center">
                            <VueImageInput
                                size="size-76"
                                title="Drop your avatar here (optional)"
                                bgRounded="12px"
                                v-model:file="image"
                             />
                            <p v-if="errors.image" class="text-red-600 mt-1 ml-1 text-center">{{ errors.image }}</p>
                        </div>
                        <div class="col-span-2">
                            <Field
                                type="text"
                                placeholder="Name" 
                                v-model="name"
                                :error="errors.name"
                             />
                        </div>
                        <div class="col-span-2">
                            <Field
                                type="text"
                                placeholder="Surname" 
                                v-model="surname"
                                :error="errors.surname"
                             />
                        </div>
                        <div class="col-span-2">
                            <Field
                                type="text"
                                placeholder="Patronymic" 
                                v-model="patronymic"
                                :error="errors.patronymic"
                             />
                        </div>
                        <div class="col-span-2">
                            <Field
                                type="number"
                                min="1"
                                placeholder="Age"
                                v-model="age"
                                :error="errors.age" 
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
import { ref, computed } from 'vue'
import { useStore } from 'vuex'
import { VueImageInput } from 'vue3-picture-input' 
import "vue3-picture-input/style.css"

import Field from '../Field.vue'

const store = useStore()

const isModalOpen = ref(false)
const image = ref(null)

const name = ref(store.getters.profile?.name)
const surname = ref(store.getters.profile?.surname)
const patronymic = ref(store.getters.profile?.patronymic)
const age = ref(store.getters.profile?.age)

const errors = computed(() => {
    return {
        image: store.getters.imageProfileError,
        name: store.getters.nameProfileError,
        surname: store.getters.surnameProfileError,
        patronymic: store.getters.patronymicProfileError,
        age: store.getters.ageProfileError
    }
}) 

const isDisabled = computed(() => store.getters.isLoadingProfile)

const openModal = () => {
    isModalOpen.value = true
}

const closeModal = () => {
    isModalOpen.value = false
}

const updateProfile = () => {
    const formData = new FormData()

    if (image.value) formData.append('image', image.value[0])

    formData.append('name', name.value)
    formData.append('surname', surname.value)
    formData.append('patronymic', patronymic.value)
    formData.append('age', age.value)
    console.log(name.value)

    store.dispatch('updateProfile', formData)
        .then(() => {
            closeModal()
        })
}
</script>

<style>
.size-76 {
    width: 19rem !important;
}
</style>