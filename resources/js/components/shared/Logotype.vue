<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { get } from "@vueuse/core";
import { computed } from "vue";


type Props = {
    variant?: 'title' | 'icon' | 'error';
};

const {
    variant = 'title'
} = defineProps<Props>();
const page = usePage();
const text = computed(() => ({
    title: page.props.name,
    icon: page.props.name.charAt(0),
    error: page.props.name.charAt(0) + '...',
}[get(variant)]));

</script>

<template>
    <span class="font-[1000] font-stretch-200% -skew-x-12 inline-block whitespace-nowrap select-none">
        {{ text }}
    </span>
</template>
