<script setup lang="ts">
import { useWordInputStore } from '@/handles/word-input';
import { useSfxStore } from '@/lib/sfx';
import { get } from '@vueuse/core';
import { useTemplateRef } from 'vue';
import { gsap } from 'gsap';

const {
    play,
} = useSfxStore();

const self = useTemplateRef<HTMLDivElement>('self');

const wordInput = useWordInputStore();
const resettingAnimation = <F extends (...args: any) => any>(f: F): F => {
    return ((...args: any) => {
        gsap.killTweensOf(get(self));
        gsap.set(get(self), { x: 0, y: 0 });
        return f(...args);
    }) as F;
};

const write = resettingAnimation((char: string) => {
    play('write');
    gsap.from(get(self), { y: 3, ease: 'elastic.out' });
    emit('write', char);
    wordInput.write(char);
});

const erase = resettingAnimation((fast: boolean) => {
    play('erase.' + (fast ? 'fast' : 'slow'));
    gsap.from(get(self), { x: -(fast ? 5 : 1) * 10, ease: 'elastic.out' });
    emit('erase', fast);
    wordInput.erase(fast);
});

const move = resettingAnimation((direction: 'left' | 'right', fast: boolean) => {
    play('move.' + (fast ? 'fast' : 'slow'));
    gsap.from(get(self), { x: ({ left: -1, right: 1 }[direction]) * (fast ? 5 : 1) * 10, ease: 'circ.out' });
    emit('move', direction, fast);
    wordInput.move(direction, fast);
});

const submit = resettingAnimation(() => {
    if (get(length) === 0) return;
    emit('submit');
});

const check = resettingAnimation((type: 'ok' | 'bad' | 'used') => {
    play(type);
    emit('check', type);
    if (type === 'ok') wordInput.clear();
});

function unknown(key: string) {
    emit('unknown', key);
}

defineExpose({
    write,
    move,
    erase,
    submit,
    check,
    unknown,
});

const emit = defineEmits<{
    write: [char: string],
    erase: [fast: boolean],
    move: [direction: 'left' | 'right', fast: boolean],
    submit: [],
    unknown: [key: string],
    check: [type: 'ok' | 'bad' | 'used'],
}>();

</script>

<template>
    <div ref='self' class="flex gap-x-1 h-16 items-center justify-center">
        <template v-for="(_, i) in wordInput.length + 1">
            <div :data-i="i" v-if="i === wordInput.caret" class="w-0 h-full outline-white outline-1"></div>
            <span :data-i="i" v-if="i !== wordInput.length"
                class="_letter text-white font-stretch-extra-condensed font-black text-center text-5xl uppercase leading-16">{{
                    wordInput.letters[i]
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
