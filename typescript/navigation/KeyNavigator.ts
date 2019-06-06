
module navigation {

    export class KeyNavigator {

        private map: { [key: string]: HTMLElement } = {};

        constructor() {
            var that = this;
            $('.nav').each((index, value) => {
                that.addNav(<HTMLElement>value);
            });
            $('body').keydown((event: KeyboardEvent) => {
                that.navigate(event);
            })
        }

        public addNav(nav: HTMLElement) {
            var text = nav.textContent;
            text = text.trim();
            if (text == "") {
                return;
            }
            for (var index = 0; index < text.length; index++) {
                var char = text.charAt(index).toLowerCase();
                var regex = /^[\u0020-\u007e\u00a0-\u00ff]*$/;
                if (regex.test(char) && char != '`' && !(char in this.map)) {
                    if ((index > 0 && text.charAt(index - 1) != '`' && text.charAt(index - 1) != ' ') || index == 0) {
                        this.map[char] = nav;
                        this.markKey(nav, index);
                        return;
                    }
                }
            }
        }

        public navigate(event: KeyboardEvent) {
            if (event.altKey) {
                return;
            }
            if (event.ctrlKey) {
                return;
            }
            if (event.target instanceof HTMLTextAreaElement) {
                return;
            }
            if (event.target instanceof HTMLInputElement) {
                return;
            }
            if (event.target instanceof HTMLSelectElement) {
                return;
            }
            if (event.key in this.map) {
                this.map[event.key].click();
            }
        }

        private markKey(nav: HTMLElement, position: number) {
            var text = nav.textContent.trim();
            nav.textContent = [
                text.slice(0, position),
                "`H",
                text.slice(position, position + 1),
                "`H",
                text.slice(position + 1)
            ].join('');
        }
    }
}