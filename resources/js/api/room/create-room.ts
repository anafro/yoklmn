import { api } from "@/lib/requests";
import { gotoRoom } from "@/navigation/room";

export async function createRoomAndJoin(): Promise<void> {
    const response = await api('post', '/api/v1/create-room');

    if (response.successful) {
        const code: unknown = response.data.code;
        if (typeof code !== 'string') {
            console.log(code);
            throw new Error(`Room code is not a string`);
        }

        gotoRoom(code);
    } else {
        console.error(response.message);
    }
}
