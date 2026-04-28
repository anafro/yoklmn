import { get, set } from "@vueuse/core";
import { computed, MaybeRef, ref } from "vue";

export function withInserted<TElement>(array: TElement[], index: number, element: TElement): TElement[] {
    return [
        ...array.slice(0, index),
        element,
        ...array.slice(index),
    ];
}

export function withRemoved<TElement>(array: TElement[], index: number): TElement[] {
    return [
        ...array.slice(0, index),
        ...array.slice(index + 1),
    ];
}

export function useArray<TElement>(array: TElement[] = []) {
    const reference = ref<TElement[]>(array);
    const length = computed<number>(() => get(reference).length);

    function insert(_index: MaybeRef<number>, _element: MaybeRef<TElement>): void {
        const array = get(reference) as TElement[];
        const index = get(_index);
        const element = get(_element);
        const newArray = withInserted(array, index, element);
        set(reference, newArray);
    }

    function remove(_index: MaybeRef<number>): void {
        const array = get(reference) as TElement[];
        const index = get(_index);
        const newArray = withRemoved(array, index);
        set(reference, newArray);
    }

    function slice(_start: MaybeRef<number> | undefined = undefined, _end: MaybeRef<number> | undefined = undefined): void {
        const array = get(reference) as TElement[];
        const start = get(_start);
        const end = get(_end);
        const newArray = array.slice(start, end);
        set(reference, newArray);
    }

    function push(_element: MaybeRef<TElement>): void {
        const array = get(reference) as TElement[];
        const element = get(_element);
        const newArray = [...array, element];
        set(reference, newArray);
    }

    function drop(_element: MaybeRef<TElement>): void {
        const array = get(reference) as TElement[];
        const element = get(_element);
        const newArray = array.filter(_ => _ !== element);
        set(reference, newArray);
    }

    function clear(): void {
        set(reference, []);
    }

    return {
        reference,
        length,
        insert,
        slice,
        remove,
        push,
        drop,
        clear,
    };
}
