<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

type Props = {
    variant: "primary" | "secondary";
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
        class="font-black outline-none px-2 py-1 rounded-md cursor-pointer flex items-center justify-center" :class="{
            'bg-blue-500 text-white': variant === 'primary',
            'opacity-20': disabled,
        }" v-bind="href ? { href } : {}" :disabled aria-role="button" @click='emit("click")'>
        <slot></slot>
    </component>
</template>
