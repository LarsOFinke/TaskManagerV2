import { inject, ref } from "vue";

export function useApiTodoService() {
    const api = inject("api");
    if (!api) {
        throw new Error(
            "[useApiTodoService] Axios-Instanz wurde nicht gefunden. " +
                "Hast du in app.js `provide('api', api)` gesetzt?"
        );
    }
    const loading = ref(false);
    const error = ref(null);
    const todos = ref([]);

    const getTodos = async (taskId) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await api.get(`api/todo/get/${taskId}`);
            if (response.data.success) {
                todos.value = response.data.todoList;
                return true;
            }
            return false;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Fetching todos failed.";
            return false;
        } finally {
            loading.value = false;
        }
    };

    const closeTodo = async (todoId) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await api.post("api/todo/close", { todoId });
            if (response.data.success) {
                return true;
            }
            return false;
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
            const response = await api.post("api/todo/open", { todoId });
            if (response.data.success) {
                return true;
            }
            return false;
        } catch (err) {
            error.value = err.response?.data?.message || "Opening todo failed.";
            return false;
        } finally {
            loading.value = false;
        }
    };

    return {
        loading,
        error,
        todos,
        getTodos,
        closeTodo,
        openTodo,
    };
}
