<script setup lang="ts">
import { useUserStore } from '@/api/user/user';
import { storeToRefs } from 'pinia';
import Icon from '../shared/Icon.vue';
import Button from '../shared/Button.vue';
import UserAvatar from './UserAvatar.vue';
import { useSettingsPopupStore } from '@/handles/settings-popup';

const { user } = storeToRefs(useUserStore());
const { settingsPopupShown } = storeToRefs(useSettingsPopupStore());
</script>

<template>
    <div class="flex items-center justify-center gap-x-2 px-3 py-2 rounded-xl bg-white/20">
        <UserAvatar></UserAvatar>
        <span class="text-white">{{ user.name }}</span>
        <Button variant="custom" @click="settingsPopupShown = true">
            <Icon class="text-white">more_vert</Icon>
        </Button>
    </div>
</template>
