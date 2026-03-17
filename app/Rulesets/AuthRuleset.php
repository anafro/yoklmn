<?php

namespace App\Rulesets;

use Illuminate\Support\Arr;

final class AuthRuleset
{
    private function __construct()
    {
        //
    }

    /**
     * Returns rules for a user password.
     *
     * @param array<int,mixed>|string|null $additional Additional rules for user passwords.
     * @return array<int,mixed> A set of rules for a user password.
     */
    public static function email(array|string|null $additional = []): array
    {
        return self::mergeRules(
            [
                'required',
                'email',
                'min:6',
                'max:128',
            ],
            $additional,
        );
    }

    /**
     * Returns rules for a user name.
     *
     * @param array<int,mixed>|string|null $additional Additional rules for user names.
     * @return array<int,mixed> A set of rules for a user name.
     */
    public static function name(array|string|null $additional = []): array
    {
        return self::mergeRules(
            [
                'required',
                'min:3',
                'max:32',
                'alpha_num:ascii',
            ],
            $additional,
        );
    }

    /**
     * Returns rules for a user password.
     *
     * @param array<int,mixed>|string|null $additional Additional rules for user passwords.
     * @return array<int,mixed> A set of rules for a user password.
     */
    public static function password(array|string|null $additional = []): array
    {
        return self::mergeRules(
            [
                'required',
                'min:6',
                'max:128',
            ],
            $additional,
        );
    }

    /**
     * Merges two rule arrays together.
     *
     * @param array<int,mixed>|string|null $base Base (or 'default') rules for a field.
     * @param array<int,mixed>|string|null $additional Additional rules for a field.
     * @return array<int,array<int,mixed>> The merged rules.
     */
    private static function mergeRules(array|string|null $base, array|string|null $additional): array
    {
        return [
            ...Arr::wrap($base),
            ...Arr::wrap($additional),
        ];
    }
}
