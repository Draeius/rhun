/**
 * Module that contains classes and functions for editors
 */
module editor {
    export class AreaEditor extends Editor {

        constructor() {
            super("area_add", "area_edit", "area_delete");
        }
    }
}