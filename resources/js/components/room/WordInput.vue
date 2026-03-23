<script setup lang="ts">
import { useWordInputStore } from '@/handles/word-input';
import { guessWordInputAction, WordInputAction } from '@/lib/keyboard';
import { useSfxStore } from '@/lib/sfx';
import { get, onKeyStroke } from '@vueuse/core';
import { storeToRefs } from 'pinia';
import { useTemplateRef } from 'vue';
import { gsap } from 'gsap';

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

const self = useTemplateRef<HTMLDivElement>('self');

const {
    length,
    letters,
    caret,
} = storeToRefs(useWordInputStore());

function onKey(event: KeyboardEvent): void {
    const action: WordInputAction = guessWordInputAction(event);

    if (action.type !== 'unknown') {
        gsap.killTweensOf(get(self));
        gsap.set(get(self), { x: 0, y: 0 });
    }

    switch (action.type) {
        case 'write':
            play('write');
            gsap.from(get(self), {
                y: 3,
                ease: 'elastic.out',
            });
            return emit('write', action.char);
        case 'erase':
            play('erase.' + (action.fast ? 'fast' : 'slow'));
            gsap.from(get(self), {
                x: -(action.fast ? 5 : 1) * 10,
                ease: 'elastic.out',
            });
            return emit('erase', action.fast);
        case 'move':
            play('move.' + (action.fast ? 'fast' : 'slow'));
            gsap.from(get(self), {
                x: ({ left: -1, right: 1 }[action.direction]) * (action.fast ? 5 : 1) * 10,
                ease: 'circ.out',
            });
            return emit('move', action.direction, action.fast);
        case 'submit':
            if (get(length) === 0) {
                return;
            }
            return emit('submit');
        case 'unknown':
            return emit('unknown', action.key);
    }
}

onKeyStroke(onKey, { dedupe: true });
</script>

<template>
    <div ref='self' class="flex gap-x-1 h-16 items-center justify-center">
        <template v-for="(_, i) in length + 1">
            <div :data-i="i" v-if="i === caret" class="w-0 h-full outline-white outline-1"></div>
            <span :data-i="i" v-if="i !== length"
                class="_letter text-white font-stretch-extra-condensed font-black text-center text-5xl uppercase leading-16">{{
                    letters[i]
                }}</span>
        </template>
    </div>
</template>

<style lang="sass" scoped>
._letter
    animation: _ ease-out 75ms

@keyframes _
    from
        opacity: 0
        transform: translateY(+0.125rem)
    to
        opacity: 1
        transform: translateY(0)
</style>
