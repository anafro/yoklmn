<script setup lang="ts">
import { useUserStore } from '@/api/user/user';
import Player from '@/components/room/Player.vue';
import PlayerTray from '@/components/room/PlayerTray.vue';
import RoomBase from '@/components/room/RoomBase.vue';
import WordInput from '@/components/room/WordInput.vue';
import { useTrainingStore } from '@/handles/modes/training';
import { useWordInputStore } from '@/handles/word-input';
import { storeToRefs } from 'pinia';
import { onMounted } from 'vue';

const {
    randomToken,
} = storeToRefs(useTrainingStore());

const {
    start,
    submit,
} = useTrainingStore();

const {
    write,
    move,
    erase,
} = useWordInputStore();

const {
    user,
} = storeToRefs(useUserStore());

onMounted(async (): Promise<void> => {
    await start();
})
</script>

<template>
    <RoomBase background="training">
        <span class="text-white font-stretch-extra-expanded">{{ randomToken }}</span>
        <WordInput @write="write" @move="move" @erase="erase" @submit="submit"></WordInput>
        <PlayerTray>
            <Player :name="user.name"></Player>
        </PlayerTray>
    </RoomBase>
</template>
