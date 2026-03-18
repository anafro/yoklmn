<script setup lang="ts">
import UserDisplay from '@/components/common/UserDisplay.vue';
import MenuButtonTray from '@/components/menu/MenuButtonTray.vue';
import MenuMode from '@/components/menu/MenuMode.vue';
import SettingsPopup from '@/components/settings/SettingsPopup.vue';
import Logotype from '@/components/shared/Logotype.vue';
import Version from '@/components/shared/Version.vue';
import Sunrays from '@/components/vfx/Sunrays.vue';
import { get } from '@vueuse/core';
import { computed, ref } from 'vue';

const image = ref<string>('menu');
const style = computed(() => ({
    backgroundImage: `url(/images/${get(image)}-bg.jpg)`,
}));
</script>

<template>
    <div :style class="h-lvh bg-cover flex flex-col items-stretch justify-start px-12">
        <div class="flex items-center justify-between p-12">
            <Version></Version>
            <UserDisplay></UserDisplay>
        </div>
        <Logotype class="text-white text-[14rem] leading-60"></Logotype>
        <div class="flex flex-col justify-end self-end max-w-1/3 me-12">
            <MenuButtonTray class="self-end mb-12"></MenuButtonTray>
            <MenuMode class="self-start flex-1"></MenuMode>
        </div>
        <SettingsPopup></SettingsPopup>
        <Sunrays></Sunrays>
    </div>
</template>
