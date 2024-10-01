<template>
    <div class="bg-gray-100">
        <div class="container mx-auto py-8">
            <div class="grid grid-cols-4 sm:grid-cols-12 gap-6 px-4">
                <div class="col-span-4 sm:col-span-3">
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex flex-col items-center">
                            <img src="/imgs/user-icon.png">
                            <h1 class="text-xl font-bold text-center">Name Surname Patronymic</h1>
                            <p class="text-gray-700">age: 32 years</p>
                            
                            <div class="mt-6 flex flex-wrap gap-4 justify-center">
                                <CreateProfile v-if="!profile" />
                                <button v-if="profile" class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded">Edit profile</button>
                                <button v-if="profile" class="bg-red-300 hover:bg-red-400 text-red-700 py-2 px-4 rounded">Delete profile</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-4 sm:col-span-9">
                    <div class="bg-white shadow rounded-lg p-6">
                        <CreateTaskButton />
                        <div v-if="tasks" class="mt-6 flex flex-col gap-3">
                            <template v-for="task in tasks">
                                <TaskItem 
                                    :task="task"
                                 />
                            </template>
                        </div>
                        <div ref="target"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import TaskItem from '../../components/Task/TaskItem.vue'
import CreateTaskButton from '../../components/Task/Create.vue'
import CreateProfile from '../../components/Profile/Create.vue'

import { onMounted, onUnmounted, computed, ref } from 'vue'
import { useStore } from 'vuex'

const store = useStore()

const target = ref(null)

let currectPage = 1
let isLoadingPage = false

const checkVisibility = (entries) => {
    entries.forEach(async (entry) => {
        if (entry.isIntersecting && !isLoadingPage) {
            isLoadingPage = true

            currectPage++
            await store.dispatch('getTasks', currectPage)
            isLoadingPage = false
        }
    })
}

onMounted(async () => {
    store.dispatch('getProfile')
    await store.dispatch('getTasks')

    const observer = new IntersectionObserver(checkVisibility)
    if (target.value) {
        observer.observe(target.value)
    }
    
    onUnmounted(() => {
        if (target.value) {
            observer.unobserve(target.value)
        }
    })
})

const tasks = computed(() => {
    return store.getters.tasks
})

const profile = computed(() => {
    return store.getters.profile
})
</script>