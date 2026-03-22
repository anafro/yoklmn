export function random(max: number): number {
    return Math.floor(Math.random() * max);
}

export function pick<TElement>(array: TElement[]): TElement {
    if (array.length === 0) {
        throw new Error(`Cannot pick from an empty array.`);
    }

    const index = random(array.length);
    return array[index];
}
