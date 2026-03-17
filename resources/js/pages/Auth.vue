<script setup lang="ts">
import { useAuthStore } from '@/api/auth/auth';
import AuthLoginWidget from '@/components/auth/AuthLoginWidget.vue';
import AuthNameWidget from '@/components/auth/AuthNameWidget.vue';
import AuthSignupWidget from '@/components/auth/AuthSignupWidget.vue';
import Logotype from '@/components/shared/Logotype.vue';
import Version from '@/components/shared/Version.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { storeToRefs } from 'pinia';

const {
    authAction,
} = storeToRefs(useAuthStore());
</script>

<template>
    <AuthLayout>
        <div class="flex gap-x-18 items-center justify-center">
            <div class="flex flex-col items-end">
                <Logotype class="text-xl"></Logotype>
                <Version class="text-xs leading-3"></Version>
            </div>
        </div>

        <AuthNameWidget v-if="authAction === null"></AuthNameWidget>
        <AuthLoginWidget v-if="authAction === 'login'"></AuthLoginWidget>
        <AuthSignupWidget v-if="authAction === 'signup'"></AuthSignupWidget>
    </AuthLayout>
</template>
