import { api } from "@/lib/requests";
import { get, set } from "@vueuse/core";
import { defineStore, storeToRefs } from "pinia";
import { MaybeRef, ref } from "vue";
import { useAuthErrorStore } from "./auth-error";

export const useSignupStore = defineStore('signup', () => {
    const isSignupRequested = ref<boolean>(false);
    const signedUp = ref<boolean | null>(null);
    const {
        errorMessage
    } = storeToRefs(useAuthErrorStore());

    async function requestSignup(_name: MaybeRef<string>, _email: MaybeRef<string>, _password: MaybeRef<string>): Promise<void> {
        set(isSignupRequested, true);
        const email = get(_email);
        const name = get(_name);
        const password = get(_password);
        const response = await api('post', '/api/v1/auth/signup', { name, email, password });

        if (response.successful) {
            set(signedUp, true);
        } else {
            set(errorMessage, response.message);
        }

        set(isSignupRequested, false);
    }

    return {
        requestSignup,
        isSignupRequested,
        signedUp,
    }
});
