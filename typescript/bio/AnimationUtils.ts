module Biography {

    export class AnimationUtils {

        /**
        * Dreht das übergebene Element um einen bestimmten Winkel in einer festgelegten Zeit. 
        * @param {Element} element Das Element welches gedreht werden soll.
        * @param {number} degree Die Grad-zahl um die gedreht wird. Wenn sie 0 ist, wird unendlich lang weiter gedreht.
        * @param {number} duration Die Zeit in ms in der gedreht wird.
        * @returns {Velocity} Um eine unendliche Drehung wieder zu stoppen einfach die stop() Funktion dieses Elements aufrufen.
        */
        public static rotate(element: HTMLElement, degree: number, duration: number) {
            var rotateZ = degree ? degree : 360;
            return $(element).velocity({
                rotateZ: rotateZ
            }, {
                    delay: 0,
                    easing: degree !== 0 ? "swing" : "linear",
                    duration: duration,
                    loop: degree === 0
                });
        };

        /**
         * Schüttelt das übergebene Element X mal um 15° nach rechts und links.
         * @param {Element} element Das Element das geschüttelt werden soll.
         * @param {number} [times] Die Anzahl der Wiederholungen.
         * @returns {void}
         */
        public static shake(element: HTMLElement, times: number) {
            $(element).velocity({
                rotateZ: 15
            }, {
                    delay: 0,
                    duration: 80
                }).velocity({
                    rotateZ: -15
                }, {
                        delay: 0,
                        loop: times,
                        duration: 80
                    }).velocity({
                        rotateZ: 0
                    }, {
                            delay: 0,
                            duration: 80
                        });
        };

        /**
         * Randomly fades elements in that have the class "blink".
         * @param {Object} config The config may contain the following atrributes: <br>
         * fadeIn: The time in ms to fade in. Default: 350<br>
         * fadeOut: The time in ms to fade out. Default: 2500<br>
         * delay: The time until the next execution. Default: 300<br>
         * probability: The probability that the element will fade in. Values <= 0 means never, >= 100 always. Default: 10
         * @returns {void}
         */
        public static blink(options: BlinkOptions) {
            if (typeof options !== "object") {
                options = new Object();
            }
            let defaults = {
                fadeIn: 350,
                fadeOut: 2500,
                delay: 300,
                probability: 10
            };
            var config = $.extend({}, defaults, options);

            $(".blink").each(function () {
                var random = Math.floor(Math.random() * 100);
                //chance for each tag to show up
                if (random <= config.probability) {
                    //time to fade in
                    $(this).velocity({opacity: 1}, config.fadeIn, function () {
                        //time to fade out
                        $(this).velocity({opacity: 0}, config.fadeOut);
                    });
                }
            });
            //delay until redo
            window.setTimeout(function () {
                AnimationUtils.blink(config);
            }, config.delay);
        };

        /**
         * Bewegt ein Element an seine Position auf der Seite.
         * @param {Element} element Das Element das bewegt werden soll.
         * @param {number} startTop Die Top-Koordinate an der das Element startet.
         * @param {number} startLeft Die Left-Koordinate an der das Element startet.
         * @returns {void}
         */
        public static floatToPosition(element: HTMLElement, startTop: number, startLeft: number, callback?: Function) {
            var originLeft = $(element).offset().left;
            var originTop = $(element).offset().top;

            var temp = $(element).clone().css("opacity", 0);
            $(element).after(temp);
            $(element).velocity({
                top: originTop,
                left: originLeft
            }, {
                    duration: 5000,
                    easing: "swing",
                    complete: function () {
                        $(element).css("position", "static");
                        temp.remove();
                        if (callback) {
                            callback();
                        }
                    },
                    begin: function () {
                        $(element).css({
                            position: "absolute",
                            top: startTop,
                            left: startLeft
                        });
                    }
                });
        };
    };
}