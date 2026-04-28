<script setup lang="ts">
    import { storeToRefs } from 'pinia';
import UserAvatar from '../common/UserAvatar.vue';
    import Pane from '../shared/Pane.vue';
    import PaneTitle from '../shared/PaneTitle.vue';
import { usePlayerListStore } from '@/handles/player-list';

    const {
        players,
    } = storeToRefs(usePlayerListStore());
</script>

<template>
    <Pane class="flex-col w-xs">
        <PaneTitle class="mb-4">Игроки</PaneTitle>
        <div v-for="player in players" class="flex gap-x-2 mb-1 items-center">
            <UserAvatar :name="player"></UserAvatar>
            <span class="text-white font-stretch-expanded">{{ player }}</span>
        </div>
    </Pane>
</template>
