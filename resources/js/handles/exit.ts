import { goto } from "@/navigation/goto";
import { get, onKeyStroke, set, useNow } from "@vueuse/core";
import { computed, onUnmounted, ref, watch } from "vue";

type UseExitOptions = Partial<{
    presses: number,
    key: string,
    time: number,
}>;


export function useExit(options: UseExitOptions = {}) {
    const {
        presses = 5,
        key = 'Escape',
        time = 1000,
    } = options;

    const pressesLeft = ref<number>(presses);
    const pressedAt = ref<number>(-1);
    const now = useNow();
    const active = computed<boolean>(() => get(pressedAt) + time > get(now).getTime());

    onKeyStroke(key, (): void => {
        const wasActive = get(active);
        set(pressedAt, get(now).getTime());

        if (wasActive) {
            set(pressesLeft, Math.max(0, get(pressesLeft) - 1));
        }
    }, { dedupe: true });

    watch(active, (): void => {
        if (!get(active)) {
            set(pressesLeft, presses);
        }
    });

    watch(pressesLeft, (): void => {
        if (get(pressesLeft) === 0) {
            goto("menu");
        }
    });

    return {
        pressesLeft,
        active,
        key,
    }
}
