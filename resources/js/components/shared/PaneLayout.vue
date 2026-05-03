<script setup lang="ts">
import { computed } from 'vue';
import Exit from '../room/Exit.vue';
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
    <div :style class="fixed inset-0 bg-cover flex flex-row items-stretch justify-center px-12 py-6 h-full gap-4">
        <slot name="left"></slot>
        <div class="flex flex-col items-stretch justify-between h-full gap-4 flex-1">
            <slot name="top"></slot>
            <slot name="center"></slot>
            <slot name="bottom"></slot>
        </div>
        <slot name="right"></slot>
    </div>
    <Exit></Exit>
</template>
