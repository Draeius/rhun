
module Sorting {
    export class StandardSortPolicy implements SortPolicy {

        public sort(value: string, compare: string, reverse: number): number {
            if (isNaN(Number(value)) || isNaN(Number(value))) {
                return reverse * value.localeCompare(compare)
            }
            return reverse * Number(value) - Number(compare);
        }
    }
}