
namespace App.Location {

    export class Bank {

        private static draw = true;

        public static changeInteraction(path: string) {
            this.draw = !this.draw;
            $(document.forms.namedItem("bank_form")).attr("action", path);
        }

    }

}