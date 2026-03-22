<script setup lang="ts">
import { computed } from 'vue';
import WordInput from './WordInput.vue';

type Props = {
    background: string;
};

const {
    background,
} = defineProps<Props>();

const style = computed(() => ({
    backgroundImage: `url(/images/modes/${background}-bg.jpg)`,
}));
</script>

<template>
    <div :style class="fixed inset-0 bg-cover flex flex-col items-center justify-between py-12">
        <slot></slot>
    </div>
</template>
