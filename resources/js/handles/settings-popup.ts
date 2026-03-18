import { defineStore } from "pinia";
import { ref } from "vue";

export const useSettingsPopupStore = defineStore('settings', () => {
    const settingsPopupShown = ref<boolean>(false);

    return { settingsPopupShown };
});
