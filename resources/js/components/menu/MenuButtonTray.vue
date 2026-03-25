<script setup lang="ts">
import ArcadeMenuButton from './buttons/ArcadeMenuButton.vue';
import ArenaMenuButton from './buttons/ArenaMenuButton.vue';
import BattleMenuButton from './buttons/BattleMenuButton.vue';
import RoomForFriendsMenuButton from './buttons/RoomForFriendsMenuButton.vue';
import TrainingMenuButton from './buttons/TrainingMenuButton.vue';
</script>

<template>
    <div class="group flex gap-x-4">
        <ArenaMenuButton></ArenaMenuButton>
        <BattleMenuButton></BattleMenuButton>
        <RoomForFriendsMenuButton></RoomForFriendsMenuButton>
        <TrainingMenuButton></TrainingMenuButton>
        <ArcadeMenuButton></ArcadeMenuButton>
    </div>
</template>
