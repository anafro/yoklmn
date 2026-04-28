<script setup lang="ts">
    export type Props = {
        text: string,
        type: 'server' | 'player',
        name?: string,
    };

    const {
        text,
        type,
        name = undefined,
    } = defineProps<Props>();
</script>

<template>
<div class="flex text-white">
    <span v-if="type === 'player'" class="flex gap-2">
        <span class="font-bold font-stretch-expanded">{{ name }}</span>
        <span class="text-wrap text-ellipsis">{{ text }}</span>
    </span>
    <div v-if="type === 'server'" class="opacity-50 font-stretch-extra-condensed">{{ text }}</div>
</div>
</template>
