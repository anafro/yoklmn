import { useArray } from "@/lib/arrays";
import { defineStore } from "pinia";

export const usePlayerListStore = defineStore('player-list', () => {
    const {
        reference: players,
        push: addPlayer,
        drop: removePlayer,
    } = useArray<string>([]);

    return {
        players,
        addPlayer,
        removePlayer,
    };
});
