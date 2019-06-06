
module Sorting {
    export class DateSortPolicy implements SortPolicy {

        public sort(value: string, compare: string, reverse: number): number {
            var dateA = new Date(value);
            var dateB = new Date(compare);
            return reverse * (dateA > dateB ? -1 : dateA < dateB ? 1 : 0);
        }
    }
}