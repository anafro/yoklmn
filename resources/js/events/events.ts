export type PingEvent = {};
export type PongEvent = {};
export type PlayerJoinedEvent = {};
export type PlayerExitedEvent = {};
export type PlayerDisconnectedEvent = {};

export interface YoklmnEvents {
    'ping': PingEvent,
    'pong': PongEvent,
    'player.joined': PlayerJoinedEvent,
    'player.exited': PlayerExitedEvent,
    'player.disconnected': PlayerDisconnectedEvent,
}

export type YoklmnEvent = keyof YoklmnEvents;
