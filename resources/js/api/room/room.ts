import { useChatStore } from "@/handles/chat";
import { defineBiosphereChannel } from "@/lib/biosphere";
import { AppPageProps } from "@/types";
import { usePage } from "@inertiajs/vue3";
import { useUserStore } from "../user/user";
import { storeToRefs } from "pinia";
import { get, set } from "@vueuse/core";
import { usePlayerListStore } from "@/handles/player-list";
import { useSfxStore } from "@/lib/sfx";
import { ref } from "vue";
import { later } from "@/lib/later";

export const defineRoom = (code: string) => defineBiosphereChannel({ name: `Room #${code}` }, channel => {
    const {
        addServerMessage,
        addPlayerMessage,
    } = useChatStore();

    const {
        players,
    } = storeToRefs(usePlayerListStore());

    const {
        addPlayer,
        removePlayer,
    } = usePlayerListStore();

    const sfx = useSfxStore();
    const { user: _me } = storeToRefs(useUserStore());

    channel.on(/join/, message => {
        const player = message.player as string;
        const playersAfter = message.players as string[];
        const me = get(_me);
        addServerMessage(`${player} зашёл в комнату`);
        if (player === me.name) {
            set(players, playersAfter);
        } else {
            addPlayer(player);
        }
        sfx.play('join');
    });

    channel.on(/quit/, message => {
        const player = message.player as string;
        addServerMessage(`${player} вышел из комнаты`);
        removePlayer(player);
        sfx.play('quit');
    });

    channel.on(/chat/, message => {
        const player = message.player as string;
        const text = message.text as string;
        addPlayerMessage(player, text);
    });

    channel.on(/close/, _ => addServerMessage("Соединение с сервером разорвалось"));
});

export function useRoom() {
    const page = usePage<{
        code: {
            cyrillic: string;
            latin: string;
        }
    } & AppPageProps>();
    const code = page.props.code.latin;
    return defineRoom(code)();
}
