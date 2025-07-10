<template>
  <div class="flex flex-row bg-gray-700 text-gray-100 p-2 border-solid border-2 rounded-xl border-gray-900">
    <div class="w-full self-center">
      <!-- <h1 class="text-xl font-semibold ml-2">{{ pageHeaders[route.name] }}</h1> -->
    </div>

    <nav class="flex border-solid border-2 rounded-xl border-gray-900">
      <!-- Desktop-Navbar -->
      <div class="hidden text-center sm:flex sm:items-center">
        <!-- VISIBLE WHEN NOT AUTHENTICATED -->
        <a :href="loginUrl"
          class="m-2 whitespace-nowrap font-medium dark:text-yellow-300 hover:text-yellow-500 hover:underline cursor-pointer"
          :class="{ hidden: isAuthenticated }">Login</a>
        <a :href="registerUrl"
          class="m-2 whitespace-nowrap font-medium dark:text-yellow-300 hover:text-yellow-500 hover:underline cursor-pointer"
          :class="{ hidden: isAuthenticated }">Register</a>

        <!-- VISIBLE WHEN AUTHENTICATED -->
        <a @click.prevent="goToTasksList"
          class="m-2 whitespace-nowrap font-medium dark:text-yellow-300 hover:text-yellow-500 hover:underline cursor-pointer"
          :class="{ hidden: !isAuthenticated }">My Tasks</a>
        <a @click.prevent="goToTasksForm"
          class="m-2 whitespace-nowrap font-medium dark:text-yellow-300 hover:text-yellow-500 hover:underline cursor-pointer"
          :class="{ hidden: !isAuthenticated }">New Task</a>

        <a :href="logoutUrl"
          class="m-2 whitespace-nowrap font-medium dark:text-yellow-300 hover:text-yellow-500 hover:underline cursor-pointer"
          :class="{ hidden: !isAuthenticated }">Logout</a>
      </div>

      <!-- Mobile-Navbar -->
      <div class="sm:hidden relative">
        <!-- Hamburger-Icon -->
        <div @click="toggleMobileMenu" class="text-gray-100 text-4xl px-1">
          &#9776;
        </div>
        <!-- Nav-Links -->
        <div :class="showMobileMenu ? '' : 'hidden'"
          class="flex flex-col absolute -left-5.5 top-10 bg-gray-700 z-50 border-solid border-2 rounded-xl border-gray-900">
          <!-- VISIBLE WHEN NOT AUTHENTICATED -->
          <a :href="loginUrl"
            class="m-2 whitespace-nowrap font-medium dark:text-yellow-300 hover:text-yellow-500 hover:underline cursor-pointer"
            :class="{ hidden: isAuthenticated }">Login</a>
          <a :href="registerUrl"
            class="m-2 whitespace-nowrap font-medium dark:text-yellow-300 hover:text-yellow-500 hover:underline cursor-pointer"
            :class="{ hidden: isAuthenticated }">Register</a>

          <!-- VISIBLE WHEN AUTHENTICATED -->
          <a @click.prevent="goToTasksList"
            class="m-2 whitespace-nowrap font-medium dark:text-yellow-300 hover:text-yellow-500 hover:underline cursor-pointer"
            :class="{ hidden: !isAuthenticated }">My Tasks</a>
          <a @click.prevent="goToTasksForm"
            class="m-2 whitespace-nowrap font-medium dark:text-yellow-300 hover:text-yellow-500 hover:underline cursor-pointer"
            :class="{ hidden: !isAuthenticated }">New Task</a>
          <a :href="logoutUrl"
            class="m-2 font-medium dark:text-yellow-300 hover:text-yellow-500 hover:underline cursor-pointer"
            :class="{ hidden: !isAuthenticated }">Logout</a>
        </div>
      </div>
    </nav>
  </div>
</template>

<script setup>
const props = defineProps({
  loginUrl: { type: String, required: true },
  registerUrl: { type: String, required: true },
  logoutUrl: { type: String, required: true },
  isAuthenticated: { type: Boolean, required: true },
  // tasksListUrl: { type: String, required: true },
  // tasksFormUrl: { type: String, required: true },
});
const showMobileMenu = false;

const goToTasksList = () => {
  window.location.href = props.tasksListUrl;
}
const goToTasksForm = () => {
  window.location.href = props.tasksFormUrl;
}
</script>
