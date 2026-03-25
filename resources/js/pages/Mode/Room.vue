<script setup lang="ts">
import { useRoomExists } from '@/api/room/room-exists';
import RoomBase from '@/components/room/RoomBase.vue';

type Props = {
    code: string;
}

const {
    code,
} = defineProps<Props>();

const {
    requestRoomExists,
    roomExistsRequested,
    roomExists,
} = useRoomExists();
</script>

<template>
    <RoomBase background="friends">
        <template #center>
            <span class="text-white text-6xl font-stretch-extra-expanded text-center">#{{ code }}</span>
        </template>
    </RoomBase>
</template>
