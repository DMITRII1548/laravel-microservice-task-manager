<template>
    <main class="flex justify-center items-center h-screen">
        <form class="flex flex-col gap-4 w-72 p-3 mx-auto border-2 border-slate-500 rounded-md">
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
            <Field
                type="password_confirmation"
                v-model="password_confirmation"
                :error="errors.password_confirmation"
                placeholder="Confirm password"
             />      
            <div class="flex justify-between">
                <router-link :to="{ name: 'sing_in' }" class="text-sky-500 mt-1">Already have an account?</router-link>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded-md">Sing up</button>
            </div>
        </form>
    </main>
</template>

<script setup>
import { ref, reactive } from 'vue'

import Field from '../../components/Field.vue';

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')

const errors = reactive({
    name: '',
    email: '',
    password: '',
})
</script>
