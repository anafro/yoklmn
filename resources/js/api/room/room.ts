import { useChatStore } from "@/handles/chat";
import { defineBiosphereChannel } from "@/lib/biosphere";
import { AppPageProps } from "@/types";
import { usePage } from "@inertiajs/vue3";
import { useUserStore } from "../user/user";
import { storeToRefs } from "pinia";
import { get, set } from "@vueuse/core";
import { usePlayerListStore } from "@/handles/player-list";

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
    });

    channel.on(/quit/, message => {
        const player = message.player as string;
        addServerMessage(`${player} вышел из комнаты`);
        removePlayer(player);
    });

    channel.on(/chat/, message => {
        const player = message.player as string;
        const text = message.text as string;
        addPlayerMessage(player, text);
    });

    channel.on(/close/, _ => addServerMessage("Соединение с сервером разорвалось"));
    channel.on(/.*/, m => {
        console.log(m, /chat/.test(m.event), m.event);
    });
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
