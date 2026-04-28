<script setup lang="ts">
import Button from '@/components/shared/Button.vue';
import Logotype from '@/components/shared/Logotype.vue';
import { goto } from '@/navigation/goto';
</script>

<template>
    <div class="fixed inset-0 bg-black flex flex-col gap-y-2 items-center justify-center">
        <Logotype variant="error" class="text-white text-9xl"></Logotype>
        <span class="text-neutral-800 font-stretch-extra-expanded">Такой комнаты нет</span>
        <Button variant="custom" class="bg-white text-black mt-8" @click="goto('menu')">Ну и ладно</Button>
    </div>
</template>
