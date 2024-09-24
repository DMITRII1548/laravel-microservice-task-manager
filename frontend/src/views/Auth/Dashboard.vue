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
                                <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Create profile</a>
                                <a href="#" class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded">Edit profile</a>
                                <a href="#" class="bg-red-300 hover:bg-red-400 text-red-700 py-2 px-4 rounded">Delete profile</a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import TaskItem from '../../components/Task/TaskItem.vue'
import CreateTaskButton from '../../components/Task/Create.vue'

import { onMounted, computed } from 'vue'
import { useStore } from 'vuex'

const store = useStore()

onMounted(() => {
    store.dispatch('getTasks')
})

const tasks = computed(() => {
    return store.getters.tasks
})
</script>