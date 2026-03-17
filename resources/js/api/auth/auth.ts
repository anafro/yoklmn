import { defineStore, storeToRefs } from "pinia";
import { useAuthAction } from "./auth-action";
import { ref } from "vue";
import { useLoginStore } from "./login";
import { useSignupStore } from "./signup";
import { useAuthErrorStore } from "./auth-error";
import { get, set } from "@vueuse/core";
import { goto } from "@/navigation/goto";

export const useAuthStore = defineStore('auth', () => {
    const name = ref<string>('');
    const email = ref<string>('');
    const password = ref<string>('');
    const passwordAgain = ref<string>('');

    const {
        requestAuthAction: requestAuthActionByName,
        isAuthActionRequested,
        authAction,
    } = useAuthAction();

    const {
        isLoginRequested,
        loggedIn,
    } = storeToRefs(useLoginStore());
    const {
        requestLogin: requestLoginWithCredentials,
    } = useLoginStore();

    const {
        isSignupRequested,
        signedUp,
    } = storeToRefs(useSignupStore());
    const {
        requestSignup: requestSignupWithCredentials,
    } = useSignupStore();

    const {
        errorMessage,
    } = storeToRefs(useAuthErrorStore());

    async function requestAuthAction(): Promise<void> {
        await requestAuthActionByName(name);
    }

    async function requestSignup(): Promise<void> {
        if (get(password) !== get(passwordAgain)) {
            set(errorMessage, "Пароли не совпадают");
            return;
        }

        await requestSignupWithCredentials(name, email, password);

        if (signedUp) {
            goto('menu');
        }
    }

    async function requestLogin(): Promise<void> {
        await requestLoginWithCredentials(name, password);

        if (signedUp) {
            goto('menu');
        }

    }

    return {
        name,
        email,
        password,
        passwordAgain,

        requestAuthAction,
        isAuthActionRequested,
        authAction,

        requestSignup,
        isSignupRequested,
        signedUp,

        requestLogin,
        isLoginRequested,
        loggedIn,
    };
});
