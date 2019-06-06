module bio {
    export interface Options {
        minSize?: number,
        maxSize?: number,
        newOn?: number,
        flakeColor?: string[],
        flakeChar?: string[]
    }

    export class Downfall {

        private documentHeight = window.innerHeight;
        private documentWidth = window.innerWidth;
        private defaults = {
            minSize: 10,
            maxSize: 20,
            newOn: 500,
            flakeColor: ["#FFFFFF"],
            flakeChar: ["&#10052;"]
        };
        private config: Options;
        private flake: JQuery<HTMLElement>;
        private intervalId: number;

        constructor(options: Options) {
            this.config = $.extend({}, this.defaults, options);
            console.log(this.config);
            this.flake = $('<div id="flake" />').css({'position': 'fixed', 'top': '-50px'})
        }


        public start() {
            var _this = this;
            this.intervalId = setInterval(function () {_this.run(_this.config, _this.documentWidth, _this.documentHeight);}, this.config.newOn);
        }

        public stop() {
            if (typeof this.intervalId !== "undefined") {
                clearInterval(this.intervalId);
            }
        }

        private getRandomArrayEntry(array: string[]) {
            return array[Math.floor(Math.random() * array.length)];
        }

        private run(config: Options, docWidth: number, docHeight: number) {
            var startPositionLeft = Math.random() * docWidth - 100;
            var startOpacity = 0.5 + Math.random();
            var sizeFlake = config.minSize + Math.random() * config.maxSize;
            var endPositionTop = docHeight - 40;
            var endPositionLeft = startPositionLeft - 100 + Math.random() * 200;
            var durationFall = docHeight * 10 + Math.random() * 5000;

            this.flake.clone().appendTo('body')
                .css(
                    {
                        left: startPositionLeft,
                        opacity: startOpacity,
                        'font-size': sizeFlake,
                        color: this.getRandomArrayEntry(this.config.flakeColor)
                    }
                ).html(this.getRandomArrayEntry(this.config.flakeChar))
                .velocity(
                    {
                        top: endPositionTop,
                        left: endPositionLeft,
                        opacity: 0.2
                    },
                    durationFall,
                    'linear',
                    function () {
                        $(this).remove();
                    }
                );
        }
    }
}