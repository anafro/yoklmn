import { useEcho } from "@laravel/echo-vue";
import { YoklmnEvents } from "./events";

export function listen<TEventName extends keyof YoklmnEvents>(
    channel: string,
    event: TEventName,
    handler: (event: YoklmnEvents[TEventName]) => void
): ReturnType<typeof useEcho> {
    return useEcho(channel, event, handler);
}
