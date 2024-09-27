<template>
    <div class="w-100">
        <select 
            v-model="modelValue"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            :required="required"
         >
            <option selected disabled>{{ placeholder }}</option>
            <option value="CREATED" class="text-green-500">CREATED</option>
            <option value="PROCESSING" class="text-sky-500">PROCESSING</option>
            <option value="FINISHED" class="text-violet-500">FINISHED</option>
            <option value="CANCELED" class="text-red-500">CANCELED</option>
        </select>
        <p v-if="error" class="text-red-600 mt-1 ml-1">{{ error }}</p>
    </div>
</template>
    
<script setup>
    import { ref, watch } from 'vue';
    
    const props = defineProps({
        placeholder: {
            type: String,
            default: ''
        },
        modelValue: {
            type: String,
            required: true
        },
        error: {
            type: String,
            default: ''
        },
        required: {
            type: Boolean,
            default: true
        }
    });
    
    const emit = defineEmits();
    const value = ref(props.modelValue);
    
    watch(() => props.modelValue, (newValue) => {
        value.value = newValue;
    });
    
    watch(value, (newValue) => {
        emit('update:modelValue', newValue);
    });
</script>
  