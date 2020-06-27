
module formatting {

    export class Preview {

        private view: HTMLElement;
        private input: any;
        private lastText: string;
        private requestThreshold: number = 500;
        private checkThreshold: number = 200;
        private lastActive: number;


        public constructor(input: any, view: HTMLElement) {
            this.view = view;
            this.input = input;
            this.lastActive = Date.now();
            var _this = this;
            setInterval(_this.getPreview, this.checkThreshold, this)
            $(input).keyup(function() {
                _this.update();
            })
        }

        public update() {
            this.lastActive = Date.now();
        }

        public getPreview(_this: Preview) {
            var text = '';
            if (Date.now() - _this.lastActive - _this.requestThreshold < 0) {
                return;
            }

            if (typeof _this.input.value !== 'undefined') {
                text = _this.input.value.trim();
            } else {
                text = _this.input.getValue().trim();
            }

            if (_this.lastText == text) {
                return;
            }

            var con = new Ajax.AjaxConnector();
            _this.lastText = text;
            console.log(con.getBaseURL() + "format/preview");
            con.getData(con.getBaseURL() + "format/preview", { text: text }, function(preview: string) {
                _this.view.innerHTML = preview;
            });
        }


    }
}