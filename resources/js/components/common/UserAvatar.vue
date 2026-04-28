<script setup lang="ts">
import { get } from '@vueuse/core';
import { computed } from 'vue';

type Props = {
    name: string;
};

const {
    name,
} = defineProps<Props>();

const symbol = computed(() => get(name).charAt(0))
</script>

<template>
    <div class="contain-paint size-5 aspect-square rounded-full flex items-center justify-center bg-white/20">
        <span class="leading-2 text-white/90 font-stretch-extra-expanded font-black -rotate-30 scale-150 select-none">
            {{ symbol }}
        </span>
    </div>
</template>
