import { useChatStore } from "@/handles/chat";
import { defineBiosphereChannel } from "@/lib/biosphere";
import { AppPageProps } from "@/types";
import { usePage } from "@inertiajs/vue3";
import { useUserStore } from "../user/user";
import { storeToRefs } from "pinia";
import { set } from "@vueuse/core";
import { usePlayerListStore } from "@/handles/player-list";
import { useSfxStore } from "@/lib/sfx";
import { ref } from "vue";
import { useTimerStore } from "@/handles/timer";

export const defineRoom = (code: string) => defineBiosphereChannel({ name: `Room #${code}`, debug: true }, {
    type: ref<'vanilla'>(),
    status: ref<'abandoned' | 'waiting' | 'running'>('waiting'),
    host: ref<string>(),
    turn: ref<string>(),
    time: ref<number>(),
    token: ref<string>(),
}, (channel, refs) => {
    const chat = useChatStore();
    const playerList = usePlayerListStore();
    const timer = useTimerStore();
    const sfx = useSfxStore();
    const auth = useUserStore();

    channel.on(/join/, message => {
        const player = message.player as string;
        const players = message.players as string[];
        const host = message.host as string;

        if (player === auth.user.name) {
            set(storeToRefs(playerList).players, players);
        } else {
            playerList.addPlayer(player);
        }

        set(refs.host, host);
        console.log(host);

        chat.addServerMessage(`${player} зашёл в комнату`);
        sfx.play('join');
    });

    channel.on(/quit/, message => {
        const player = message.player as string;
        const host = message.host as string;
        set(refs.host, host);

        chat.addServerMessage(`${player} вышел из комнаты`);
        playerList.removePlayer(player);
        sfx.play('quit');
    });

    channel.on(/chat/, message => {
        const player = message.player as string | undefined;
        const text = message.text as string;

        if (player === undefined) {
            chat.addServerMessage(text);
        } else {
            chat.addPlayerMessage(player, text);
        }
    });

    channel.on(/start/, message => {
        const turn = message.turn as string;
        const token = message.token as string;
        const time = message.time as number;

        set(refs.status, 'running');
        set(refs.turn, turn);
        set(refs.token, token);
        set(refs.time, time);

        timer.setTimer(time);
    });

    channel.on(/timeout/, message => {
        const turn = message.turn as string;
        const token = message.token as string;
        const time = message.time as number;

        set(refs.turn, turn);
        set(refs.token, token);
        set(refs.time, time);

        timer.setTimer(time);
    });

    channel.on(/close/, () => {
        chat.addServerMessage("[!] Соединение с сервером разорвалось")
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
    const room = defineRoom(code)();

    const sendMessage = (text: string) => room.channel().send('chat', { text });
    const start = () => room.channel().send('start', {});
    const write = (char: string) => room.channel().send('write', { char });
    const move = (direction: 'left' | 'right', fast: boolean) => room.channel().send('move', { direction, fast });
    const erase = (fast: boolean) => room.channel().send('erase', { fast });
    const submit = (word: string) => room.channel().send('submit', { word });

    return {
        sendMessage,
        start,
        write,
        move,
        erase,
        submit,
        channel: room.channel,
        type: storeToRefs(room).type,
        status: storeToRefs(room).status,
        host: storeToRefs(room).host,
        turn: storeToRefs(room).turn,
        time: storeToRefs(room).time,
        token: storeToRefs(room).token,
    };
}
