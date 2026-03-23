<script setup lang="ts">
import { useExit } from '@/handles/exit';
import Kbd from '../shared/Kbd.vue';

const {
    active,
    pressesLeft,
    key,
} = useExit();
</script>

<template>
    <Transition>
        <div v-if="active"
            class="fixed inset-0 bg-white/20 backdrop-blur-xl flex items-center justify-center text-white font-stretch-extra-expanded text-2xl">
            Нажми <Kbd class="mx-2">{{ key }}</Kbd> ещё {{ pressesLeft }} раз, чтобы выйти
        </div>
    </Transition>
</template>

<style lang="scss">
.v-enter-active,
.v-leave-active {
    transition: opacity 75ms ease-out;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
