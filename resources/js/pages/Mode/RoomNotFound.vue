<script setup lang="ts">
import Logotype from '@/components/shared/Logotype.vue';


</script>

<template>
    <div class="fixed inset-0 bg-black flex flex-col gap-y-2 items-center justify-center">
        <Logotype variant="error" class="text-white text-9xl"></Logotype>
        <span class="text-neutral-800 font-stretch-extra-expanded">Такой комнаты нет</span>
    </div>
</template>
