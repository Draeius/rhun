
module Util {

    export class ImageMap {

        /** 
         * Enthält das HTMLElement, welches die einzelnen Links definiert. 
         */
        private map: HTMLMapElement;

        /**
         * Enthält das Bild der Karte.
         */
        private image: HTMLImageElement;

        private areas: HTMLCollectionOf<HTMLAreaElement>;

        private previousWidth = 1500;

        private coords: number[][];


        constructor(map: HTMLMapElement, image: HTMLImageElement) {
            this.map = map;
            this.image = image;
            this.areas = map.getElementsByTagName('area');

            let length = this.areas.length;
            for (let index = 0; index < length; index++) {
                let split = this.areas[index].coords.split(',');
                for (let key in split) {
                    this.coords[index][key] = parseInt(split[key]);
                }
            }
            this.addToOnload();
        }

        public resize(): boolean {
            let xStep = this.image.offsetWidth / this.previousWidth;
            let len = this.areas.length;

            for (let n = 0; n < len; n++) {
                let clen = this.coords[n].length;
                for (let m = 0; m < clen; m++) {
                    this.coords[n][m] *= xStep;
                }
                this.areas[n].coords = this.coords[n].join(',');
            }
            this.previousWidth = this.image.offsetWidth;
            return true;
        }

        private addToOnload(): void {
            window.addEventListener("load", this.resize, false);
        }
    }
}
