export * from './auth';

import type { Auth } from './auth';

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    auth: Auth;
    [key: string]: unknown;
};

export type MaybePromise<T> = T | Promise<T>;
type IsArg<A> =
    A extends object
    ? A extends never
    ? false
    : true
    : true

export type MaybeAsync<F> =
    F extends (a1: infer A1, a2: infer A2, a3: infer A3, a4: infer A4, a5: infer A5, a6: infer A6, a7: infer A7, a8: infer A8, a9: infer A9, a10: infer A10, a11: infer A11, a12: infer A12) => infer R
    ? (
        IsArg<A12> extends true ? ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6, a7: A7, a8: A8, a9: A9, a10: A10, a11: A11, a12: A12) => R) | ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6, a7: A7, a8: A8, a9: A9, a10: A10, a11: A11, a12: A12) => Promise<R>) :
        IsArg<A11> extends true ? ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6, a7: A7, a8: A8, a9: A9, a10: A10, a11: A11) => R) | ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6, a7: A7, a8: A8, a9: A9, a10: A10, a11: A11) => Promise<R>) :
        IsArg<A10> extends true ? ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6, a7: A7, a8: A8, a9: A9, a10: A10) => R) | ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6, a7: A7, a8: A8, a9: A9, a10: A10) => Promise<R>) :
        IsArg<A9> extends true ? ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6, a7: A7, a8: A8, a9: A9) => R) | ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6, a7: A7, a8: A8, a9: A9) => Promise<R>) :
        IsArg<A8> extends true ? ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6, a7: A7, a8: A8) => R) | ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6, a7: A7, a8: A8) => Promise<R>) :
        IsArg<A7> extends true ? ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6, a7: A7) => R) | ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6, a7: A7) => Promise<R>) :
        IsArg<A6> extends true ? ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6) => R) | ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5, a6: A6) => Promise<R>) :
        IsArg<A5> extends true ? ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5) => R) | ((a1: A1, a2: A2, a3: A3, a4: A4, a5: A5) => Promise<R>) :
        IsArg<A4> extends true ? ((a1: A1, a2: A2, a3: A3, a4: A4) => R) | ((a1: A1, a2: A2, a3: A3, a4: A4) => Promise<R>) :
        IsArg<A3> extends true ? ((a1: A1, a2: A2, a3: A3) => R) | ((a1: A1, a2: A2, a3: A3) => Promise<R>) :
        IsArg<A2> extends true ? ((a1: A1, a2: A2) => R) | ((a1: A1, a2: A2) => Promise<R>) :
        IsArg<A1> extends true ? ((a1: A1) => R) | ((a1: A1) => Promise<R>) :
        (() => R) | (() => Promise<R>)
    )
    : never;
