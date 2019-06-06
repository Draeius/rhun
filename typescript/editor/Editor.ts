/**
 * Module that contains classes and functions for editors
 */
module editor {
 
    /**
     * Superclass of all Editors
     */
    export class Editor {

        private addForm: HTMLFormElement;
        private editForm: HTMLFormElement;
        private deleteForm: HTMLFormElement;

        private hideOnChange: boolean; //if true, the editor hides the old form on mode change

        /**
         * Creates a new Editor
         * @param addFormId Id of the form that sends a Request to add new elements to the db
         * @param editFormId Id of the form that alters existing data
         * @param deleteFormId Id of the form that deletes db entries
         */
        constructor(addFormId: string, editFormId: string, deleteFormId: string) {
            this.addForm = this.findForm(addFormId);
            this.editForm = this.findForm(editFormId);
            this.deleteForm = this.findForm(deleteFormId);
        }

        /**
         * Finds a form by its id and returns it. Null if no form with the given id exists
         * @param formId The id to search for.
         */
        public findForm(formId: string): HTMLFormElement {
            if (formId == null || formId == "") {
                return null;
            }
            var result = document.getElementById(formId);
            if (result != null && result.tagName == "form") {
                return <HTMLFormElement>result;
            }
            return null;
        }

        /**
         * Sets the editors mode.
         * @param mode The new mode
         */
        public selectMode(mode: Mode): void {
            this.changeVisibility(this.addForm, mode, Mode.add);
            this.changeVisibility(this.editForm, mode, Mode.edit);
            this.changeVisibility(this.deleteForm, mode, Mode.delete);
        }

        /**
         * Changes the visibility of the given HTMLFormElement to block or none respectively
         * @param form the form whose visibility should be changed
         * @param newMode the new mode that should be displayed
         * @param formMode the mode that the given form represents
         */
        private changeVisibility(form: HTMLFormElement, newMode: Mode, formMode: Mode): void {
            //check if form is null
            if (form == null) {
                return;
            }
            //if the new mode is equals the form's mode, set the form visible
            if (newMode == formMode) {
                form.style.display = "block";
                return;
            }
            //if the form is not of the same mode as the new one and it should be hidden, hide it
            if (this.hideOnChange) {
                form.style.display = "none";
            }
        }
    }

}