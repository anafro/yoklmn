import { MaybeAsync } from "@/types";
import { get } from "@vueuse/core";
import { MaybeRef, onMounted, onUnmounted } from "vue";

export function useLifecycleBind(_whenMounted: MaybeRef<MaybeAsync<() => void>>, _whenUnmounted: MaybeRef<MaybeAsync<() => void>>): void {
    const whenMounted = get(_whenMounted);
    const whenUnmounted = get(_whenUnmounted);

    onMounted(whenMounted);
    onUnmounted(whenUnmounted);
}
