<template>
    <main class="flex justify-center items-center h-screen">
        <form class="flex flex-col gap-4 w-72 p-3 mx-auto border-2 border-slate-500 rounded-md">
            <h1 class="text-lg font-medium text-center">Sign in</h1>
            <Field
                type="email"
                v-model="email"
                :error="errors.email"
                placeholder="E-mail"
             />
            <Field
                type="password"
                v-model="password"
                placeholder="Password"
             />
            <div class="flex justify-between">
                <a href="#" class="text-sky-500 mt-1">Don't have an account?</a>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded-md">Sing in</button>
            </div>
        </form>
    </main>
</template>

<script setup>
import { ref, reactive } from 'vue'

import Field from '../../components/Field.vue';

const email = ref('')
const password = ref('')

const errors = reactive({
    email: '',
})
</script>
