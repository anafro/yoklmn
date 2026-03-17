import { router } from "@inertiajs/vue3";


const routes = {
    'menu': '/',
    'auth.login': '/войти',
} as const;

export type Route = keyof typeof routes;

export function goto(route: Route): void {
    router.visit(routes[route]);
}
