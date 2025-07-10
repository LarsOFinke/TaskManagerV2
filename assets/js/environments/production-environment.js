import axios from "axios";

const prodEnvironment = {
  api: axios.create({
    baseURL: "http://192.168.2.36/api",
  }),
};

export default prodEnvironment;
