<script setup lang="ts">
import { ref } from 'vue';

const t = ref<string>((Math.random() * 5 + 10) + 's');
const x0 = ref<string>(Math.random() * 100 + '%');
const y0 = ref<string>(Math.random() * 100 + '%');
const s0 = ref<number>(Math.random() * 1.5 + 2);
const x1 = ref<string>(Math.random() * 100 + '%');
const y1 = ref<string>(Math.random() * 100 + '%');
const s1 = ref<number>(Math.random() * 0.5 + 0.75);
</script>

<template>
    <div
        class="this absolute aspect-square size-[300dvh] rounded-full bg-radial from-white from-0% to-transparent to-50% opacity-20">
    </div>
</template>


<style lang="sass" scoped>
.this
    --x0: v-bind(x0)
    --y0: v-bind(y0)
    --s0: v-bind(s0)
    --x1: v-bind(x1)
    --y1: v-bind(y1)
    --s1: v-bind(s1)
    animation: _ v-bind(t) infinite alternate ease-in-out
    transform: translateX(-50%) translateY(-50%)

@keyframes _
    from
        left: var(--x0)
        top: var(--y0)
        scale: var(--s0)
    to
        left: var(--x1)
        top: var(--y1)
        scale: var(--s1)
</style>
