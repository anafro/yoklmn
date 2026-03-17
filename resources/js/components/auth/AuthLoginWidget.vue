<script setup lang="ts">
import AuthWidgetBase from './AuthWidgetBase.vue';
import Icon from '../shared/Icon.vue';
import Button from '../shared/Button.vue';
import Input from '../shared/Input.vue';
import { useAuthStore } from '@/api/auth/auth';
import { storeToRefs } from 'pinia';

const {
    name,
    password,
} = storeToRefs(useAuthStore());

const {
    requestLogin,
} = useAuthStore();
</script>

<template>
    <AuthWidgetBase>
        <span class="text-white">С возвращением, {{ name }})</span>
        <div class="flex gap-x-2">
            <Input type="password" v-model="password" placeholder="Пароль..." @keyup.enter="requestLogin"></Input>
            <Button class="aspect-square" variant="primary" @click="requestLogin">
                <Icon class="scale-150">arrow_forward</Icon>
            </Button>
        </div>
    </AuthWidgetBase>
</template>
