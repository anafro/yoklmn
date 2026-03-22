<script setup lang="ts">
import { useLogoutStore } from '@/api/auth/logout';
import { useUserStore } from '@/api/user/user';
import { useSettingsPopupStore } from '@/handles/settings-popup';
import { onKeyStroke, set } from '@vueuse/core';
import { storeToRefs } from 'pinia';
import UserAvatar from '../common/UserAvatar.vue';
import Button from '../shared/Button.vue';
import Logotype from '../shared/Logotype.vue';
import Icon from '../shared/Icon.vue';

const { user } = storeToRefs(useUserStore());
const { logout } = useLogoutStore();
const { settingsPopupShown } = storeToRefs(useSettingsPopupStore());

onKeyStroke('Escape', () => set(settingsPopupShown, false));
</script>

<template>
    <div v-if="settingsPopupShown"
        class="this backdrop-blur-md bg-black/50 absolute inset-0 flex items-center justify-center"
        @click.self="settingsPopupShown = false">
        <div class="grid grid-cols-4 h-5/6 w-4/5 rounded-md contain-paint shadow-2xl">
            <div class="px-5 py-3 col-span-1 bg-white">
                <div class="mb-2">
                    <div class="flex gap-x-2 items-center justify-center">
                        <UserAvatar :name="user.name"></UserAvatar>
                        <span>{{ user.name }}</span>
                        <Button variant="danger" @click="logout">
                            <Icon>logout</Icon>
                        </Button>
                    </div>
                </div>
                <hr class="border-blue-100">
            </div>
            <div class="px-5 py-3 col-span-3 bg-blue-50 flex items-center justify-center">
                <Logotype variant="icon" class="text-blue-100 text-[48rem]"></Logotype>
            </div>
        </div>
    </div>
</template>

<style lang="sass" scoped>
.this
    animation: _ 125ms ease-out

@keyframes _
    from
        opacity: 0
        scale: 1.03

    to
        scale: 1
</style>
