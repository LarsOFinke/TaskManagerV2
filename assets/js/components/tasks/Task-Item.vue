<template>
    <div class="w-full max-w-sm bg-gray-600 rounded-lg shadow-md p-4 mb-4 mx-auto relative sm:mx-2">

        <!-- Message-Box -->
        <message-box :msg="msg" :errorPhrase="errorPhrase"></message-box>

        <!-- Task-Header -->
        <div class="flex items-center w-full mb-2 mt-4">
            <!-- Topic -->
            <div class="absolute text-sm font-semibold text-gray-100 mb-2 rounded-lg shadow-md p-1 top-0 left-0">
                <h4>{{ props.task.topic.name }}</h4>
            </div>
            <!-- Title -->
            <h3 class="w-full text-lg font-bold text-gray-100 mb-2">{{ props.task.title }}</h3>
            <div class="flex self-start text-gray-100">
                <!-- Priority -->
                <div class="">
                    <label class="text-xs font-semibold">Priority:</label>
                    <p :class="task.priority === 'high' ? 'text-red-500 text-sm font-semibold' : 'text-xs'">{{
                        props.task.priority }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Task-Info -->
        <div class="flex text-xs mb-4">
            <div v-if="task.category === 'timed' || task.category === 'recurring'" class="w-full text-gray-100">
                <label class="font-semibold">Deadline:</label>
                <br>
                <label class="mb-2">
                    {{ props.task.deadlineDate }}
                    {{ props.task.deadlineTime ? '\| ' + props.task.deadlineTime + 'h' : null }}
                </label>
                <label v-if="props.task.remainingTime !== null" class="float-right">
                    ({{ props.task.remainingTime }})</label>
            </div>
        </div>

        <!-- Toggle-Container -->
        <div class="flex flex-col text-gray-100">
            <!-- Toggle-Control-Buttons -->
            <div class="w-full text-sm flex justify-around">
                <button type="button" @click.prevent="toggleShowTodos(true)"
                    :class="showTodos ? 'bg-gray-800' : 'bg-gray-700 hover:bg-gray-900'"
                    class="w-50 text-gray-100 py-1 px-4 transition">To-Do's</button>
                <button type="button" @click.prevent="toggleShowTodos(false)"
                    :class="showTodos ? 'bg-gray-700 hover:bg-gray-900' : 'bg-gray-800'"
                    class="w-50 text-gray-100 py-1 px-4 transition">Description</button>
            </div>
            <!-- Task-To-Do's -->
            <div v-if="showTodos"
                class="w-full text-sm max-w-sm bg-gray-500 rounded-lg shadow-md p-1 mx-auto relative mb-2">
                <div class="text-sm mb-2 overflow-x-auto pl-6 max-h-25">
                    <ul v-for="todo in todoList" :key="todo.id" class="list-disc">
                        <li :value="todo.id" :class="{ 'line-through': todo.isCompleted }"
                            class="cursor-pointer shadow-md my-3 mr-2" @click.prevent="toggleTodoStatus(todo)">
                            <div class="flex justify-between items-center">
                                <p>{{ todo.text }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <p class="text-right">{{ doneTodos }} / {{ totalTodos }} done</p>
            </div>
            <!-- Task-Description -->
            <div v-else class="w-full max-w-sm bg-gray-500 rounded-lg shadow-md p-2 mx-auto relative mb-2">
                <div class="text-sm mb-4 overflow-x-auto  max-h-26">
                    <p class="overflow-y-hidden scrollbar-hide">{{ props.task.description }}</p>
                </div>
            </div>
        </div>

        <!-- Utility-Buttons -->
        <div>
            <div class="mb-2 absolute top-0 right-0">
                <button type="button" @click.prevent="deleteItem"
                    class="text-sm w-fit bg-red-800 text-gray-100 px-2 py-1 rounded-md hover:bg-red-700 transition">Delete</button>
            </div>
            <div class="text-sm w-full flex justify-around">
                <button v-if="props.task.canEdit" type="button" @click="goEdit"
                    class="w-fit bg-indigo-700 text-gray-100 py-1 px-4 rounded-md hover:bg-indigo-600 transition">Edit</button>
                <button type="button" @click.prevent="closeItem"
                    class="w-fit text-gray-100 py-1 px-2 rounded-md transition"
                    :class="itemCloseable ? 'bg-emerald-700 hover:bg-emerald-600' : 'bg-gray-500 hover:bg-gray-400 line-through cursor-not-allowed'"
                    :disabled="!itemCloseable">Done!</button>
            </div>
        </div>
    </div>


</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import MessageBox from '@/components/shared/Message-Box.vue';
import { useApiTodoService } from '@/api/ApiTodoService';
const { loading, error, todos, getTodos, closeTodo, openTodo } = useApiTodoService();
const props = defineProps({
    task: Object
})

const todoList = ref([]);
const msg = ref('')
const errorPhrase = ref('')
const emit = defineEmits(['hideItemEdit', 'updateTaskList', 'closeItem'])
const showTodos = ref(true)
const doneTodos = computed(() => todoList.value.filter(t => t.isCompleted).length)
const totalTodos = computed(() => todoList.value.length)
const itemCloseable = computed(() => !(doneTodos.value < totalTodos.value))

onMounted(async () => {
    await fetchTodos();
})

const fetchTodos = async () => {
    if (await getTodos(props.task.id)) {
        todoList.value = todos.value;
    } else {
        errorPhrase.value = "Something went wrong!";
        msg.value = "Failed to fetch todos!";
    }
}

const toggleShowTodos = (toggleOn) => {
    if (toggleOn) {
        showTodos.value = true
    } else {
        showTodos.value = false
    }
}

const goEdit = () => {
    window.location.href = props.task.editUrl;
}

const toggleTodoStatus = async (todo) => {
    if (!todo.isCompleted) {
        if (await closeTodo(todo.id)) {
            todo.isCompleted = true
            await fetchTodos();
        }
    } else {
        if (await openTodo(todo.id)) {
            todo.isCompleted = false
            await fetchTodos();
        }
    }
}

const deleteItem = async () => {
    await deleteTask(task.id)
    updateList()
}

const closeItem = async () => {
    await closeTask(task.id)
    updateList()
}

</script>