import { useCheckWordStore } from "@/api/word/check-word";
import { useRandomTokenStore } from "@/api/word/random-token";
import { get } from "@vueuse/core";
import { defineStore, storeToRefs } from "pinia";
import { useWordInputStore } from "../word-input";

export const useTrainingStore = defineStore('training', () => {
    const {
        caret,
        word,
        letters,
    } = storeToRefs(useWordInputStore());

    const {
        clear,
    } = useWordInputStore();

    const {
        randomTokenRequested,
        randomToken,
    } = storeToRefs(useRandomTokenStore());

    const {
        requestRandomToken,
    } = useRandomTokenStore();

    const {
        checkWordRequested,
        isWordCorrect,
    } = storeToRefs(useCheckWordStore());

    const {
        requestCheckWord,
    } = useCheckWordStore()

    async function submit(): Promise<void> {
        if (randomToken.value === null) {
            return;
        }

        await requestCheckWord(word, randomToken.value);

        if (get(isWordCorrect)) {
            clear();
            await requestRandomToken();
        }
    }

    async function start(): Promise<void> {
        await requestRandomToken();
    }

    return {
        caret,
        letters,
        randomToken,
        randomTokenRequested,
        checkWordRequested,
        isWordCorrect,
        submit,
        start,
    };
});
