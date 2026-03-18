import { User } from "@/types";
import { usePage } from "@inertiajs/vue3";
import { defineStore } from "pinia";
import { computed } from "vue";

export const useUserStore = defineStore('user', () => {
    const page = usePage();
    const user = computed<User>(() => page.props.auth.user);

    return { user };
});
