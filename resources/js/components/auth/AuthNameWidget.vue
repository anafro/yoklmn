<script setup lang="ts">
import AuthWidgetBase from './AuthWidgetBase.vue';
import Icon from '../shared/Icon.vue';
import Button from '../shared/Button.vue';
import Input from '../shared/Input.vue';
import { useAuthStore } from '@/api/auth/auth';
import { storeToRefs } from 'pinia';

const {
    name,
} = storeToRefs(useAuthStore());
const {
    requestAuthAction,
} = useAuthStore();
</script>

<template>
    <AuthWidgetBase>
        <div class="flex gap-x-2">
            <Input v-model="name" placeholder="Никнейм..." @keyup.enter="requestAuthAction"></Input>
            <Button class="aspect-square" variant="primary" @click="requestAuthAction">
                <Icon class="scale-150">arrow_forward</Icon>
            </Button>
        </div>
    </AuthWidgetBase>
</template>
