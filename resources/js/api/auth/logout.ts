import { api } from "@/lib/requests";
import { goto } from "@/navigation/goto";
import { set } from "@vueuse/core";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useLogoutStore = defineStore('logout', () => {
    const isLogoutRequested = ref<boolean>(false);
    const loggedOut = ref<boolean | null>(null);

    async function logout(): Promise<void> {
        const response = await api('post', '/api/v1/auth/logout');

        if (response.successful) {
            set(loggedOut, true);
            goto('auth.login');
        }
    }

    return {
        logout,
        isLogoutRequested,
        loggedOut,
    };
});
