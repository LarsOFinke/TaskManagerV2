<template>
    <div class="w-full flex flex-col max-w-sm bg-gray-700 rounded-lg shadow-md p-6 mx-auto">
        <h2 class="text-2xl font-bold text-gray-100 mb-4 text-center">Edit</h2>

        <!-- Message-Box -->
        <message-box :msg="msg" :errorPhrase="errorPhrase"></message-box>

        <!-- Edit-Form -->
        <div>
            <!-- Priority -->
            <div class="flex mb-4 justify-between">
                <label class="w-full flex justify-end text-sm font-medium text-gray-100">Priority
                    <select v-model.trim="priority" name="priority"
                        class="ml-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 border-gray-300 rounded-md shadow-sm"
                        :class="priority === 'high' ? 'text-red-600 ' : ''" required>
                        <option value="high" class="text-red-600 bg-gray-700">High</option>
                        <option value="medium" class="text-gray-100 bg-gray-700">Medium</option>
                        <option value="low" class="text-gray-100 bg-gray-700">Low</option>
                    </select>
                </label>
            </div>

            <!-- Topic -->
            <div class="flex mb-4 justify-between">
                <label class="text-sm font-medium text-gray-100">Topic
                    <select v-model.trim="topic" name="topic"
                        class="ml-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 border-gray-300 rounded-md shadow-sm"
                        required>
                        <option v-for="tpc in topicList" class="text-gray-100 bg-gray-700">{{ tpc.name }}</option>
                    </select>
                </label>

                <!-- Category -->
                <label class="text-sm font-medium text-gray-100">Category
                    <select v-model.trim="category" name="category"
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
                    <input v-model="deadlineDate" name="deadlineDate" type="date"
                        class="mt-1 w-fit px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <input v-model="deadlineTime" name="deadlineTime" type="time"
                        class="mt-1 w-fit px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </label>
            </div>

            <!-- Interval -->
            <div v-if="category === 'recurring'" class="mb-4">
                <label class="text-sm font-medium text-gray-100">Interval
                    <select v-model.trim="interval" name="interval"
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
                    <input v-model="startDate" name="startDate" type="date"
                        class="mt-1 w-fit px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </label>
            </div>

            <!-- Title -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-100">Title
                    <input v-model.trim="title" name="title" type="text" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </label>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-100">Description
                    <textarea v-model.trim="description" name="description" type="text" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </label>
            </div>

            <!-- ToDo's -->
            <!-- preserved todos -->
            <template v-for="todo in todoList" :key="todo.id">
                <!-- tell Symfony “keep this ID” -->
                <input type="hidden" name="existingTodos[]" :value="todo.id">
                <!-- and send along the edited text for that ID -->
                <input type="hidden" :name="`todoTexts[${todo.id}]`" :value="todo.text">
            </template>
            <!-- brand-new todos -->
            <template v-for="(todo, i) in newTodoList" :key="i">
                <input type="hidden" name="newTodos[]" :value="todo.text">
            </template>
            <!-- Visible-Part -->
            <div class="mb-4 text-gray-100">
                <label class="block text-sm font-medium text-gray-100">To-Do's</label>
                <div class="w-full max-w-sm bg-gray-600 rounded-lg shadow-md p-2 mx-auto relative mb-2">
                    <div class="text-sm mb-4 overflow-x-auto  max-h-18">
                        <ul v-for="todo in todoList" :key="todo.id" class="list-disc pl-6">
                            <li>
                                <div class="flex mb-2">
                                    <input type="text" v-model="todo.text">
                                    <button type="button" @click.prevent="deleteTodo(todo)"
                                        class="w-fit bg-red-800 text-gray-100 py-1 px-2 rounded-md hover:bg-red-700 transition">Delete</button>
                                </div>
                            </li>
                        </ul>
                        <ul v-for="todo in newTodoList" :key="todo.id" class="list-disc pl-6">
                            <li>
                                <div class="flex mb-2">
                                    <input type="text" v-model="todo.text">
                                    <button type="button" @click.prevent="deleteNewTodo(todo)"
                                        class="w-fit bg-red-800 text-gray-100 py-1 px-2 rounded-md hover:bg-red-700 transition">Delete</button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="w-full flex justify-between">
                    <input v-model.trim="newTodo" type="text" placeholder="New to-do"
                        class="mt-1 block w-fill px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <button type="button" @click.prevent="addToDo"
                        class="w-fill h-fit my-auto bg-indigo-700 text-gray-100 py-2 px-2 rounded-md hover:bg-indigo-600 transition">Add</button>
                </div>
            </div>

            <!-- Utility-Buttons -->
            <div class="w-full flex justify-between">
                <button type="button" @click.prevent="goToTaskList"
                    class="w-fit bg-red-800 text-gray-100 py-1 px-4 rounded-md hover:bg-red-700 transition">Back</button>

                <button type="reset"
                    class="w-fit bg-indigo-700 text-gray-100 py-1 px-4 rounded-md hover:bg-indigo-600 transition">Reset</button>

                <button type="submit"
                    class="w-fit bg-indigo-700 text-gray-100 py-1 px-2 rounded-md hover:bg-indigo-600 transition">Edit</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import MessageBox from '@/components/shared/Message-Box.vue';
import { useApiTopicService } from '../api/services/ApiTopicService';
import { useApiTaskService } from '@/components/api/services/ApiTaskService'
const { topicList, getAllTopics} = useApiTopicService();
const { loading } = useApiTaskService()
const props = defineProps({
    taskListUrl: String,
    task: Object,
})

const msg = ref('')
const errorPhrase = ref('')
const priority = ref(props.task.priority)
const topic = ref(props.task.topic)
const category = ref(props.task.category)
const deadlineDate = ref(props.task.deadlineDate)
const deadlineTime = ref(props.task.deadlineTime)
const interval = ref(props.task.interval)
const startDate = ref(props.task.startDate)
const title = ref(props.task.title)
const description = ref(props.task.description)
const todoList = ref([...props.task.todoList])
const newTodoList = ref([])
const newTodo = ref('')
let nextId = props.task.todoList.length + 1

onMounted(async () => {
    try {
        msg.value = 'Fetching topics...'
        await getAllTopics()
        msg.value = ''
    }
    catch {
        errorPhrase.value = 'Something went wrong!'
    }
})

watch(loading, (v) => { msg.value = v ? 'Editing…' : '' })

const goToTaskList = () => { window.location.href = props.taskListUrl; }

function deleteTodo(todoItem) {
    const idx = todoList.value.findIndex(t => t.id === todoItem.id)
    if (idx !== -1) {
        todoList.value.splice(idx, 1)
    }
}

function deleteNewTodo(todoItem) {
    const idx = newTodoList.value.findIndex(t => t.id === todoItem.id)
    if (idx !== -1) {
        newTodoList.value.splice(idx, 1)
    }
}

function addToDo() {
    newTodoList.value.push({ id: nextId++, text: newTodo.value })
    newTodo.value = ''
}
</script>