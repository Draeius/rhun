
module formatting {

    export class TextTree {

        private formats: { [tag: string]: Format };
        private delimiter = '`';
        private textArray: string[];
        private root = new FormatNode();

        public constructor(text: string) {
            this.textArray = text.match(new RegExp(this.delimiter + "?.{1}[^" + this.delimiter + "]*", "g"));
        }
        
        public setFormats(formats: { [tag: string]: Format }){
            this.formats = formats;
        }

        public buildTree() {
            for (var text of this.textArray) {
                var code = this.getCode(text);
                if (code != "") {
                    if (this.formats[code] != null && this.formats[code].color) {
                        this.addColorNode(text);
                    }
                    else {
                        this.addNonColorNode(text);
                    }
                } else {
                    this.root.appendChild(this.buildNode(text, false));
                }
            }
        }

        private addNonColorNode(text: string) {
            var jump = this.codeIsOpen(this.getCode(text));
            if (jump) {
                this.root = jump;
            }
            else {
                this.moveDown();
            }
            this.root.appendChild(this.buildNode(text, !jump));
            if (this.isBreakTag(this.root.getLastChild())) {
                this.root.appendChild(this.buildNode(text, false));
            }
            if (this.root.getLastChild().getFormat()) {
                this.moveDown();
            }
        };

        private addColorNode(text: string) {
            if (!this.root.getFormat()) {
                this.root.appendChild(this.buildNode(text, true));
                return;
            }
            if (!this.root.getFormat().color) {
                this.root.appendChild(this.buildNode(text, true));
                return;
            }
            if (this.root.getParentNode() != null) {
                this.moveUp();
            }
            this.root.appendChild(this.buildNode(text, true));
        };

        private moveUp() {
            if (this.root.getParentNode() == null) {
                return;
            }
            this.root = this.root.getParentNode();
        };

        private moveDown() {
            var lastChild = this.root.getLastChild();
            if (lastChild == null) {
                return;
            }
            if (this.isBreakTag(lastChild)) {
                return;
            }
            this.root = lastChild;
        };

        private isBreakTag(node: FormatNode) {
            if (!node) {
                return false;
            }
            if (!node.getFormat()) {
                return false;
            }
            if (!node.getFormat().tag) {
                return false;
            }
            return node.getFormat().tag.toUpperCase() == "BR";
        };

        private codeIsOpen(code: string) {
            var current = this.getLastInsertedNode();
            while (current != null) {
                if (current.getFormat() != null && current.getFormat().code == code) {
                    return current.getParentNode();
                }
                current = current.getParentNode();
            }
            return null;
        };

        private getLastInsertedNode() {
            if (this.root.getLastChild() == null) {
                return this.root;
            }
            return this.root.getLastChild();
        };

        private buildNode(text: string, hasFormat: boolean) {
            var node = new FormatNode();
            if (hasFormat) {
                node.setFormat(this.formats[this.getCode(text)]);
            }
            if (text.indexOf(this.delimiter) != -1) {
                node.setText(text.substring(2));
            } else {
                node.setText(text);
            }
            return node;
        };

        private getCode(text: string): string {
            if (text.indexOf(this.delimiter) != -1) {
                return text.substring(1, 2);
            }
            return ''
        }

        public getRoot() {
            return this.root;
        };

        public getRealRoot() {
            var root = this.root;
            while (root.getParentNode() != null) {
                root = root.getParentNode();
            }
            return root;
        };

        public getFormats() {
            return this.formats;
        };
    }
}