<script setup lang="ts">
import { useLogoutStore } from '@/api/auth/logout';
import Button from '@/components/shared/Button.vue';

const { logout } = useLogoutStore();
</script>

<template>
    <Button variant="primary" @click="logout">Выйти из аккаунта</Button>
</template>
