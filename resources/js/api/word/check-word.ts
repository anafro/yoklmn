import { api } from "@/lib/requests";
import { get, set } from "@vueuse/core";
import { defineStore } from "pinia";
import { MaybeRef, ref } from "vue";

export const useCheckWordStore = defineStore('check-word', () => {
    const checkWordRequested = ref<boolean>(false);
    const isWordCorrect = ref<boolean | null>(null);

    async function requestCheckWord(_word: MaybeRef<string>, _token: MaybeRef<string>): Promise<void> {
        const word = get(_word);
        const token = get(_token);
        const response = await api('post', '/api/v1/check-word/', { word, token });
        if (response.successful) {
            set(isWordCorrect, response.data.correct);
        } else {
            console.error(response.message);
        }

        set(checkWordRequested, false);
    }

    return {
        requestCheckWord,
        checkWordRequested,
        isWordCorrect,
    };
});
