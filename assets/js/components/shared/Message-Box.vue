<template>
    <div v-if="msg !== ''"
        class="w-fit max-w-sm bg-gray-500 text-gray-100 font-semibold rounded-lg shadow-md p-1 mx-auto mb-4"
        :class="errorPhrase === 'Something went wrong!' ? 'text-red-600' : ''">
        <p>{{ msg }}</p>
    </div>
</template>

<script setup>
defineProps({
    msg: String,
    errorPhrase: String,
})
</script>