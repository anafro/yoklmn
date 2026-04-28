<script setup lang="ts">
import Sunray from './Sunray.vue';


</script>

<template>
    <div class="fixed inset-0 size-full pointer-events-none contain-paint">
        <div class="size-full relative">
            <Sunray v-for="_ in 2"></Sunray>
        </div>
    </div>
</template>
