<script setup lang="ts">
import { useMenuModeStore } from '@/handles/menu-mode';
import { storeToRefs } from 'pinia';
import Icon from '../shared/Icon.vue';

const {
    menuModeShown,
    menuModeName,
    menuModeDescription,
} = storeToRefs(useMenuModeStore());
</script>

<template>
    <div v-if="menuModeShown" class="this flex flex-col gap-y-2 w-full">
        <span class="text-white font-stretch-extra-expanded font-black">{{ menuModeName }}</span>
        <span class="text-white text-wrap">{{ menuModeDescription }}</span>
        <span class="text-white text-wrap opacity-50 text-xs">
            <Icon>left_click</Icon> Нажми на кнопку, чтобы начать играть
        </span>
    </div>
</template>


<style lang="sass" scoped>
.this
    animation: _ 125ms ease-out


@keyframes _
    from
        opacity: 0
        transform: translateX(4rch)
</style>
