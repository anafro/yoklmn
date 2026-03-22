import { useArray } from "@/lib/arrays";
import { get, set } from "@vueuse/core";
import { defineStore } from "pinia";
import { computed, ref } from "vue";

export const useWordInputStore = defineStore('word-input', () => {
    const caret = ref<number>(0);
    const {
        reference: letters,
        length,
        insert,
        remove,
        slice,
    } = useArray<string>([]);
    const word = computed(() => get(letters).join(''));

    function write(char: string): void {
        insert(caret, char);
        set(caret, get(caret) + 1);
    }

    function move(direction: 'left' | 'right', fast: boolean): void {
        switch (direction) {
            case "left":
                return set(caret, fast ? 0 : Math.max(0, get(caret) - 1));
            case "right":
                return set(caret, fast ? get(length) : Math.min(get(length), get(caret) + 1));
        }
    }

    function erase(fast: boolean): void {
        if (get(caret) === 0) {
            return;
        }

        if (fast) {
            slice(get(caret));
            set(caret, 0);
        } else {
            remove(get(caret) - 1);
            set(caret, Math.max(0, get(caret) - 1));
        }
    }

    function clear(): void {
        set(letters, []);
        set(caret, 0);
    }

    return {
        caret,
        letters,
        length,
        word,

        write,
        move,
        erase,
        clear,
    };
});
