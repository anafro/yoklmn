import { api } from "@/lib/requests";
import { gotoRoom } from "@/navigation/room";

export async function createRoomAndJoin(): Promise<void> {
    const response = await api('post', '/api/v1/create-room');

    if (response.successful) {
        const code: string = response.data.code as string;
        gotoRoom(code);
    } else {
        console.error(response.message);
    }
}
