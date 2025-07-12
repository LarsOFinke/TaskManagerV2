<template>
    <div class="relative w-full flex flex-col max-w-sm bg-gray-700 rounded-lg shadow-md p-6 mx-auto">
        <!-- Task-Form-Header -->
        <h2 class="text-2xl font-bold text-gray-100 mb-4 text-center">Add a new Task!</h2>

        <!-- Message-Box -->
        <message-box :msg="msg" :errorPhrase="errorPhrase"></message-box>

        <!-- Task-Form -->
        <form>
            <div class="flex mb-4 justify-between">
                <!-- Priority -->
                <label class="w-full flex justify-end text-sm font-medium text-gray-100">Priority
                    <select name="priority" v-model.trim="priority"
                        class="ml-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 border-gray-300 rounded-md shadow-sm"
                        :class="priority === 'high' ? 'text-red-600 ' : ''" required>
                        <option value="high" class="text-red-600 bg-gray-700">High</option>
                        <option value="medium" class="text-gray-100 bg-gray-700">Medium</option>
                        <option value="low" class="text-gray-100 bg-gray-700">Low</option>
                    </select>
                </label>
            </div>

            <div class="flex mb-4 justify-between">
                <!-- Topic -->
                <label class="text-sm font-medium text-gray-100">Topic
                    <select name="topic" v-model.trim="topic"
                        class="ml-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 border-gray-300 rounded-md shadow-sm"
                        required>
                        <option v-for="tpc in topicList" :key="tpc.id" :value="tpc.id" class="text-gray-100 bg-gray-700">{{ tpc.name }}</option>
                    </select>
                    <button @click.prevent="toggleNewTopic(true)" type="button"
                        class="w-fit text-sm bg-indigo-700 text-gray-100 p-1 rounded-md hover:bg-indigo-600 transition">Add</button>
                </label>
                <!-- New-Topic -->
                <div v-if="viewNewTopic" class="fixed inset-0 bg-gray-900 flex items-center justify-center z-50">
                    <div
                        class="flex flex-col items-center bg-gray-700 p-6 rounded-lg shadow-lg w-3/4 max-w-md relative">
                        <label class="block items-center text-md font-medium text-gray-100 mt-6 mb-4">Topic name
                            <input v-model.trim="newTopicName" type="text" placeholder="Topic name"
                                class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </label>

                        <button @click.prevent="addTopic" type="button"
                            class="w-fit h-fit text-md bg-indigo-700 text-gray-100 p-1 rounded-md hover:bg-indigo-600 transition">Add
                            new topic</button>

                        <!-- Close New-Topic Button -->
                        <button @click.prevent="toggleNewTopic(false)" type="button"
                            class="absolute top-1 right-1 text-md text-red-700 hover:text-red-600 px-2 border-solid border-2 rounded-2xl border-gray-900">
                            X
                        </button>
                    </div>
                </div>

                <!-- Category -->
                <label class="text-sm font-medium text-gray-100">Category
                    <select name="category" v-model.trim="category"
                        class="ml-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 border-gray-300 rounded-md shadow-sm"
                        required>
                        <option value="default" class="text-gray-100 bg-gray-700">Default</option>
                        <option value="timed" class="text-gray-100 bg-gray-700">Timed</option>
                        <option value="recurring" class="text-gray-100 bg-gray-700">Recurring</option>
                    </select>
                </label>
            </div>

            <!-- Deadline -->
            <div v-if="category === 'timed'" class="mb-4">
                <label class="text-sm font-medium text-gray-100">Deadline
                    <input name="deadlineDate" v-model.trim="deadlineDate" type="date"
                        class="mt-1 w-fit px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <input name="deadlineTime" type="time"
                        class="mt-1 w-fit px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </label>
            </div>

            <!-- Interval -->
            <div v-if="category === 'recurring'" class="mb-4">
                <label class="text-sm font-medium text-gray-100">Interval
                    <select name="interval" v-model.trim="interval"
                        class="ml-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 border-gray-300 rounded-md shadow-sm">
                        <option value="daily" class="text-gray-100 bg-gray-700">Daily</option>
                        <option value="weekly" class="text-gray-100 bg-gray-700">Weekly</option>
                        <option value="monthly" class="text-gray-100 bg-gray-700">Monthly</option>
                    </select>
                </label>
            </div>

            <!-- Start Date -->
            <div v-if="category === 'recurring'" class="mb-4">
                <label class="text-sm font-medium text-gray-100">Start Date
                    <input name="startDate" v-model.trim="startDate" type="date"
                        class="mt-1 w-fit px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </label>
            </div>

            <!-- Title -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-100">Title
                    <input name="title" v-model.trim="title" type="text" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </label>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-100">Description
                    <textarea name="description" v-model.trim="description" type="text" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </label>
            </div>

            <!-- ToDo's -->
            <div class="flex flex-col justify-between mb-8">
                <label class="text-sm font-medium text-gray-100">To-do's</label>

                <div class="w-full text-gray-100 flex justify-between">
                    <input v-model.trim="newTodo" type="text" placeholder="New to-do"
                        class="mt-1 block w-fill px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <button type="button" @click.prevent="addToDo"
                        class="w-fill h-fit my-auto bg-indigo-700 text-gray-100 py-2 px-2 rounded-md hover:bg-indigo-600 transition">Add</button>
                </div>

                <!-- Scrollable To-Do-List -->
                <!-- hidden JSON dump of the entire todoList -->
                <template v-for="(todo, i) in todoList" :key="i">
                    <input type="hidden" name="todoList[]" :value="todo.text" />
                </template>
                <div class="overflow-x-auto max-h-95 shadow-md p-6 text-gray-100">
                    <ul v-for="todo in todoList" :key="todo.id" class="list-disc">
                        <li>{{ todo.text }}</li>
                    </ul>
                </div>
            </div>

            <!-- Utility-Buttons -->
            <div class="w-full flex justify-between">
                <button type="button" @click.prevent="goToList"
                    class="w-fit bg-red-800 text-gray-100 py-1 px-4 rounded-md hover:bg-red-700 transition">Back</button>

                <button type="reset"
                    class="w-fit bg-indigo-700 text-gray-100 py-1 px-4 rounded-md hover:bg-indigo-600 transition">Reset</button>

                <button type="submit" @click.prevent="submitNewTask"
                    class="w-fit bg-indigo-700 text-gray-100 py-1 px-2 rounded-md hover:bg-indigo-600 transition">Submit</button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import MessageBox from '@/components/shared/Message-Box.vue';
