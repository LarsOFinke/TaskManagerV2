import axios from "axios";

const testEnvironment = {
    api: axios.create({
        // Point at the Symfony host + the /api/tasks prefix
        baseURL: import.meta.env.VITE_API_URL
            ? `${import.meta.env.VITE_API_URL}/api`
            : "/api",
        withCredentials: true,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
            "Content-Type": "application/json",
        },
    }),
};

export default testEnvironment;
