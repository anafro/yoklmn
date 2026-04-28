<script setup lang="ts">
import RoomBase from '@/components/room/RoomBase.vue';
import Chat from '@/components/room/Chat.vue';
import PlayerList from '@/components/room/PlayerList.vue';
import Code from '@/components/room/Code.vue';
import { useRoom } from '@/api/room/room';
import Pane from '@/components/shared/Pane.vue';
import RoomToolbar from '@/components/room/RoomToolbar.vue';

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
             <PlayerList></PlayerList>
        </template>
        <template #right>
             <Chat></Chat>
        </template>
        <template #bottom>
            <RoomToolbar>

            </RoomToolbar>
        </template>
        <template #center>
            <Pane class="flex">
            </Pane>
        </template>
    </RoomBase>
</template>
