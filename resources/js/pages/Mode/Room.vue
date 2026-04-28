<script setup lang="ts">
import RoomBase from '@/components/room/RoomBase.vue';
import Chat from '@/components/room/Chat.vue';
import PlayerList from '@/components/room/PlayerList.vue';
import Code from '@/components/room/Code.vue';
import { useRoom } from '@/api/room/room';

type Props = {
    code: {
        cyrillic: string;
        latin: string;
    };
}

const {
    code,
} = defineProps<Props>();

const room = useRoom();
room.$onAction(({onError}) => {
    onError(console.error);
});
</script>

<template>
    <RoomBase background="friends">
        <template #top>
            <Code :code="code.cyrillic"></Code>
        </template>
        <template #left>
             <PlayerList :players="['anafro', 'darkmun', 'limuran', 'ploshka']"></PlayerList>
        </template>
        <template #right>
             <Chat></Chat>
        </template>
    </RoomBase>
</template>
