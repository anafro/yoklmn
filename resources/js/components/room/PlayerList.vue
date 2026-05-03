<script setup lang="ts">
import { useRoom } from '@/api/room/room';
import UserAvatar from '../common/UserAvatar.vue';
import Pane from '../shared/Pane.vue';
import PaneTitle from '../shared/PaneTitle.vue';
import { usePlayerListStore } from '@/handles/player-list';
import Icon from '../shared/Icon.vue';
import { onMounted } from 'vue';

const room = useRoom();
const playerList = usePlayerListStore();
onMounted(() => {
    console.log(room);
});
</script>

<template>
    <Pane class="flex-col">
        <PaneTitle class="mb-4">Игроки</PaneTitle>
        <div v-for="player in playerList.players" class="flex gap-x-2 mb-1 items-center">
            <UserAvatar :name="player"></UserAvatar>
            <span class="text-white font-stretch-expanded">{{ player }}</span>
            <div v-if="room.host.value === player" class="flex items-center gap-x-1 text-amber-200">
                <Icon variant="fill">crown</Icon>
            </div>
        </div>
    </Pane>
</template>
