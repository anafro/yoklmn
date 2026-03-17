import { computed, MaybeRef } from "vue";
import { useUserExists } from "./user-exists";
import { unset } from "@/lib/unset";
import { get } from "@vueuse/core";

export type AuthAction = 'login' | 'signup';

export function useAuthAction() {
    const {
        requestUserExists,
        isUserExistsRequested,
        userExists,
    } = useUserExists();

    async function requestAuthAction(_name: MaybeRef<string>): Promise<void> {
        return await requestUserExists(_name);
    }

    const authAction = computed<AuthAction | null>(() => {
        return unset(userExists)
            ? null
            : get(userExists)
                ? 'login'
                : 'signup'
    });

    return {
        requestAuthAction,
        isAuthActionRequested: isUserExistsRequested,
        authAction,
    }
}
