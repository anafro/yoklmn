<script setup lang="ts">
import Button from '../shared/Button.vue';
import Icon from '../shared/Icon.vue';
import Pane from '../shared/Pane.vue';
</script>

<template>
    <Pane class="text-white flex items-center justify-center">
        <Button variant="custom" class="bg-white/20 text-white flex gap-x-2">
            <Icon>play_arrow</Icon>
            Начать игру
        </Button>
    </Pane>
</template>
