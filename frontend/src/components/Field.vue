<template>
    <div class="w-100">
        <input 
            :type="type" 
            v-model="value"
            :placeholder="placeholder"
            :required="required"
            :min="min"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
        />
        <p v-if="error" class="text-red-600 mt-1 ml-1">{{ error }}</p>
    </div>
</template>
    
<script setup>
    import { ref, watch } from 'vue';
    
    const props = defineProps({
        type: {
            type: String,
            required: true
        },
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
        },
        min: {
            type: Number,
            default: 0
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
  