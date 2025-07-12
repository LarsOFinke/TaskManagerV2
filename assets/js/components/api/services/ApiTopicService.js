import { ref } from "vue";
import environment from "@/environments/environment";

const api = environment.api;
const loading = ref(false);
const error = ref(null);
const topicList = ref([]);

const addNewTopic = async (newTopic) => {
    loading.value = true;
    error.value = null;
    try {
        await api.post("topic/create", newTopic);
        return true;
    } catch (err) {
        error.value = err.response?.data?.message || "Adding new topic failed.";
        return false;
    } finally {
        loading.value = false;
    }
};

const getAllTopics = async () => {
    loading.value = true;
    error.value = null;
    try {
        const response = await api.get("api/topic/get-all");
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

export function useApiTopicService() {
    return {
        loading,
        error,
        topicList,
        addNewTopic,
        getAllTopics,
    };
}
