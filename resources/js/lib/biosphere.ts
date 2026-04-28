import { biosphere, BiosphereChannel } from "@anf/biosphere-client";
import { usePage } from "@inertiajs/vue3";
import { defineStore } from "pinia";
import { onMounted, onUnmounted, ref } from "vue";
import { get, set } from "@vueuse/core";


export function useBiosphere() {
    const page = usePage();

    return {
        biosphere: biosphere({
            csrf: page.props.csrf as string,
            url: undefined,
        }),
    };
}

export type UseBiosphereChannelSetupCallback = (channel: BiosphereChannel) => void;
export type UseBiosphereChannelOptions = { name: string };
export function defineBiosphereChannel({ name }: UseBiosphereChannelOptions, setup: UseBiosphereChannelSetupCallback) {
    return defineStore(`biosphere-channel:${name}`, () => {
        const channel = ref<BiosphereChannel>();

        const { biosphere } = useBiosphere();

        onMounted(async () => {
            set(channel, await biosphere.channel(name));
            setup(get(channel)!);
        });

        onUnmounted(() => {
            get(channel)?.close();
        });

        return {
            channel,
        }
    });
}
