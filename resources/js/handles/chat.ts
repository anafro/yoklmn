import { useRoom } from "@/api/room/room";
import { Props } from "@/components/room/Message.vue";
import { useArray } from "@/lib/arrays";
import { useSfxStore } from "@/lib/sfx";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useChatStore = defineStore('chat', () => {
    const {
        reference: messages,
        push: pushMessage,
    } = useArray<Props>();

    const sfx = useSfxStore();

    const input = ref<string>("");

    const addPlayerMessage = (name: string, text: string) => {
        sfx.play('chat');
        pushMessage({
            type: 'player',
            name,
            text,
        });
    };

    const addServerMessage = (text: string) => {
        sfx.play('chat');
        pushMessage({
            type: 'server',
            text,
        });
    };

    return {
        addPlayerMessage,
        addServerMessage,
        messages,
        input,
    };
});
