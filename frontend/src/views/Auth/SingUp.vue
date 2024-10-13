<template>
    <main class="flex justify-center items-center h-screen">
        <form @submit.prevent="store.dispatch('register', {
            name,
            email, 
            password
        })" 
        class="flex flex-col gap-4 w-72 p-3 mx-auto border-2 border-slate-500 rounded-md">
            <h1 class="text-lg font-medium text-center">Sign up</h1>
            <Field
                type="text"
                v-model="name"
                :error="errors.name"
                placeholder="Name"
             />
            <Field
                type="email"
                v-model="email"
                :error="errors.email"
                placeholder="E-mail"
             />
            <Field
                type="password"
                v-model="password"
                :error="errors.password"
                placeholder="Password"
             />      
            <div class="flex justify-between">
                <router-link :to="{ name: 'sing_in' }" class="text-sky-500 mt-1">Already have an account?</router-link>
                <button :disabled="isDisabled" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded-md">Sing up</button>
            </div>
        </form>
    </main>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useStore } from 'vuex'

import Field from '../../components/Field.vue';

const store = useStore()

const name = ref('')
const email = ref('')
const password = ref('')

const isDisabled = computed(() => store.getters.isLoading)
const errors = computed(() => {
    return {
        name: store.getters.nameError,
        email: store.getters.emailError,
        password: store.getters.passwordError
    }
})
</script>
