
module Content {

    export class FormHelper {
        private observedElement: JQuery;
        private form: JQuery;
        private replaceIds: string[]

        public constructor(elementId: string, replaceIds: string[]) {
            this.observedElement = $('#' + elementId);
            this.replaceIds = replaceIds;
            if (this.checkIds()) {
                this.getForm();
            }
        }

        public action(html: string): void {
            let parsed = $(html);
            for (let id of this.replaceIds) {
                $('#' + id).replaceWith(
                    parsed.find('#' + id)
                );
            }
        }


        public init(): void {
            var _this = this;
            this.observedElement.change(() => {
                let data = this.form.serialize();
                $.ajax({
                    url: this.form.attr('action'),
                    type: this.form.attr('method'),
                    data: data,
                    success: (data) => {
                        _this.action(data);
                    }
                });
            })

        }

        private getForm(): void {
            this.form = this.observedElement.closest('form');
        }

        private checkIds(): boolean {
            if (!this.observedElement) {
                console.error("ObservedElement " + this.observedElement + " does not exist.");
                return false;
            }
            if (!this.replaceIds) {
                console.error("Replace Ids is empty.");
                console.error(this.replaceIds);
                return false;
            }
            return true;
        }
    }

}
