import { defineStore } from "pinia";
import { ref } from "vue";

export const useMenuModeStore = defineStore('menu-mode-description', () => {
    const menuModeShown = ref<boolean>(false);
    const menuModeName = ref<string>('');
    const menuModeDescription = ref<string>('');

    return {
        menuModeShown,
        menuModeName,
        menuModeDescription,
    };
});
