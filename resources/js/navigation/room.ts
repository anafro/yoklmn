import { router } from "@inertiajs/vue3";

export function gotoRoom(code: string): void {
    router.visit(`/${code}`);
}
