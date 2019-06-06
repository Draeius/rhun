var Logging;
(function (Logging) {
    Logging.SHOW_DEBUG = true;
    Logging.SHOW_LOG = true;
    Logging.SHOW_WARNING = true;
    var Logger = (function () {
        function Logger() {
        }
        Logger.log = function (message) {
            if (Logging.SHOW_LOG) {
                console.log(message);
            }
        };
        Logger.debug = function (message) {
            if (Logging.SHOW_DEBUG) {
                console.debug(message);
            }
        };
        Logger.warning = function (message) {
            if (Logging.SHOW_WARNING) {
                console.warn(message);
            }
        };
        Logger.error = function (message) {
            console.error(message);
        };
        return Logger;
    }());
    Logging.Logger = Logger;
})(Logging || (Logging = {}));
var Util;
(function (Util) {
    var ImageMap = (function () {
        function ImageMap(map, image) {
            this.previousWidth = 1500;
            this.map = map;
            this.image = image;
            this.areas = map.getElementsByTagName('area');
            var length = this.areas.length;
            for (var index = 0; index < length; index++) {
                var split = this.areas[index].coords.split(',');
                for (var key in split) {
                    this.coords[index][key] = parseInt(split[key]);
                }
            }
            this.addToOnload();
        }
        ImageMap.prototype.resize = function () {
            var xStep = this.image.offsetWidth / this.previousWidth;
            var len = this.areas.length;
            for (var n = 0; n < len; n++) {
                var clen = this.coords[n].length;
                for (var m = 0; m < clen; m++) {
                    this.coords[n][m] *= xStep;
                }
                this.areas[n].coords = this.coords[n].join(',');
            }
            this.previousWidth = this.image.offsetWidth;
            return true;
        };
        ImageMap.prototype.addToOnload = function () {
            window.addEventListener("load", this.resize, false);
        };
        return ImageMap;
    }());
    Util.ImageMap = ImageMap;
})(Util || (Util = {}));
//# sourceMappingURL=main.js.map