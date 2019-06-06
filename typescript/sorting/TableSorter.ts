
module Sorting {

    export class TableSorter {

        public makeAllSortable(parent: HTMLElement) {
            parent = parent || document.body;
            var tables = parent.getElementsByTagName('table');
            var index = tables.length;
            while (--index >= 0) {
                this.makeSortable(tables[index]);
            }
        }

        public makeSortable(table: HTMLTableElement) {
            var _this = this;
            var th: any = table.tHead, i;
            th && (th = th.rows[0]) && (th = th.cells);
            if (th)
                i = th.length;
            else
                return;
            while (--i >= 0)
                (function (i) {
                    var dir = 1;
                    th[i].addEventListener('click', function () {_this.sortTable(table, i, (dir = 1 - dir));});
                }(i));
        }

        private sortTable(table: HTMLTableElement, col: number, reverse: number) {
            var tb = table.tBodies[0], // use `<tbody>` to ignore `<thead>` and `<tfoot>` rows
                tr: HTMLTableRowElement[] = Array.prototype.slice.call(tb.rows, 0), // put rows into array
                index,
                sortPolicy: SortPolicy;

            reverse = -((+reverse) || -1);

            if ($(tr[0].cells[col]).attr('data-type') == 'date') {
                sortPolicy = new DateSortPolicy();
                tr = tr.sort((a, b) => {
                    var value = $(a.cells[col]).attr('data-value');
                    var compare = $(b.cells[col]).attr('data-value');

                    return sortPolicy.sort(value, compare, reverse);
                });
            } else {
                if ($(tr[0].cells[col]).attr('data-type') == 'number') {
                    sortPolicy = new NumberSortPolicy();
                } else {
                    sortPolicy = new StandardSortPolicy();
                }
                tr = tr.sort((a, b) => {
                    var value = a.cells[col].textContent.trim();
                    var compare = b.cells[col].textContent.trim();

                    return sortPolicy.sort(value, compare, reverse);
                });
            }

            for (index = 0; index < tr.length; ++index) {
                tb.appendChild(tr[index]);
            } // append each row in order
        }
    }
}