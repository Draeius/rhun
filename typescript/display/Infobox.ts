
module display {

    export class Infobox {
        public static showInfobox(event: MouseEvent, offsetX: number, offsetY: number) {
            var parent = (<HTMLElement>event.currentTarget);
            if (!parent) {
                return;
            }
            var infobox = $(parent).find('.infobox');

            infobox.css('display', 'block');
            infobox.css('left', event.pageX + offsetX);
            infobox.css('top', event.pageY + offsetY);
            
        }

        public static hideInfobox(event: MouseEvent) {
            var parent = (<HTMLElement>event.currentTarget);
            if (!parent) {
                return;
            }
            var infobox = $(parent).find('.infobox');

            infobox.css('display', 'none');
        }

        private static isElementInViewport(el: HTMLElement) {
            //special bonus for those using jQuery
            if (typeof jQuery === "function" && el instanceof jQuery) {
                el = el[0];
            }

            var rect = el.getBoundingClientRect();

            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
                rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
            );
        }
    }

}