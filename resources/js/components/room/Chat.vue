<script setup lang="ts">
    import Pane from "@/components/shared/Pane.vue";
    import PaneTitle from "../shared/PaneTitle.vue";
    import Input from "../shared/Input.vue";
    import { useRoom } from "@/api/room/room";
    import { useChatStore } from "@/handles/chat";
    import { storeToRefs } from "pinia";
    import Message from "./Message.vue";
    import { useUserStore } from "@/api/user/user";
    import { get, set } from "@vueuse/core";

    const {} = useRoom();
    const {
        messages,
        input,
    } = storeToRefs(useChatStore());

    const {
        sendMessage,
    } = useChatStore();

    const {
        user,
    } = storeToRefs(useUserStore());

    function send() {
        sendMessage(get(input));
        set(input, "");
    }
</script>

<template>
    <Pane class="flex-col gap-4">
        <PaneTitle>Чат</PaneTitle>
        <div class="flex-1 flex flex-col justify-end text-xs overflow-y-scroll">
            <Message v-for="{ type, text, name } in messages" :type :text :name></Message>
        </div>
        <Input placeholder="Сообщение..." v-model="input" class="inline-block" @keydown.enter="send"></Input>
    </Pane>
</template>
