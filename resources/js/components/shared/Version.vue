<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const version = computed(() => page.props.version);
</script>

<template>
    <span class="text-white font-stretch-expanded font-semibold">
        {{ version }}
    </span>
</template>
