module formatting {

    export class Formatter {

        private formats: { [tag: string]: Format };

        public applyFormats() {
            var body = document.body;
            this.iterateNodes(body);
        };

        public iterateNodes(node: Node) {
            this.getFormats(node);
        };

        private getFormats(node: Node) {
            var _this = this;
            formatting.getFormats(function() {
                _this.formats = formatting.formats;
                _this.iterateNodesAfterFormat(node);
            });
        }

        private iterateNodesAfterFormat(node: Node) {
            if (typeof node.nodeType == 'undefined') {
                return;
            }
            if (node.hasChildNodes()) {
                var children = Array.prototype.slice.call(node.childNodes)
                for (var child of children) {
                    this.iterateNodes(child);
                }
            }
            if (node.nodeType == 3) {
                this.handleTextNode(node);
            }
        }


        public handleTextNode(node: Node) {
            var text = this.getText(node);
            //            if (text.indexOf("Männlich") != -1) { console.log(text) }
            if (text.indexOf('`') == -1) {
                return;
            }

            var tree = new TextTree(text);
            var _this = this;

            tree.setFormats(this.formats);
            tree.buildTree();
            var dom = tree.getRealRoot().buildDOM();
            _this.insert(node, dom);


        };

        private insert(node: Node, dom: HTMLElement) {
            if (dom.children.length == 0) {
                return;
            }
            $(node).replaceWith($(dom.children));
        };

        private getText(node: Node): string {
            var text = node.nodeValue.replace(/^\s+|[\n\r]|\s+$/g, '').trim();
            return text;
        };

        public decodeText(text: string) {
            var txtArea = document.createElement("textarea");
            txtArea.innerHTML = text;
            return txtArea.innerText;
        };

        public encodeText(text: string) {
            return $('<div/>').text(text).html();
        };
    }
}