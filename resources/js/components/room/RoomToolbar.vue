<script setup lang="ts">
import { useRoom } from '@/api/room/room';
import Button from '../shared/Button.vue';
import Icon from '../shared/Icon.vue';
import Pane from '../shared/Pane.vue';
import { storeToRefs } from 'pinia';
import { useUserStore } from '@/api/user/user';
import { usePlayerListStore } from '@/handles/player-list';

const room = useRoom();
const playerList = usePlayerListStore();
const { user: me } = storeToRefs(useUserStore());
</script>

<template>
    <Pane v-if="room.host.value === me.name && playerList.players.length >= 2" class="text-white flex items-center justify-center">
        <Button variant="custom" class="bg-white/20 text-white flex gap-x-2" @click="room.start()">
            <Icon>play_arrow</Icon>
            Начать игру
        </Button>
    </Pane>
</template>
