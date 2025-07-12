// src/composables/useApiTopicService.js
import { inject, ref } from "vue";

export function useApiTopicService() {
    const api = inject("api");
    if (!api) {
        throw new Error(
            "[useApiTopicService] Axios-Instanz wurde nicht gefunden. " +
                "Hast du in app.js `provide('api', api)` gesetzt?"
        );
    }
    const loading = ref(false);
    const error = ref(null);
    const topicList = ref([]);

    const addNewTopic = async (newTopic) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await api.post("api/topic/create", newTopic);
            if (response.data.success) {
                return true;
            }
            return false;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Adding new topic failed.";
            return false;
        } finally {
            loading.value = false;
        }
    };

    const getAllTopics = async () => {
        loading.value = true;
        error.value = null;
        try {
            const response = await api.get("/api/topic/get-all");
            if (response.data.success) {
                topicList.value = response.data.topicList;
                return true;
            }
            return false;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Fetching topics failed.";
            return false;
        } finally {
            loading.value = false;
        }
    };

    return {
        loading,
        error,
        topicList,
        addNewTopic,
        getAllTopics,
    };
}
