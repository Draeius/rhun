
module Sorting {
    export class StandardSortPolicy implements SortPolicy {

        public sort(value: string, compare: string, reverse: number): number {
            return reverse * value.localeCompare(compare)
        }
    }
}