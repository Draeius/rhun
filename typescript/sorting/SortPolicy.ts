module Sorting {

    export interface SortPolicy {
        sort(value: string, compare: string, reverse: number): number;
    }
}