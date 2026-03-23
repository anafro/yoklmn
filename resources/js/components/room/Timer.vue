<script setup lang="ts">
import { useTimerStore } from '@/handles/timer';
import { get } from '@vueuse/core';
import { storeToRefs } from 'pinia';
import { computed } from 'vue';

const {
    timeLeft,
    percentsLeft,
} = storeToRefs(useTimerStore());

const style = computed(() => ({
    width: `${get(percentsLeft)}%`,
}));
</script>

<template>
    <div class="flex items-center justify-center gap-x-4">
        <span class="text-white text-center font-stretch-extra-expanded w-12"> {{ (timeLeft / 1000).toFixed(1) }}
        </span>
        <div class="h-4 flex-1 outline-white outline-1">
            <div class="bg-white h-full" :style></div>
        </div>
    </div>
</template>
