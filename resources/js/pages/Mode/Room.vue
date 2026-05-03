<script setup lang="ts">
import PaneLayout from '@/components/shared/PaneLayout.vue';
import Chat from '@/components/room/Chat.vue';
import PlayerList from '@/components/room/PlayerList.vue';
import Code from '@/components/room/Code.vue';
import { useRoom } from '@/api/room/room';
import RoomToolbar from '@/components/room/RoomToolbar.vue';
import { usePlayerListStore } from '@/handles/player-list';
import { ChannelWordInputDriver, KeyboardWordInputDriver, WordInputDriver } from '@/handles/word-input';
import { get, set, watchOnce } from '@vueuse/core';
import WordInput from '@/components/room/WordInput.vue';
import PlayerTray from '@/components/room/PlayerTray.vue';
import Player from '@/components/room/Player.vue';
import { ComponentExposed } from "vue-component-type-helpers";
import { ref, useTemplateRef, watch } from 'vue';
import { useUserStore } from '@/api/user/user';
import Timer from '@/components/room/Timer.vue';

type Props = {
    code: {
        cyrillic: string;
        latin: string;
    };
}

const {
    code,
} = defineProps<Props>();

const driver = ref<WordInputDriver>();
const room = useRoom();
const playerList = usePlayerListStore();
const wordInput = useTemplateRef<ComponentExposed<typeof WordInput>>('wordInput');

watch(room.turn, () => {
    if (get(room.status) !== 'running') {
        return;
    }
    const auth = useUserStore();
    get(driver)?.detach();
    set(driver, get(room.turn) === auth.user.name ? new KeyboardWordInputDriver(wordInput, room) : new ChannelWordInputDriver(wordInput, room));
    get(driver)?.attach();
});
</script>

<template>
    <PaneLayout v-if="room.status.value === 'waiting'" background="friends">
        <template #top>
            <Code :code="code.cyrillic"></Code>
        </template>
        <template #left>
             <PlayerList></PlayerList>
        </template>
        <template #right>
             <Chat></Chat>
        </template>
        <template #bottom>
            <RoomToolbar></RoomToolbar>
        </template>
        <template #center>
        </template>
    </PaneLayout>

    <PaneLayout v-if="room.status.value === 'running'" background="training">
        <template #top>
            <Timer></Timer>
            <span class="text-white text-center font-stretch-extra-expanded text-3xl font-thin">{{ room.token.value }}</span>
        </template>
        <template #center>
            <WordInput ref="wordInput"></WordInput>
        </template>
        <template #bottom>
            <PlayerTray>
                <Player v-for="player in playerList.players" :name="player"></Player>
            </PlayerTray>
        </template>
        <template #right>
             <Chat></Chat>
        </template>
    </PaneLayout>
</template>
