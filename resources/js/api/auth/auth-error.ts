import { defineStore } from "pinia";
import { computed, ref } from "vue";

export const useAuthErrorStore = defineStore('auth-error', () => {
    const errorMessage = ref<string | null>(null);
    const hasErrorMessage = computed<boolean>(() => errorMessage !== null);

    return {
        errorMessage,
        hasErrorMessage,
    };
});
