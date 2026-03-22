import { api } from "@/lib/requests";
import { set } from "@vueuse/core";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useRandomTokenStore = defineStore('random-token', () => {
    const randomTokenRequested = ref<boolean>(false);
    const randomToken = ref<string | null>(null);

    async function requestRandomToken(): Promise<void> {
        set(randomTokenRequested, true);
        const response = await api('post', '/api/v1/random-token/');
        if (response.successful) {
            set(randomToken, response.data.token);
        } else {
            console.error(response.message);
        }

        set(randomTokenRequested, false);
    }

    return {
        requestRandomToken,
        randomTokenRequested,
        randomToken,
    };
});
