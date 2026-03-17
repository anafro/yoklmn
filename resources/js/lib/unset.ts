import { get } from "@vueuse/core";
import { MaybeRef } from "vue";

export function unset<TValue>(_value: MaybeRef<TValue | null>): boolean {
    const value = get(_value);
    return value === null;
}
