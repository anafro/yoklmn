<script setup lang="ts">
import { useUserStore } from '@/api/user/user';
import { storeToRefs } from 'pinia';
import Icon from '../shared/Icon.vue';
import UserAvatar from './UserAvatar.vue';
import { useSettingsPopupStore } from '@/handles/settings-popup';

const { user } = storeToRefs(useUserStore());
const { settingsPopupShown } = storeToRefs(useSettingsPopupStore());
</script>

<template>
    <div class="flex items-center justify-center gap-x-2 px-3 py-2 rounded-xl bg-white/20">
        <UserAvatar :name="user.name"></UserAvatar>
        <span class="text-white">{{ user.name }}</span>
        <Icon class="text-white cursor-pointer" @click="settingsPopupShown = true">more_vert</Icon>
    </div>
</template>
