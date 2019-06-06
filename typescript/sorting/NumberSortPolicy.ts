module Sorting {
    
    export class NumberSortPolicy implements SortPolicy {

        public sort(value: string, compare: string, reverse: number): number {
            var result = 0;
            var floatValue = parseFloat(value);
            var floatCompare = parseFloat(compare);
            if (floatValue < floatCompare) {
                result = -1;
            }
            if (floatValue > floatCompare) {
                result = 1;
            }
            return reverse * result;
        }
    }
}