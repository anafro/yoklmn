<script setup lang="ts">
import { useUserStore } from '@/api/user/user';
import Player from '@/components/room/Player.vue';
import PlayerTray from '@/components/room/PlayerTray.vue';
import PaneLayout from '@/components/shared/PaneLayout.vue';
import Timer from '@/components/room/Timer.vue';
import WordInput from '@/components/room/WordInput.vue';
import { useTrainingStore } from '@/handles/modes/training';
import { useWordInputStore } from '@/handles/word-input';
import { storeToRefs } from 'pinia';
import { onMounted, onUnmounted } from 'vue';

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
});

onUnmounted((): void => {
    stop();
})
</script>

<template>
    <PaneLayout background="training">
        <template #top>
            <Timer></Timer>
            <span class="text-white text-center font-stretch-extra-expanded text-3xl font-thin">{{ randomToken }}</span>
        </template>
        <template #center>
            <WordInput @write="write" @move="move" @erase="erase" @submit="submit"></WordInput>
        </template>
        <template #bottom>
            <PlayerTray>
                <Player :name="user.name"></Player>
            </PlayerTray>
        </template>
    </PaneLayout>
</template>
