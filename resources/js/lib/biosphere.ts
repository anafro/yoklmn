import { biosphere, BiosphereChannel } from "@anf/biosphere-client";
import { usePage } from "@inertiajs/vue3";
import { defineStore } from "pinia";
import { ref } from "vue";
import { get, set, tryOnMounted, tryOnUnmounted } from "@vueuse/core";


export function useBiosphere() {
    const page = usePage();

    return {
        biosphere: biosphere({
            csrf: page.props.csrf as string,
            url: undefined,
        }),
    };
}

export type UseBiosphereChannelSetupCallback<R> = (channel: BiosphereChannel, refs: R) => void;
export type UseBiosphereChannelOptions = { name: string, debug?: boolean };
export function defineBiosphereChannel<R>({ name, debug }: UseBiosphereChannelOptions, refs: R, setup: UseBiosphereChannelSetupCallback<R>) {
    return defineStore(`biosphere-channel:${name}`, () => {
        const { biosphere } = useBiosphere();
        const _channel = ref<BiosphereChannel>();

        tryOnMounted(async () => {
            set(_channel, await biosphere.channel(name, { debug }));
            setup(get(_channel)!, refs);
        });

        tryOnUnmounted(() => {
            get(_channel)!.close();
        });

        const channel = () => {
            const channel = get(_channel);

            if (channel === undefined) {
                throw new Error(`Channel '${name}' is not yet initialized.`);
            }

            return channel;
        }

        return {
            channel,
            ...refs,
        };
    });
}
