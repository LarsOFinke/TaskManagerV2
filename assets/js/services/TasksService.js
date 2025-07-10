// IMPLEMENT CALCULATE REMAINING TIME //

import { ref } from "vue";
import environment from "@/environments/environment";

const api = environment.api;
const loading = ref(false);
const error = ref(null);
const topicList = ref([]);
const doneCount = ref(0);

const addNewTopic = async (newTopic) => {
    loading.value = true;
    error.value = null;
    try {
        await api.post("tasks/create-topic", newTopic);
        return true;
    } catch (err) {
        error.value = err.response?.data?.message || "Adding new topic failed.";
        return false;
    } finally {
        loading.value = false;
    }
};

const getUserTopics = async () => {
    loading.value = true;
    error.value = null;
    try {
        const response = await api.get("tasks/get-user-topics");
        topicList.value = response.data.topicList;
        error.value = null;
        return true;
    } catch (err) {
        error.value = err.response?.data?.message || "Fetching topics failed.";
        return false;
    } finally {
        loading.value = false;
    }
};

const addNewTask = async (submitUrl, newTask) => {
    loading.value = true;
    error.value = null;
    try {
        await api.post(submitUrl, newTask);
        return true;
    } catch (err) {
        error.value = err.response?.data?.message || "Adding new task failed.";
        return false;
    } finally {
        loading.value = false;
    }
};

const getDoneTasksCount = async (userId) => {
    loading.value = true;
    error.value = null;
    try {
        const response = await api.post("tasks/get-done-count", { userId });
        if (response.data.success) {
            doneCount.value = response.data.doneCount;
            return true;
        }
        return false;
    } catch (err) {
        error.value =
            err.response?.data?.message || "Fetching done tasks count failed.";
        return false;
    } finally {
        loading.value = false;
    }
};

const deleteTask = async (taskId) => {
    loading.value = true;
    error.value = null;
    try {
        await api.post("tasks/delete", { taskId });
        return true;
    } catch (err) {
        error.value = err.response?.data?.message || "Deleting task failed.";
        return false;
    } finally {
        loading.value = false;
    }
};

const closeTask = async (taskId) => {
    loading.value = true;
    error.value = null;
    try {
        await api.post("tasks/close", { taskId });
        return true;
    } catch (err) {
        error.value = err.response?.data?.message || "Closing task failed.";
        return false;
    } finally {
        loading.value = false;
    }
};

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

export function useTasksService() {
    return {
        loading,
        error,
        topicList,
        doneCount,
        addNewTopic,
        getUserTopics,
        addNewTask,
        getDoneTasksCount,
        deleteTask,
        closeTask,
        closeTodo,
        openTodo,
    };
}
