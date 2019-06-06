
module formatting {

    export class FormatNode {
        private parentNode: FormatNode = null;
        private children: FormatNode[] = [];
        private text: string = "";
        private format: Format = null;

        public constructor() {

        }

        public appendChild(child: FormatNode) {
            this.children.push(child);
            child.parentNode = this;
        };

        public buildDOM() {
            var root = this.createRoot();
            for (var node of this.children) {
                root.appendChild(node.buildDOM());
            }
            return root;
        };

        public createRoot() {
            var htmlTag = "span";
            if (this.format != null && this.format.tag) {
                htmlTag = this.format.tag;
            }
            var root = document.createElement(htmlTag);
            if (root.nodeName != "BR") {
                root.innerText = this.text;
            }
            if (this.format == null) {
                return root;
            }
            if (this.format.color) {
                root.style.color = "#" + this.format.color;
            }
            if (this.format.style) {
                root.className = this.format.style;
            }
            return root;
        };

        public getLastChild() {
            if (this.children.length == 0) {
                return null;
            }
            return this.children[this.children.length - 1];
        };

        public getParentNode() {
            return this.parentNode;
        };

        public getFormat(): Format {
            return this.format;
        };

        public setFormat(format: Format) {
            this.format = format;
        };

        public getText() {
            return this.text;
        };

        public setText(text: string) {
            this.text = text;
        };
    }
}