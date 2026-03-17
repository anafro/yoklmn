<script setup lang="ts">
import AuthError from './AuthError.vue';

//
</script>

<template>
    <div class="flex flex-col items-start gap-y-2">
        <slot></slot>
        <AuthError></AuthError>
    </div>
</template>
