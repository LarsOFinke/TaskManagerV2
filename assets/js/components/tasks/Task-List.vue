<template>
    <div v-if="viewTaskList && !viewItemEdit"
        class="w-full max-w-sm bg-gray-700 rounded-lg shadow-md p-6 mx-auto relative sm:max-w-3xl md:max-w-6xl">
        <!-- Header -->
        <h2 class="text-2xl font-bold text-gray-100 mb-4 text-center">{{ header }}</h2>

        <!-- Message-Box -->
        <message-box :msg="msg" :errorPhrase="errorPhrase"></message-box>

        <!-- Utility-Buttons -->
        <div class="flex justify-center mb-4">
            <button type="button" @click="goToForm"
                class="text-l w-fit bg-yellow-700 text-gray-100 px-2 py-1 mb-1 rounded-md hover:bg-yellow-600 transition">
                New task
            </button>
        </div>

        <!-- Scrollable Task List -->
        <div class="overflow-x-auto max-h-95 sm:overflow-y-auto sm:flex">
            <ul class="mb-8 sm:flex">
                <task-item v-for="task in taskList" :key="task.id" :task="task" :edit-url="task.editUrl"
                    :can-edit="task.canEdit" @updateTaskList="updateTaskList"></task-item>
            </ul>
        </div>

        <!-- <div v-if="mode === 'team'" class="w-full flex justify-center">
            <button type="button" @click.prevent="showTeamTasks"
                class="w-fit bg-red-700 text-gray-100 py-1 px-4 rounded-md hover:bg-red-600 transition">Back</button>
        </div> -->
    </div>
</template>

<script setup>
import { ref } from 'vue';
import MessageBox from '@/components/shared/Message-Box.vue';
import TaskItem from '@/components/tasks/Task-Item.vue';
const props = defineProps({
    header: { type: String, required: true },
    taskFormUrl: { type: String, required: true },
    taskList: { type: String, required: true },
})

const taskList = ref(JSON.parse(props.taskList))
const msg = ref('')
const errorPhrase = ref('')
const viewTaskList = ref(true)
const viewItemEdit = ref(false);

const goToForm = () => {
    window.location.href = props.taskFormUrl;
}

const updateTaskList = () => { 
    window.location.reload();
}
</script>