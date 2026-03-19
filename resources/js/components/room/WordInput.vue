<script setup lang="ts">
import { useWordInputStore } from '@/handles/word-input';
import { guessWordInputAction } from '@/lib/keyboard';
import { onKeyStroke } from '@vueuse/core';
import { storeToRefs } from 'pinia';

const {
    length,
    letters,
    caret,
} = storeToRefs(useWordInputStore());

const {
    write,
    move,
    erase,
} = useWordInputStore();

onKeyStroke((event: KeyboardEvent) => {
    const action = guessWordInputAction(event);

    switch (action.type) {
        case 'char':
            return write(action.char);
        case 'erase':
            return erase(action.fast);
        case 'move':
            return move(action.direction, action.fast);
        case 'submit':
            return console.log("undefined: submit");
        case 'unknown':
            return console.log("undefined: unknown");
    }
}, {
    dedupe: false,
});
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
