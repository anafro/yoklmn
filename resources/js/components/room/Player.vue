<script setup lang="ts">
import { useRoom } from '@/api/room/room';
import UserAvatar from '../common/UserAvatar.vue';
import Icon from '../shared/Icon.vue';

type Props = {
    name: string;
};

const {
    name,
} = defineProps<Props>();

const room = useRoom();
</script>

<template>
    <div :class="{'opacity-20': name !== room.turn.value, 'scale-110 -translate-y-3': name === room.turn.value}" class="flex gap-y-3 flex-col items-center justify-center">
        <Icon v-if="name === room.turn.value" class="text-white text-5xl! animate-float-y">arrow_drop_down</Icon>
        <UserAvatar class="scale-200" :name></UserAvatar>
        <span class="text-white font-stretch-expanded">{{ name }}</span>
    </div>
</template>
