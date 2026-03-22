const enAlphabet = 'qwertyuiop[]asdfghjkl;\'zxcvbnm,.`';
const ruAlphabet = 'йцукенгшщзхъфывапролджэячсмитьбюё';
const specialCharacters = '-';

export type WordInputAction = {
    type: 'write',
    char: string,
} | {
    type: 'erase',
    fast: boolean,
} | {
    type: 'move',
    fast: boolean,
    direction: 'left' | 'right'
} | {
    type: 'submit',
} | {
    type: 'unknown',
    key: string,
};

export function guessWordInputAction(event: KeyboardEvent): WordInputAction {
    const key = event.key.toLowerCase();

    if (ruAlphabet.includes(key) || specialCharacters.includes(key)) {
        return {
            type: 'write',
            char: key,
        };
    }

    if (enAlphabet.includes(key)) {
        const charIndex = enAlphabet.indexOf(key);
        return {
            type: 'write',
            char: ruAlphabet.charAt(charIndex),
        };
    }

    if (['arrowleft', 'arrowright'].includes(key)) {
        return {
            type: 'move',
            direction: key.slice(5) as 'left' | 'right',
            fast: event.ctrlKey,
        };
    }

    if (key === 'backspace') {
        return {
            type: 'erase',
            fast: event.ctrlKey,
        }
    }

    if (key === 'enter') {
        return {
            type: 'submit',
        }
    }

    return {
        type: 'unknown',
        key,
    };
}
