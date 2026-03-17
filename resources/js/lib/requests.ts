import { usePage } from "@inertiajs/vue3";
import { get } from "@vueuse/core";
import { computed, ComputedRef, MaybeRef } from "vue";

export type HTTPMethod = 'get' | 'post' | 'put' | 'patch' | 'delete' | 'head' | 'options' | 'trace' | 'connect';

export type APIResponse = {
    successful: true,
    data: Record<string, unknown>,
} | {
    successful: false,
    message: string,
}

export function useCsrfToken(): ComputedRef<string> {
    const page = usePage();
    return computed<string>(() => String(page.props.csrf));
}

export async function api(method: HTTPMethod, _uri: MaybeRef<string>, parameters: Record<string, string> = {}): Promise<APIResponse> {
    const uri = get(_uri);
    const isGet = method === 'get';
    const searchParams = new URLSearchParams(parameters);
    const parametrizedUri = uri + (Object.keys(parameters).length === 0 ? '' : `?${searchParams}`);
    const csrf = useCsrfToken();

    const response = await fetch(isGet ? parametrizedUri : uri, {
        method,
        ...(isGet
            ? {}
            : { body: JSON.stringify(parameters) }
        ),
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': get(csrf),
        },
    });

    if (!response.ok) {
        return {
            successful: false,
            ...await response.json()
        }
    }

    return {
        successful: true,
        data: await response.json(),
    }
}
