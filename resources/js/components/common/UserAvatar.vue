<script setup lang="ts">
import { useUserStore } from '@/api/user/user';
import { get } from '@vueuse/core';
import { storeToRefs } from 'pinia';
import { computed } from 'vue';

const { user } = storeToRefs(useUserStore());
const symbol = computed(() => get(user).name.charAt(0))
</script>

<template>
    <div class="contain-paint aspect-square size-4 rounded-full flex items-center justify-center bg-blue-600">
        <span class="leading-2 text-blue-100 font-stretch-extra-expanded font-black -rotate-30 scale-150 select-none">
            {{ symbol }}
        </span>
    </div>
</template>
