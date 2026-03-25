import { api } from "@/lib/requests";
import { get, set } from "@vueuse/core";
import { MaybeRef, ref } from "vue";

export function useRoomExists() {
    const roomExistsRequested = ref<boolean>(false);
    const roomExists = ref<boolean | null>(null);

    async function requestRoomExists(_code: MaybeRef<string>): Promise<void> {
        const code = get(_code);
        set(roomExistsRequested, true);

        const response = await api('post', '/api/v1/room-exists', { code });

        if (response.successful) {
            set(roomExists, response.data.exists);
        } else {
            console.error(response.message);
        }

        set(roomExistsRequested, false);
    }

    return {
        requestRoomExists,
        roomExistsRequested,
        roomExists,
    }
}
