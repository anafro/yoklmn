<script setup lang="ts">
import UserAvatar from '../common/UserAvatar.vue';

type Props = {
    name: string;
};

const {
    name
} = defineProps<Props>();
</script>

<template>
    <div class="flex gap-y-2 flex-col items-center justify-center">
        <UserAvatar class="scale-200" :name></UserAvatar>
        <span class="text-white font-stretch-expanded">{{ name }}</span>
    </div>
</template>
