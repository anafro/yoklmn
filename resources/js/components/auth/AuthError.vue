<script setup lang="ts">
import { useAuthErrorStore } from '@/api/auth/auth-error';
import { storeToRefs } from 'pinia';

const {
    errorMessage,
    hasErrorMessage,
} = storeToRefs(useAuthErrorStore());
</script>

<template>
    <span v-if="hasErrorMessage" class="text-sm text-red-400 absolute top-0 left-0 right-0 text-center mt-4">{{
        errorMessage
        }}</span>
</template>
