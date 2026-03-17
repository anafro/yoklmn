import { api } from "@/lib/requests";
import { get, set } from "@vueuse/core";
import { defineStore, storeToRefs } from "pinia";
import { MaybeRef, ref } from "vue";
import { useAuthErrorStore } from "./auth-error";

export const useLoginStore = defineStore('login', () => {
    const isLoginRequested = ref<boolean>(false);
    const loggedIn = ref<boolean | null>(null);
    const {
        errorMessage
    } = storeToRefs(useAuthErrorStore());

    async function requestLogin(_name: MaybeRef<string>, _password: MaybeRef<string>): Promise<void> {
        set(isLoginRequested, true);
        const name = get(_name);
        const password = get(_password);
        const response = await api('post', '/api/v1/auth/login', { name, password });

        if (response.successful) {
            set(loggedIn, true);
        } else {
            set(errorMessage, response.message);
        }

        set(isLoginRequested, false);
    }

    return {
        requestLogin,
        isLoginRequested,
        loggedIn,
    };
});
