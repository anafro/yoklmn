import { get, set, useNow } from "@vueuse/core";
import { defineStore } from "pinia";
import { computed, MaybeRef, ref } from "vue";

export const useTimerStore = defineStore('timer', () => {
    const now = useNow();
    const startedAt = ref<number>();
    const time = ref<number>();
    const endsAt = computed<number>(() => get(startedAt)! + get(time)!);
    const timeLeft = computed<number>(() => Math.max(0, get(endsAt) - get(now).getTime()));
    const percentsLeft = computed<number>(() => 100 * get(timeLeft) / get(time)!);
    const secondsLeft = computed<number>(() => Math.ceil(get(timeLeft) / 1000));

    function setTimer(_time: MaybeRef<number>): void {
        set(startedAt, get(now).getTime());
        set(time, get(_time));
    }

    return {
        setTimer,
        timeLeft,
        percentsLeft,
        secondsLeft,
    };
});
