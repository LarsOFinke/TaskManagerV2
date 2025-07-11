import axios from "axios";

const testEnvironment = {
    api: axios.create({
        // Point at the Symfony host
        baseURL: "http://127.0.0.1:8000/",
        withCredentials: true,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    }),
};

export default testEnvironment;
