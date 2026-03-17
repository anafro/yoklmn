import { api } from "@/lib/requests";
import { get, set } from "@vueuse/core";
import { MaybeRef, ref } from "vue";

export function useUserExists() {
    const isUserExistsRequested = ref<boolean>(false);
    const userExists = ref<boolean | null>(null);

    async function requestUserExists(_name: MaybeRef<string>): Promise<void> {
        set(isUserExistsRequested, true);
        const name = get(_name);
        const response = await api('get', '/api/v1/users/exists', { name });

        if (response.successful) {
            const { exists } = response.data;
            set(userExists, exists);
        } else {
            console.error(response.message);
        }
        set(isUserExistsRequested, false);
    }

    return {
        isUserExistsRequested,
        userExists,
        requestUserExists,
    }
}
