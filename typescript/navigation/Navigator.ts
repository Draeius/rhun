module navigation {

    export class Navigator {

        private form: HTMLFormElement;

        public createForm(target: string, fields: { [key: string]: string }) {
            var form = document.createElement('form');
            form.method = "POST";
            if (target != null && target != '') {
                form.action = target;
            }

            for (var key in fields) {
                var input = document.createElement('input');
                input.type = "hidden";
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            }
            this.form = form;
        }

        public navigate() {
            document.body.appendChild(this.form);
            //send the whole stuff
            this.form.submit();
        }
    }
}