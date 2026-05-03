import { useArray } from "@/lib/arrays";
import { get, onKeyStroke, set } from "@vueuse/core";
import { defineStore, storeToRefs } from "pinia";
import { computed, MaybeRef, ref, useTemplateRef } from "vue";
import WordInput from "@/components/room/WordInput.vue";
import { guessWordInputAction, WordInputAction } from "@/lib/keyboard";
import { ComponentExposed } from "vue-component-type-helpers";
import { useRoom } from "@/api/room/room";
import { useUserStore } from "@/api/user/user";
import { useTimerStore } from "./timer";

export abstract class WordInputDriver {
    protected readonly _wordInput: ReturnType<typeof useTemplateRef<ComponentExposed<typeof WordInput>>>;

    public constructor(_wordInput: ReturnType<typeof useTemplateRef<ComponentExposed<typeof WordInput>>>) {
        this._wordInput = _wordInput;
    }

    protected wordInput(): ComponentExposed<typeof WordInput> {
        return get(this._wordInput)!;
    }
    public abstract attach(): void;
    public abstract detach(): void;
}

export class KeyboardWordInputDriver extends WordInputDriver {
    private stopOnKeyStroke?: ReturnType<typeof onKeyStroke>;
    private readonly _room: ReturnType<typeof useRoom>;

    public constructor(_wordInput: ReturnType<typeof useTemplateRef<ComponentExposed<typeof WordInput>>>, _room: MaybeRef<ReturnType<typeof useRoom>>) {
        super(_wordInput);
        this._room = get(_room);
    }

    protected room() {
        return this._room;
    }

    public attach(): void {
        const channel = this.room().channel();
        const timer = useTimerStore();

        channel.on(/check/, message => {
            const type = message.type as 'ok' | 'used' | 'bad';
            const turn = message.turn as string;
            const token = message.token as string;
            const time = message.time as number;

            console.log(this.room());


            this.wordInput().check(type);

            if (type === 'ok') {
                this.room().turn = turn;
                this.room().token = token;
                this.room().time = time;

                timer.setTimer(time);
            }
        });

        this.stopOnKeyStroke = onKeyStroke((event: KeyboardEvent) => {
            const { word } = storeToRefs(useWordInputStore());
            const action: WordInputAction = guessWordInputAction(event);

            switch (action.type) {
                case 'write':
                    this.wordInput().write(action.char);
                    this.room().write(action.char);

                    break;
                case 'erase':
                    this.wordInput().erase(action.fast);
                    this.room().erase(action.fast);
                    break;
                case 'move':
                    this.wordInput().move(action.direction, action.fast);
                    this.room().move(action.direction, action.fast);
                    break;
                case 'submit':
                    this.wordInput().submit();
                    this.room().submit(get(word));
                    break;
                case 'unknown':
                    this.wordInput().unknown(action.key);
                    break;
            }
        }, { dedupe: true });
    }

    public detach(): void {
        const channel = this.room().channel();
        channel.off(/check/);
        this.stopOnKeyStroke!();
    }
}

export class ChannelWordInputDriver extends WordInputDriver {
    private readonly _room: ReturnType<typeof useRoom>;

    public constructor(_wordInput: ReturnType<typeof useTemplateRef<ComponentExposed<typeof WordInput>>>, _room: MaybeRef<ReturnType<typeof useRoom>>) {
        super(_wordInput);
        this._room = get(_room);
    }

    public attach(): void {
        const auth = useUserStore();
        const channel = this.room().channel();
        const timer = useTimerStore();

        channel.on(/write/, message => {
            if (get(this.room().turn) === auth.user.name) {
                return;
            }

            const char = message.char as string;
            this.wordInput().write(char);
        });

        channel.on(/move/, message => {
            if (get(this.room().turn) === auth.user.name) {
                return;
            }

            const direction = message.direction as 'left' | 'right';
            const fast = message.fast as boolean;
            this.wordInput().move(direction, fast);
        });

        channel.on(/erase/, message => {
            if (get(this.room().turn) === auth.user.name) {
                return;
            }

            const fast = message.fast as boolean;
            this.wordInput().erase(fast);
        });

        channel.on(/check/, message => {
            const type = message.type as 'ok' | 'used' | 'bad';
            const turn = message.turn as string;
            const token = message.token as string;
            const time = message.time as number

            this.wordInput().check(type);

            if (type === 'ok') {
                this.room().turn = turn;
                this.room().token = token;
                this.room().time = time;

                timer.setTimer(time);
            }
        });
    }

    public detach(): void {
        const channel = this.room().channel();
        channel.off(/write/);
        channel.off(/move/);
        channel.off(/erase/);
        channel.off(/check/);
    }

    protected room() {
        return this._room;
    }
}

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
