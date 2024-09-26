<template>
    <div class="w-100">
        <textarea 
            v-model="value"
            :placeholder="placeholder"
            :required="required"
            rows="4" 
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
        ></textarea>   
       
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
  