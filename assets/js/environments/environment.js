import prodEnvironment from "@/environments/production-environment";
import testEnvironment from "@/environments/testing-environment";

const productionMode = false;

const environment = {
  api: productionMode ? prodEnvironment.api : testEnvironment.api,
};

export default environment;
