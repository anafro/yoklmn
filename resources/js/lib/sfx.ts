import { SoundEffect, sfxr } from "jsfxr";
import { defineStore } from "pinia";
import { shallowReactive } from "vue";
import { pick } from "./random";


export type SoundEffectMetadata = {
    name: string;
    variant?: string;
    mutation: number;
};

export type PendingSoundEffectModule = {
    json: Promise<SoundEffect>;
    metadata: SoundEffectMetadata;
}

export type SoundEffectModule = {
    json: SoundEffect;
    metadata: SoundEffectMetadata;
}

function parseSoundFilepath(filepath: string): SoundEffectMetadata {
    const regexp = /^.*\/(?<name>[a-z]+)\.(?:(?<variant>[a-z]+)\.)?(?<mutation>[0-9]+)\.json$/gm;
    const metadata = regexp.exec(filepath)?.groups;

    if (metadata === undefined) {
        throw new Error(`The SFX filepath '${filepath}' cannot be parsed to metadata.`);
    }

    return metadata as unknown as SoundEffectMetadata;
}

function getSoundEffectKey(soundEffect: SoundEffectMetadata): string {
    if (soundEffect.variant === undefined) {
        return soundEffect.name;
    } else {
        return `${soundEffect.name}.${soundEffect.variant}`;
    }
}

export const useSfxStore = defineStore('sfx', () => {
    const sounds = shallowReactive<Map<string, SoundEffect[]>>(new Map());

    function play(key: string): void {
        if (!sounds.has(key)) {
            console.error(sounds);
            throw new Error(`Sfx ${key} doesn't exist`);
        }

        const sound = pick(sounds.get(key)!);
        sfxr.play(sound);
    }

    (function loadSoundEffects(): void {
        const glob = import.meta.glob<SoundEffect>('../sfx/*.json', { eager: true, import: 'default' });
        for (const [filepath, soundEffect] of Object.entries(glob)) {
            const metadata = parseSoundFilepath(filepath);
            const key = getSoundEffectKey(metadata);

            if (!sounds.has(key)) {
                sounds.set(key, []);
            }

            sounds.get(key)!.push(soundEffect);
        }
    })();

    return {
        play,
    };
}, {
    persist: import.meta.env.PROD,
});
