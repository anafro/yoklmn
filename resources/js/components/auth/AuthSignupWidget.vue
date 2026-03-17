<script setup lang="ts">
import AuthWidgetBase from './AuthWidgetBase.vue';
import Icon from '../shared/Icon.vue';
import Button from '../shared/Button.vue';
import Input from '../shared/Input.vue';
import { useAuthStore } from '@/api/auth/auth';
import { storeToRefs } from 'pinia';

const {
    name,
    email,
    password,
    passwordAgain,
} = storeToRefs(useAuthStore());

const {
    requestSignup,
} = useAuthStore();
</script>

<template>
    <AuthWidgetBase>
        <span class="text-white">Добро пожаловать, {{ name }}!</span>
        <div class="flex flex-col items-stretch gap-y-2">
            <Input type="email" class="min-w-0" v-model="email" placeholder="Почта..."
                @keyup.enter="requestSignup"></Input>
            <Input type="password" class="min-w-0" v-model="password" placeholder="Придумай пароль..."
                @keyup.enter="requestSignup"></Input>
            <div class="inline-flex gap-x-2">
                <Input type="password" class="min-w-0" v-model="passwordAgain" placeholder="И повтори его..."
                    @keyup.enter="requestSignup"></Input>
                <Button class="block aspect-square" variant="primary" @click="requestSignup">
                    <Icon class="scale-150">arrow_forward</Icon>
                </Button>
            </div>
        </div>
    </AuthWidgetBase>
</template>
