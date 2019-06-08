
module navigation {

    export class KeyNavigator {

        private map: {[key: string]: HTMLElement} = {};

        constructor() {
            var that = this;
            $('.nav').each((index, value) => {
                that.addNav(<HTMLElement> value);
            });
            $('body').on("keydown", (event: any) => {
                that.navigate(event);
            });
        }

        public addNav(nav: HTMLElement) {
            for (let key = 0; key < nav.children.length; key++) {
                let text = nav.children[key].textContent;
                if (text) {
                    for (let index = 0; index < text.length; index++) {
                        let char = text.charAt(index);
                        if (!(char.toLowerCase() in this.map) && isNaN(<any>char)) {
                            this.map[char.toLowerCase()] = nav;
                            nav.children[key].innerHTML = text.slice(0, index) + "<span style='text-decoration: underline;'>"
                                + char + "</span>" + text.slice(index + 1);
                            return;
                        }
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
    }
}