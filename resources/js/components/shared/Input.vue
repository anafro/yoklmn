<script setup lang="ts">
type Props = {
    placeholder?: string;
    type?: 'text' | 'password' | 'email';
}

const {
    placeholder = '',
    type = 'text',
} = defineProps<Props>();
const value = defineModel<string>({ required: true });
</script>

<template>
    <input class="bg-white/10 px-3 py-2 rounded-lg placeholder-white/10 text-white outline-none" :type
        v-model.trim="value" :placeholder>
</template>
