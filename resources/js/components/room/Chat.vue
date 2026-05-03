<script setup lang="ts">
    import Pane from "@/components/shared/Pane.vue";
    import PaneTitle from "../shared/PaneTitle.vue";
    import Input from "../shared/Input.vue";
    import { useRoom } from "@/api/room/room";
    import { useChatStore } from "@/handles/chat";
    import Message from "./Message.vue";
    import { get, set } from "@vueuse/core";
    import { ref } from "vue";

    const message = ref("");
    const room = useRoom();
    const chat = useChatStore();

    function sendMessage() {
        room.sendMessage(get(message));
        set(message, "");
    }
</script>

<template>
    <Pane class="flex-col gap-4">
        <PaneTitle>Чат</PaneTitle>
        <div class="flex-1 flex flex-col justify-end text-xs overflow-y-scroll">
            <Message v-for="{ type, text, name } in chat.messages" :type :text :name></Message>
        </div>
        <Input placeholder="Сообщение..." v-model="message" class="inline-block" @keydown.enter="sendMessage"></Input>
    </Pane>
</template>
