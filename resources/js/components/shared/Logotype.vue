<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { get } from "@vueuse/core";
import { computed } from "vue";


type Props = {
    variant?: 'title' | 'icon';
};

const {
    variant = 'title'
} = defineProps<Props>();
const page = usePage();
const text = computed(() => get(variant) === 'title' ? page.props.name : page.props.name.charAt(0));

</script>

<template>
    <span class="text-white font-[1000] font-stretch-200% -skew-x-12">
        {{ text }}
    </span>
</template>
