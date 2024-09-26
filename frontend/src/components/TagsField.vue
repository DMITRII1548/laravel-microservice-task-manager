<template>
    <div class="w-100 flex flex-col gap-2">
        <div v-if="modelValue.length > 0" class="flex flex-wrap gap bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <div 
                v-for="tag in modelValue" 
                :key="tag"
                class="inline-flex items-center gap-2 p-2 border-2 border-white rounded-2xl bg-indigo-500 text-white cursor-pointer"
                @click="dropTag(tag)"
             >
                <span>{{ tag }}</span>
                <svg class="w-2 h-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </div>
        </div>
        <ul class="flex gap-2">
            <input
                :placeholder="placeholder" 
                v-model="tagValue"
                type="text" 
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
             />  
             
            <button @click="addTag()" type="button" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Add</button>
        </ul>

        <p v-if="error" class="text-red-600 mt-1 ml-1">{{ error }}</p>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, ref } from 'vue'

const props = defineProps({
    modelValue: {
        type: Array,
        default: []
    },
    placeholder: {
        type: String,
        default: ''
    },
    error: {
        type: String,
        default: ''
    }
})

const emit = defineEmits(['update:modelValue'])

const tagValue = ref('')

const addTag = () => {
    if (!props.modelValue.includes(tagValue.value) && tagValue.value !== '') {
        emit('update:modelValue', [...props.modelValue, tagValue.value])
    }
    tagValue.value = ''
}

const dropTag = name => {
    const updatedTags = props.modelValue.filter(tag => tag !== name)
    emit('update:modelValue', updatedTags)
}
</script>
