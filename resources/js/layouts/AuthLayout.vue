<script setup lang="ts">
import LayoutBase from '@/layouts/LayoutBase.vue';
</script>

<template>
    <LayoutBase>
        <div class="h-lvh bg-black flex items-center justify-center gap-x-8">
            <slot></slot>
        </div>
    </LayoutBase>
</template>
