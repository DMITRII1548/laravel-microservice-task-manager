<template>
    <div class="w-100">
        <input 
            :type="type" 
            v-model="value"
            :placeholder="placeholder"
            :required="required"
            class="w-full p-1 border-2 border-slate-200 hover:border-slate-300 focus:border-slate-300"
        />
        <p v-if="error" class="text-red-600 mt-1 ml-1">{{ error }}</p>
    </div>
  </template>
    
  <script setup>
    import { ref, toRefs, watch } from 'vue';
    
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
  