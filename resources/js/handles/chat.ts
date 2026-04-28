import { useRoom } from "@/api/room/room";
import { Props } from "@/components/room/Message.vue";
import { useArray } from "@/lib/arrays";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useChatStore = defineStore('chat', () => {
    const {
        reference: messages,
        push: pushMessage,
    } = useArray<Props>();

    const input = ref<string>("");

    const addPlayerMessage = (name: string, text: string) => pushMessage({
        type: 'player',
        name,
        text,
    });

    const addServerMessage = (text: string) => pushMessage({
        type: 'server',
        text,
    });

    const room = useRoom();

    const sendMessage = (text: string) => room.channel!.send('chat', {
        text,
    });

    return {
        addPlayerMessage,
        addServerMessage,
        sendMessage,
        messages,
        input,
    };
});
