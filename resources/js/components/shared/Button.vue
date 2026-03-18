<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

type Props = {
    variant: "primary" | "secondary" | "danger" | "custom";
    disabled?: boolean;
    href?: string;
};

const emit = defineEmits<{
    click: [],
}>();

const {
    variant,
    disabled = false,
    href,
} = defineProps<Props>();

</script>

<template>
    <component :is="href ? Link : 'button'"
        class="relative font-semibold px-2 py-1 rounded-lg cursor-pointer flex items-center justify-center" :class="{
            'bg-blue-500 text-white': variant === 'primary',
            'text-red-600': variant === 'danger',
            'opacity-20 cursor-not-allowed': disabled,
        }" v-bind="href ? { href } : {}" :disabled aria-role="button" @click='emit("click")'>
        <slot></slot>
    </component>
</template>
