// Implement todoList instead of using it in the task-object to further separate concerns!?

import { ref } from "vue";
import environment from "@/environments/environment";

const api = environment.api;
const loading = ref(false);
const error = ref(null);

const closeTodo = async (todoId) => {
    loading.value = true;
    error.value = null;
    try {
        await api.post("todos/close", { todoId });
        return true;
    } catch (err) {
        error.value = err.response?.data?.message || "Closing todo failed.";
        return false;
    } finally {
        loading.value = false;
    }
};

const openTodo = async (todoId) => {
    loading.value = true;
    error.value = null;
    try {
        await api.post("todos/open", { todoId });
        return true;
    } catch (err) {
        error.value = err.response?.data?.message || "Opening todo failed.";
        return false;
    } finally {
        loading.value = false;
    }
};

export function useApiTodoService() {
    return {
        loading,
        error,
        closeTodo,
        openTodo,
    };
}
