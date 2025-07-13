// IMPLEMENT CALCULATE REMAINING TIME //
import { inject, ref } from "vue";

export function useApiTaskService() {
    const api = inject("api");
    if (!api) {
        throw new Error(
            "[useApiTaskService] Axios-Instanz wurde nicht gefunden. " +
                "Hast du in app.js `provide('api', api)` gesetzt?"
        );
    }
    const loading = ref(false);
    const error = ref(null);
    const doneCount = ref(0);

    const addNewTask = async (submitUrl, newTask) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await api.post(submitUrl, newTask);
            if (response.data.success) {
                return true;
            }
            return false;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Adding new task failed.";
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
                err.response?.data?.message ||
                "Fetching done tasks count failed.";
            return false;
        } finally {
            loading.value = false;
        }
    };

    const deleteTask = async (taskId) => {
        loading.value = true;
        error.value = null;
        try {
            await api.post("api/task/delete", { taskId });
            return true;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Deleting task failed.";
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

    return {
        loading,
        error,
        doneCount,
        addNewTask,
        getDoneTasksCount,
        deleteTask,
        closeTask,
    };
}