import { useApiTopicService } from '@/api/services/ApiTopicService';
import { useApiTaskService } from '@/api/services/ApiTaskService';
const { topicList, getAllTopics, addNewTopic, loading, error} = useApiTopicService();
const { addNewTask } = useApiTaskService();
const props = defineProps({
    submitUrl: { type: String, required: true },
    taskListUrl: { type: String, required: true },
})

const msg = ref('')
const errorPhrase = ref('')
const priority = ref('')
const topic = ref('')
const newTopicName = ref('')
const category = ref('')
const deadlineDate = ref('')
const deadlineTime = ref('')
const interval = ref('')
const startDate = ref('')
const title = ref('')
const description = ref('')
const newTodo = ref('')
const todoList = ref([])
const id = ref(0)
const viewNewTopic = ref(false)

onMounted(() => {
    getAllTopics();
})

const goToList = () => {
    window.location.href = props.taskListUrl;
}

const toggleNewTopic = (showNewTopic) => {
    if (showNewTopic) {
        viewNewTopic.value = true
    } else {
        viewNewTopic.value = false
    }
}

const addTopic = async () => {
    if (await addNewTopic({
        topicName: newTopicName.value,
        teamId: 0,
    })) {
        window.location.reload()
    } else {
        msg.value = 'Something went wrong adding the new topic!'
    }
}

const addToDo = () => {
    id.value++
    todoList.value.push({ id, text: newTodo.value })
    newTodo.value = ''
}

const submitNewTask = async () => {
    const newTask = {
        title: title.value,
        TopicIDRef: topic.value,
        category: category.value,
        priority: priority.value,
        deadlineDate: deadlineDate.value || null,
        deadlineTime: deadlineTime.value || null,
        startDate: startDate.value || null,
        remainingTime: 'XXd:XXh:XXmin left',
        description: description.value,
        todoList: todoList.value.map(t => t.text),
    }

    if (await addNewTask(props.submitUrl, newTask)) {
        window.location.href = props.taskListUrl;
    } else {
        msg.value = 'Something went wrong!'
    }

}
</script>