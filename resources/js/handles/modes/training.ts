import { useCheckWordStore } from "@/api/word/check-word";
import { useRandomTokenStore } from "@/api/word/random-token";
import { useSfxStore } from "@/lib/sfx";
import { get, set } from "@vueuse/core";
import { defineStore, storeToRefs } from "pinia";
import { ref, watch } from "vue";
import { useTimerStore } from "../timer";
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
    } = useCheckWordStore();

    const {
        play,
    } = useSfxStore();

    const {
        setTimer,
    } = useTimerStore();

    const {
        secondsLeft,
    } = storeToRefs(useTimerStore());

    const unwatchSecondsLeft = ref<() => void>((): void => { });

    async function next(): Promise<void> {
        clear();
        setTimer(10000);
        await requestRandomToken();
    }

    async function submit(): Promise<void> {
        if (randomToken.value === null) {
            return;
        }

        await requestCheckWord(word, randomToken.value);

        if (get(isWordCorrect)) {
            play('ok');
            await next();
        } else {
            play('bad');
        }
    }

    async function start(): Promise<void> {
        set(unwatchSecondsLeft, watch(secondsLeft, async (): Promise<void> => {
            if (get(secondsLeft) === 0) {
                await next();
                return play('hit');
            }

            if (get(secondsLeft) <= 3) {
                return play('tick');
            }
        }));

        await requestRandomToken();
        setTimer(10000);
    }

    function stop(): void {
        get(unwatchSecondsLeft)?.();
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
        stop,
    };
});
