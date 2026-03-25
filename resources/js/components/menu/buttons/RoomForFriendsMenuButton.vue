<script setup lang="ts">
import { createRoomAndJoin } from '@/api/room/create-room';
import MenuButton from '../MenuButton.vue';
</script>

<template>
    <MenuButton @click="createRoomAndJoin" name="Комната для друзей"
        description="Собирай компанию ёшек и играйте все вместе!" class="bg-yellow-500 text-yellow-950"></MenuButton>
</template>
