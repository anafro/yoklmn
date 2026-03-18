<script setup lang="ts">
import { computed } from 'vue';
import Button from '../shared/Button.vue';
import Icon from '../shared/Icon.vue';
import { storeToRefs } from 'pinia';
import { useMenuModeStore } from '@/handles/menu-mode';
import { get, set } from '@vueuse/core';

type Props = {
    name: string;
    description: string;
};

const {
    name,
    description,
} = defineProps<Props>();

const {
    menuModeShown,
    menuModeName,
    menuModeDescription,
} = storeToRefs(useMenuModeStore());

const label = computed<string>(() => name.slice(0, 4));

function onMouseOver(): void {
    set(menuModeName, get(name));
    set(menuModeDescription, get(description));
    set(menuModeShown, true);
}

function onMouseLeave(): void {
    set(menuModeShown, false);
}

</script>

<template>
    <Button @mouseover="onMouseOver" @mouseleave="onMouseLeave" variant="custom" class="
              group/button relative border-white border-2 shadow-2xl outline-transparent outline-16 transition-all ease-out font-stretch-extra-expanded
              group-hover:opacity-20 hover:opacity-100! hover:outline-white/20 hover:shadow-white hover:scale-110 even:hover:rotate-3 odd:hover:-rotate-3
              active:outline-white active:outline-2 active:scale-90 active:saturate-200
    ">
        {{ label }}
        <Icon class="
            absolute -bottom-4/5 m-auto text-white scale-200 opacity-0 group-hover/button:opacity-100 animate-float-y
        ">
            arrow_drop_up</Icon>
    </Button>
</template>
