<template>
    <main class="w-screen h-screen flex justify-center items-center" style="background: url('/imgs/home-bg.avif');  background-size: cover;">
        <div class="block-opacity-50 p-4 flex flex-col justify-center items-center gap-3">
            <div class="z-10">
                <h1 class="text-white text-7xl text-center font-semibold">The best task manager</h1>
            </div>
            <div class="z-10 flex justify-center">
                <WhiteLinkButton to="/">Sign in</WhiteLinkButton>
            </div>
        </div>
    </main>
</template>

<script setup>
import WhiteLinkButton from '../components/WhiteLinkButton.vue'
</script>