<script setup lang="ts">
import { useWordInputStore } from '@/handles/word-input';
import { guessWordInputAction, WordInputAction } from '@/lib/keyboard';
import { useSfxStore } from '@/lib/sfx';
import { onKeyStroke } from '@vueuse/core';
import { storeToRefs } from 'pinia';

const emit = defineEmits<{
    write: [char: string],
    erase: [fast: boolean],
    move: [direction: 'left' | 'right', fast: boolean],
    submit: [],
    unknown: [key: string],
}>();

const {
    play,
} = useSfxStore();

const {
    length,
    letters,
    caret,
} = storeToRefs(useWordInputStore());

function onKey(event: KeyboardEvent): void {
    const action: WordInputAction = guessWordInputAction(event);

    switch (action.type) {
        case 'write':
            play('write');
            return emit('write', action.char);
        case 'erase':
            play('erase.' + (action.fast ? 'fast' : 'slow'));
            return emit('erase', action.fast);
        case 'move':
            play('move.' + (action.fast ? 'fast' : 'slow'));
            return emit('move', action.direction, action.fast);
        case 'submit':
            return emit('submit');
        case 'unknown':
            return emit('unknown', action.key);
    }
}

onKeyStroke(onKey, { dedupe: true });
</script>

<template>
    <div class="flex gap-x-1 h-16">
        <template v-for="(_, i) in length + 1">
            <div :data-i="i" v-if="i === caret" class="w-0 h-full outline-white outline-1"></div>
            <span :data-i="i" v-if="i !== length"
                class="text-white font-stretch-extra-condensed font-black text-5xl uppercase leading-16">{{
                    letters[i]
                }}</span>
        </template>
    </div>
</template>
