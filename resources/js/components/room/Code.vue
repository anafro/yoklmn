<script setup lang="ts">
import UserDisplay from '../common/UserDisplay.vue';
import Icon from '../shared/Icon.vue';
import Pane from '../shared/Pane.vue';
import PaneTitle from '../shared/PaneTitle.vue';

    type Props = {
        code: string;
    };

    const {
        code,
    } = defineProps<Props>();
</script>


<template>
    <Pane class="items-start justify-between gap-12 flex-1">
        <div>
            <PaneTitle>Код комнаты</PaneTitle>
            <div class="flex gap-2 items-center">
                <span class="text-white flex-1 text-4xl font-extrabold font-stretch-extra-expanded text-shadow-md text-shadow-black/10">#{{ code }}</span>
                <Icon class="text-white text-shadow-md text-shadow-black/10">content_copy</Icon>
            </div>
        </div>
        <UserDisplay class="text-shadow-md text-shadow-black/10"></UserDisplay>
    </Pane>
</template>
