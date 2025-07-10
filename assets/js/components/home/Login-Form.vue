<template>
    <div class="w-full max-w-sm bg-gray-700 shadow-md p-6 mx-auto border-solid border-2 rounded-xl border-gray-900">
        <h2 class="text-2xl font-semibold text-gray-100 mb-4 text-center">Login</h2>

        <!-- Message-Box -->
        <message-box :msg="msg" :errorPhrase="errorPhrase"></message-box>

        <!-- Login-Form -->
        <div>
            <!-- Username -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-100">Username
                    <input name="_username" v-model.trim="username" type="text" placeholder="Username" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </label>
            </div>
            <!-- Password -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-100">Password
                    <input name="_password" v-model.trim="password" :type="showPassword ? 'text' : 'password'"
                        placeholder="Password" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </label>
            </div>
            <!-- Show-Password -->
            <div class="mb-14">
                <label class="text-gray-100 float-right">
                    Show password
                    <input v-model="showPassword" type="checkbox">
                </label>
            </div>
            <!-- Utility-Buttons -->
            <button type="submit" @click="submitForm"
                class="w-full bg-indigo-700 text-gray-100 py-2 px-4 rounded-md hover:bg-indigo-600 transition">
                Login
            </button>
        </div>
    </div>
</template>


<script setup>
import MessageBox from '@/components/shared/Message-Box.vue';
import { ref } from 'vue';

const props = defineProps({
    lastUsername: String,
    error: String
});
const msg = ref(props.error || '')
const errorPhrase = ref(msg.value ? 'Something went wrong!' : '')
const username = ref(props.lastUsername || '');
const password = ref('');
const showPassword = ref(false)

const submitForm = () => {
    errorPhrase.value = ''
    msg.value = 'Checking credentials...'
}
</script>
