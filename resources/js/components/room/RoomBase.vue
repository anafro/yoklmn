<script setup lang="ts">
import { computed } from 'vue';
import Sunrays from '../vfx/Sunrays.vue';
import Exit from './Exit.vue';

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
        <div>
            <slot name="left"></slot>
        </div>
        <div class="flex flex-col items-stretch justify-between h-full">
            <div class="flex flex-col items-stretch justify-center gap-y-4">
                <slot name="top"></slot>
            </div>
            <div>
                <slot name="center"></slot>
            </div>
            <div class="flex flex-col items-stretch justify-center">
                <slot name="bottom"></slot>
            </div>
        </div>
        <div class="flex-1">
            <slot name="right"></slot>
        </div>
    </div>
    <!--Sunrays></Sunrays-->
    <Exit></Exit>
</template>
