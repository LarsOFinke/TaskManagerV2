<template>
    <div class="w-full max-w-sm bg-gray-700 shadow-md p-6 mx-auto border-solid border-2 rounded-xl border-gray-900">
        <h2 class="text-2xl font-semibold text-gray-100 mb-4 text-center">Register</h2>

        <!-- Message-Box -->
        <message-box :msg="msg" :errorPhrase="errorPhrase"></message-box>

        <!-- Registration-Form -->
        <div class="flex flex-col">
            <!-- Username -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-100">Username
                    <input name="registration_form[username]" v-model.trim="username" type="text" placeholder="Username"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </label>
            </div>
            <!-- Password -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-100">Password
                    <input name="registration_form[plainPassword][first]" v-model.trim="password"
                        :type="showPassword ? 'text' : 'password'" placeholder="Password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </label>
            </div>
            <!-- Password-Confirmation -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-100">Password confirm
                    <input name="registration_form[plainPassword][second]" v-model.trim="passwordConfirmation"
                        :type="showPassword ? 'text' : 'password'" placeholder="Password confirm"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </label>
            </div>
            <!-- Show-Password -->
            <div class="mb-4">
                <label class="text-gray-100 float-right">
                    Show password
                    <input v-model="showPassword" type="checkbox">

                </label>
            </div>
            <!-- Utility-Buttons -->
            <div class="flex justify-between">
                <button type="reset"
                    class="w-fit bg-indigo-600 text-gray-100 py-2 px-10 rounded-md hover:bg-indigo-800 transition">Reset</button>
                <button type="submit"
                    class="w-fit bg-indigo-600 text-gray-100 py-2 px-8 rounded-md hover:bg-indigo-800 transition">Register</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import MessageBox from '@/components/shared/Message-Box.vue';
import { ref } from 'vue';

const msg = ref('')
const errorPhrase = ref('')
const username = ref('');
const password = ref('');
const passwordConfirmation = ('');
const showPassword = ref(false)
</script>