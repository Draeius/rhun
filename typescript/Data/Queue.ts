
class Queue<T> implements Iterable<T>{

    private _store: T[] = [];

    push(val: T) {
        this._store.push(val);
    }

    pop(): T | undefined {
        return this._store.shift();
    }

    peek(): T {
        return this._store[0];
    }

    peekLast(): T {
        return this._store[this._store.length - 1];
    }

    length(): number {
        return this._store.length;
    }

    [Symbol.iterator]() {
        let pointer = 0;
        let components = this._store;

        return {
            next(): IteratorResult<T> {
                if (pointer < components.length) {
                    return {
                        done: false,
                        value: components[pointer++]
                    }
                } else {
                    return {
                        done: true,
                        value: null
                    }
                }
            }
        }
    }
}
