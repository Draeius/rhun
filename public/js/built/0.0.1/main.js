var Util;
(function (Util) {
    var BroadcastCanvas = (function () {
        function BroadcastCanvas() {
            this.page = 0;
        }
        BroadcastCanvas.prototype.earlierMotCT = function () {
            this.page++;
            var con = new Ajax.AjaxConnector();
            var _this = this;
            con.getData('motd/show/' + this.page, { coding: 'true' }, function (data) {
                if (data['error']) {
                    console.debug(data['error']);
                    _this.page--;
                }
                else {
                    document.getElementById('motct_content').innerHTML = data['content'];
                }
            });
        };
        BroadcastCanvas.prototype.laterMotCT = function () {
            if (this.page <= 0) {
                return;
            }
            this.page--;
            var con = new Ajax.AjaxConnector();
            var _this = this;
            con.getData('motd/show/' + this.page, { coding: 'true' }, function (data) {
                if (data['error']) {
                    console.debug(data['error']);
                    _this.page++;
                }
                else {
                    document.getElementById('motct_content').innerHTML = data['content'];
                }
            });
        };
        return BroadcastCanvas;
    }());
    Util.BroadcastCanvas = BroadcastCanvas;
})(Util || (Util = {}));
var uuid;
var formats;
function getBaseURL() {
    return window.location.protocol + "//" + window.location.host + "/";
}
function AprilFool() {
    var randTopStart = Math.round(Math.random() * window.innerHeight);
    var randTopEnd = Math.round(Math.random() * window.innerHeight);
    var elem = $('<img>').attr('src', 'http://rhun-logd.de/resources/images/blcki.png')
        .css({
        position: 'absolute',
        top: randTopEnd + 'px',
        left: window.innerWidth + 200 + 'px',
        width: '300px',
        zIndex: 1000
    });
    $(document.body).append(elem);
    Biography.AnimationUtils.floatToPosition(elem.get(0), randTopStart, -300, function () {
        $(elem).remove();
    });
}
function initPreview() {
    $('[data-preview]').each(function () {
        var previewId = $(this).attr('data-preview');
        var element = document.getElementById(previewId);
        if (element) {
            new formatting.Preview(this, element);
        }
    });
}
$(document).ready(function () {
    var keyNavigator = new navigation.KeyNavigator();
    var sorter = new Sorting.TableSorter();
    sorter.makeAllSortable(null);
    initPreview();
});
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
var Ajax;
(function (Ajax) {
    var AjaxConnector = (function () {
        function AjaxConnector() {
            this.baseUrl = this.getBaseURL();
        }
        AjaxConnector.prototype.getPosts = function (callback, ooc, page, limit) {
            if (ooc === void 0) { ooc = true; }
            if (page === void 0) { page = 1; }
            if (limit === void 0) { limit = 10; }
            if (ooc) {
                var urlPart = "ooc/get";
            }
            else {
                var urlPart = "post/get/" + uuid;
            }
            var url = this.baseUrl + urlPart + "?limit=" + limit + "&page=" + page;
            $.ajax(url)
                .done(function (data) {
                callback(data);
            })
                .fail(function (data) {
                console.log("failed to get posts");
                $(document.body).append(data.responseText);
            });
        };
        AjaxConnector.prototype.getData = function (url, params, callback) {
            var url = this.baseUrl + url;
            console.log(url);
            $.post({
                type: "POST",
                url: url,
                data: params,
            })
                .done(function (data) {
                if (data['ERR'] && data['ERR'] == 'logout') {
                    $(document.body).css('color', '#2E2E2E');
                }
                else {
                    callback(data);
                }
            })
                .fail(function (data) {
                console.log("failed to get data. URL: " + url);
                $(document.body).append(data.responseText);
            });
        };
        AjaxConnector.prototype.submitForm = function (form, callback) {
            console.log(form.action);
            $.ajax({
                type: "POST",
                url: form.action,
                data: $(form).serialize(),
                success: function (data) {
                    if (callback) {
                        callback(data);
                    }
                },
                error: function (data) {
                    $(document.body).append(data.responseText);
                }
            });
            return false;
        };
        AjaxConnector.prototype.getBaseURL = function () {
            return window.location.protocol + "//" + window.location.host + "/";
        };
        return AjaxConnector;
    }());
    Ajax.AjaxConnector = AjaxConnector;
})(Ajax || (Ajax = {}));
var Biography;
(function (Biography) {
    var AnimationUtils = (function () {
        function AnimationUtils() {
        }
        AnimationUtils.rotate = function (element, degree, duration) {
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
        ;
        AnimationUtils.shake = function (element, times) {
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
        ;
        AnimationUtils.blink = function (options) {
            if (typeof options !== "object") {
                options = new Object();
            }
            var defaults = {
                fadeIn: 350,
                fadeOut: 2500,
                delay: 300,
                probability: 10
            };
            var config = $.extend({}, defaults, options);
            $(".blink").each(function () {
                var random = Math.floor(Math.random() * 100);
                if (random <= config.probability) {
                    $(this).velocity({ opacity: 1 }, config.fadeIn, function () {
                        $(this).velocity({ opacity: 0 }, config.fadeOut);
                    });
                }
            });
            window.setTimeout(function () {
                AnimationUtils.blink(config);
            }, config.delay);
        };
        ;
        AnimationUtils.floatToPosition = function (element, startTop, startLeft, callback) {
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
        ;
        return AnimationUtils;
    }());
    Biography.AnimationUtils = AnimationUtils;
    ;
})(Biography || (Biography = {}));
var bio;
(function (bio) {
    var Downfall = (function () {
        function Downfall(options) {
            this.documentHeight = window.innerHeight;
            this.documentWidth = window.innerWidth;
            this.defaults = {
                minSize: 10,
                maxSize: 20,
                newOn: 500,
                flakeColor: ["#FFFFFF"],
                flakeChar: ["&#10052;"]
            };
            this.config = $.extend({}, this.defaults, options);
            console.log(this.config);
            this.flake = $('<div id="flake" />').css({ 'position': 'fixed', 'top': '-50px' });
        }
        Downfall.prototype.start = function () {
            var _this = this;
            this.intervalId = setInterval(function () { _this.run(_this.config, _this.documentWidth, _this.documentHeight); }, this.config.newOn);
        };
        Downfall.prototype.stop = function () {
            if (typeof this.intervalId !== "undefined") {
                clearInterval(this.intervalId);
            }
        };
        Downfall.prototype.getRandomArrayEntry = function (array) {
            return array[Math.floor(Math.random() * array.length)];
        };
        Downfall.prototype.run = function (config, docWidth, docHeight) {
            var startPositionLeft = Math.random() * docWidth - 100;
            var startOpacity = 0.5 + Math.random();
            var sizeFlake = config.minSize + Math.random() * config.maxSize;
            var endPositionTop = docHeight - 40;
            var endPositionLeft = startPositionLeft - 100 + Math.random() * 200;
            var durationFall = docHeight * 10 + Math.random() * 5000;
            this.flake.clone().appendTo('body')
                .css({
                left: startPositionLeft,
                opacity: startOpacity,
                'font-size': sizeFlake,
                color: this.getRandomArrayEntry(this.config.flakeColor)
            }).html(this.getRandomArrayEntry(this.config.flakeChar))
                .velocity({
                top: endPositionTop,
                left: endPositionLeft,
                opacity: 0.2
            }, durationFall, 'linear', function () {
                $(this).remove();
            });
        };
        return Downfall;
    }());
    bio.Downfall = Downfall;
})(bio || (bio = {}));
var content;
(function (content) {
    var ContentManager = (function () {
        function ContentManager(currVisible) {
            this.currentlyVisible = currVisible;
        }
        ;
        ContentManager.prototype.switchContent = function (id) {
            if (id === this.currentlyVisible) {
                return;
            }
            var newElement = $('#' + id);
            if (typeof this.currentlyVisible === "undefined" || this.currentlyVisible === null) {
                newElement.velocity("fadeIn", 1500);
            }
            else {
                $('#' + this.currentlyVisible).velocity("fadeOut", 900, function () {
                    newElement.velocity("fadeIn", 1500);
                });
            }
            this.currentlyVisible = id;
        };
        ;
        ContentManager.openTab = function (evt, tabName) {
            var tabcontent, tablinks;
            tabcontent = $(evt.currentTarget).closest("ul").parent().find(".tabcontent");
            tabcontent.each(function (index, element) {
                $(element).css("display", "none");
            });
            tablinks = $(evt.currentTarget).closest("ul").parent().find(".tablinks");
            tablinks.each(function (index, element) {
                $(element).removeClass("active");
            });
            $('#' + tabName).css("display", "block");
            $(evt.currentTarget).addClass("active");
        };
        ContentManager.prototype.getCurrentlyVisible = function () {
            return this.currentlyVisible;
        };
        ;
        return ContentManager;
    }());
    content.ContentManager = ContentManager;
    ;
})(content || (content = {}));
var Content;
(function (Content) {
    var FormHelper = (function () {
        function FormHelper(elementId, replaceIds) {
            this.observedElement = $('#' + elementId);
            this.replaceIds = replaceIds;
            if (this.checkIds()) {
                this.getForm();
            }
        }
        FormHelper.prototype.action = function (html) {
            var parsed = $(html);
            for (var _i = 0, _a = this.replaceIds; _i < _a.length; _i++) {
                var id = _a[_i];
                $('#' + id).replaceWith(parsed.find('#' + id));
            }
        };
        FormHelper.prototype.init = function () {
            var _this_1 = this;
            var _this = this;
            this.observedElement.change(function () {
                var data = _this_1.form.serialize();
                $.ajax({
                    url: _this_1.form.attr('action'),
                    type: _this_1.form.attr('method'),
                    data: data,
                    success: function (data) {
                        _this.action(data);
                    }
                });
            });
        };
        FormHelper.prototype.getForm = function () {
            this.form = this.observedElement.closest('form');
        };
        FormHelper.prototype.checkIds = function () {
            if (!this.observedElement) {
                console.error("ObservedElement " + this.observedElement + " does not exist.");
                return false;
            }
            if (!this.replaceIds) {
                console.error("Replace Ids is empty.");
                console.error(this.replaceIds);
                return false;
            }
            return true;
        };
        return FormHelper;
    }());
    Content.FormHelper = FormHelper;
})(Content || (Content = {}));
var display;
(function (display) {
    var Infobox = (function () {
        function Infobox() {
        }
        Infobox.showInfobox = function (event, offsetX, offsetY) {
            var parent = event.currentTarget;
            if (!parent) {
                return;
            }
            var infobox = $(parent).find('.infobox');
            infobox.css('display', 'block');
            infobox.css('left', event.pageX + offsetX);
            infobox.css('top', event.pageY + offsetY);
        };
        Infobox.hideInfobox = function (event) {
            var parent = event.currentTarget;
            if (!parent) {
                return;
            }
            var infobox = $(parent).find('.infobox');
            infobox.css('display', 'none');
        };
        Infobox.isElementInViewport = function (el) {
            if (typeof jQuery === "function" && el instanceof jQuery) {
                var child = el.children[0];
            }
            var rect = child.getBoundingClientRect();
            return (rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth));
        };
        return Infobox;
    }());
    display.Infobox = Infobox;
})(display || (display = {}));
var formatting;
(function (formatting) {
    formatting.formats = null;
    function getFormats(callback) {
        if (formatting.formats != null) {
            callback();
            return;
        }
        var json = '{"1":{"color":"000080","tag":"","style":"","code":"1"},"2":{"color":"00B000","tag":"","style":"","code":"2"},"3":{"color":"00B0B0","tag":"","style":"","code":"3"},"4":{"color":"B00000","tag":"","style":"","code":"4"},"5":{"color":"B000CC","tag":"","style":"","code":"5"},"6":{"color":"B0B000","tag":"","style":"","code":"6"},"7":{"color":"B0B0B0","tag":"","style":"","code":"7"},"8":{"color":"DDFFBB","tag":"","style":"","code":"8"},"9":{"color":"0066CC","tag":"","style":"","code":"9"},"G":{"color":"C20000","tag":"","style":"","code":"G"},"L":{"color":"A30000","tag":"","style":"","code":"L"},"K":{"color":"940000","tag":"","style":"","code":"K"},"J":{"color":"850000","tag":"","style":"","code":"J"},"F":{"color":"660000","tag":"","style":"","code":"F"},"D":{"color":"470000","tag":"","style":"","code":"D"},"n":{"color":"","tag":"br","style":"","code":"n"},"i":{"color":"","tag":"i","style":"","code":"i"},"¬":{"color":"","tag":"pre","style":"","code":"¬"},"b":{"color":"","tag":"strong","style":"","code":"b"},"H":{"color":"","tag":"span","style":"navhi","code":"H"},"c":{"color":"","tag":"center","style":"","code":"c"},"t":{"color":"F8DB83","tag":"","style":"","code":"t"},"T":{"color":"6b563f","tag":"","style":"","code":"T"},"g":{"color":"aaff99","tag":"","style":"","code":"g"},"v":{"color":"C4CBFF","tag":"","style":"","code":"v"},"V":{"color":"9A5BEE","tag":"","style":"","code":"V"},"R":{"color":"800080","tag":"","style":"","code":"R"},"r":{"color":"EEBBEE","tag":"","style":"","code":"r"},"q":{"color":"FF9900","tag":"","style":"","code":"q"},"Q":{"color":"FF6600","tag":"","style":"","code":"Q"},"~":{"color":"222222","tag":"","style":"","code":"~"},")":{"color":"999999","tag":"","style":"","code":")"},"&":{"color":"FFFFFF","tag":"","style":"","code":"&"},"^":{"color":"FFFF00","tag":"","style":"","code":"^"},"%":{"color":"FF00FF","tag":"","style":"","code":"%"},"$":{"color":"FF0000","tag":"","style":"","code":"$"},"#":{"color":"00FFFF","tag":"","style":"","code":"#"},"@":{"color":"00FF00","tag":"","style":"","code":"@"},"!":{"color":"003399","tag":"","style":"","code":"!"},"Ä":{"color":"440022","tag":"","style":"","code":"Ä"},"Ö":{"color":"660030","tag":"","style":"","code":"Ö"},"ú":{"color":"003333","tag":"","style":"","code":"ú"},"À":{"color":"38610B","tag":"","style":"","code":"À"},"Ò":{"color":"003300","tag":"","style":"","code":"Ò"},"ó":{"color":"1B2A0A","tag":"","style":"","code":"ó"},"á":{"color":"243B0B","tag":"","style":"","code":"á"},"é":{"color":"5a3982","tag":"","style":"","code":"é"},"è":{"color":"39185a","tag":"","style":"","code":"è"},"ù":{"color":"101907","tag":"","style":"","code":"ù"},"à":{"color":"EB30BC","tag":"","style":"","code":"à"},"ò":{"color":"C55091","tag":"","style":"","code":"ò"},"ü":{"color":"66FF66","tag":"","style":"","code":"ü"},"ä":{"color":"9F88FF","tag":"","style":"","code":"ä"},"ö":{"color":"FFA4FF","tag":"","style":"","code":"ö"},"€":{"color":"D783A3","tag":"","style":"","code":"€"},":":{"color":"CE5970","tag":"","style":"","code":":"},"|":{"color":"A13F72","tag":"","style":"","code":"|"},"P":{"color":"FA8474","tag":"","style":"","code":"P"},"]":{"color":"777777","tag":"","style":"","code":"]"},"A":{"color":"85715B","tag":"","style":"","code":"A"},"C":{"color":"6C7B8B","tag":"","style":"","code":"C"},";":{"color":"7BDF9C","tag":"","style":"","code":";"},"?":{"color":"339966","tag":"","style":"","code":"?"},"k":{"color":"E0FFFF","tag":"","style":"","code":"k"},"_":{"color":"4C044C","tag":"","style":"","code":"_"},"Y":{"color":"FF3E96","tag":"","style":"","code":"Y"},".":{"color":"71FAFD","tag":"","style":"","code":"."},"=":{"color":"c71585","tag":"","style":"","code":"="},"*":{"color":"00cc66","tag":"","style":"","code":"*"},"[":{"color":"444444","tag":"","style":"","code":"["},"(":{"color":"cccccc","tag":"","style":"","code":"("},"j":{"color":"66cc00","tag":"","style":"","code":"j"},"d":{"color":"669900","tag":"","style":"","code":"d"},"O":{"color":"457A00","tag":"","style":"","code":"O"},"Z":{"color":"035900","tag":"","style":"","code":"Z"},"u":{"color":"dB6Edb","tag":"","style":"","code":"u"},"h":{"color":"a3a3ff","tag":"","style":"","code":"h"},"E":{"color":"7373FF","tag":"","style":"","code":"E"},"B":{"color":"4747FF","tag":"","style":"","code":"B"},"o":{"color":"0085FF","tag":"","style":"","code":"o"},"m":{"color":"00c2FF","tag":"","style":"","code":"m"},"l":{"color":"87CEFF","tag":"","style":"","code":"l"},"I":{"color":"18C3DE","tag":"","style":"","code":"I"},"s":{"color":"00D1D1","tag":"","style":"","code":"s"},"+":{"color":"009191","tag":"","style":"","code":"+"},"y":{"color":"AB8F70","tag":"","style":"","code":"y"},"a":{"color":"85553C","tag":"","style":"","code":"a"},"S":{"color":"B56642","tag":"","style":"","code":"S"},"f":{"color":"D48561","tag":"","style":"","code":"f"},"W":{"color":"FFC47E","tag":"","style":"","code":"W"},"X":{"color":"FFFFCC","tag":"","style":"","code":"X"},"x":{"color":"ffff99","tag":"","style":"","code":"x"},"M":{"color":"FFd700","tag":"","style":"","code":"M"},"U":{"color":"FFBF26","tag":"","style":"","code":"U"},"w":{"color":"FF4A00","tag":"","style":"","code":"w"},"p":{"color":"FF4A00","tag":"","style":"","code":"p"},"e":{"color":"FF1F00","tag":"","style":"","code":"e"},"N":{"color":"E00000","tag":"","style":"","code":"N"},"{":{"color":"e0e0e0","tag":"","style":"","code":"{"},"Ü":{"color":"8A023F","tag":"","style":"","code":"Ü"}}';
        formatting.formats = JSON.parse(json);
        callback();
    }
    formatting.getFormats = getFormats;
})(formatting || (formatting = {}));
var formatting;
(function (formatting) {
    var Formatter = (function () {
        function Formatter() {
        }
        Formatter.prototype.applyFormats = function () {
            var body = document.body;
            this.iterateNodes(body);
        };
        ;
        Formatter.prototype.iterateNodes = function (node) {
            this.getFormats(node);
        };
        ;
        Formatter.prototype.getFormats = function (node) {
            var _this = this;
            formatting.getFormats(function () {
                _this.formats = formatting.formats;
                _this.iterateNodesAfterFormat(node);
            });
        };
        Formatter.prototype.iterateNodesAfterFormat = function (node) {
            if (typeof node.nodeType == 'undefined') {
                return;
            }
            if (node.hasChildNodes()) {
                var children = Array.prototype.slice.call(node.childNodes);
                for (var _i = 0, children_1 = children; _i < children_1.length; _i++) {
                    var child = children_1[_i];
                    this.iterateNodes(child);
                }
            }
            if (node.nodeType == 3) {
                this.handleTextNode(node);
            }
        };
        Formatter.prototype.handleTextNode = function (node) {
            var text = this.getText(node);
            if (text.indexOf('`') == -1) {
                return;
            }
            var tree = new formatting.TextTree(text);
            var _this = this;
            tree.setFormats(this.formats);
            tree.buildTree();
            var dom = tree.getRealRoot().buildDOM();
            _this.insert(node, dom);
        };
        ;
        Formatter.prototype.insert = function (node, dom) {
            if (dom.children.length == 0) {
                return;
            }
            $(node).replaceWith($(dom.children));
        };
        ;
        Formatter.prototype.getText = function (node) {
            var text = node.nodeValue.replace(/^\s+|[\n\r]|\s+$/g, '').trim();
            return text;
        };
        ;
        Formatter.prototype.decodeText = function (text) {
            var txtArea = document.createElement("textarea");
            txtArea.innerHTML = text;
            return txtArea.innerText;
        };
        ;
        Formatter.prototype.encodeText = function (text) {
            return $('<div/>').text(text).html();
        };
        ;
        return Formatter;
    }());
    formatting.Formatter = Formatter;
})(formatting || (formatting = {}));
var formatting;
(function (formatting) {
    var FormatNode = (function () {
        function FormatNode() {
            this.parentNode = null;
            this.children = [];
            this.text = "";
            this.format = null;
        }
        FormatNode.prototype.appendChild = function (child) {
            this.children.push(child);
            child.parentNode = this;
        };
        ;
        FormatNode.prototype.buildDOM = function () {
            var root = this.createRoot();
            for (var _i = 0, _a = this.children; _i < _a.length; _i++) {
                var node = _a[_i];
                root.appendChild(node.buildDOM());
            }
            return root;
        };
        ;
        FormatNode.prototype.createRoot = function () {
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
        ;
        FormatNode.prototype.getLastChild = function () {
            if (this.children.length == 0) {
                return null;
            }
            return this.children[this.children.length - 1];
        };
        ;
        FormatNode.prototype.getParentNode = function () {
            return this.parentNode;
        };
        ;
        FormatNode.prototype.getFormat = function () {
            return this.format;
        };
        ;
        FormatNode.prototype.setFormat = function (format) {
            this.format = format;
        };
        ;
        FormatNode.prototype.getText = function () {
            return this.text;
        };
        ;
        FormatNode.prototype.setText = function (text) {
            this.text = text;
        };
        ;
        return FormatNode;
    }());
    formatting.FormatNode = FormatNode;
})(formatting || (formatting = {}));
var formatting;
(function (formatting) {
    var Preview = (function () {
        function Preview(input, view) {
            this.requestThreshold = 500;
            this.checkThreshold = 200;
            this.view = view;
            this.input = input;
            this.lastActive = Date.now();
            var _this = this;
            setInterval(_this.getPreview, this.checkThreshold, this);
            $(input).keyup(function () {
                _this.update();
            });
        }
        Preview.prototype.update = function () {
            this.lastActive = Date.now();
        };
        Preview.prototype.getPreview = function (_this) {
            var text = '';
            if (Date.now() - _this.lastActive - _this.requestThreshold < 0) {
                return;
            }
            if (typeof _this.input.value !== 'undefined') {
                text = _this.input.value.trim();
            }
            else {
                text = _this.input.getValue().trim();
            }
            if (_this.lastText == text) {
                return;
            }
            var con = new Ajax.AjaxConnector();
            _this.lastText = text;
            con.getData("format/preview", { text: text }, function (preview) {
                _this.view.innerHTML = preview;
            });
        };
        return Preview;
    }());
    formatting.Preview = Preview;
})(formatting || (formatting = {}));
var formatting;
(function (formatting) {
    var TextTree = (function () {
        function TextTree(text) {
            this.delimiter = '`';
            this.root = new formatting.FormatNode();
            this.textArray = text.match(new RegExp(this.delimiter + "?.{1}[^" + this.delimiter + "]*", "g"));
        }
        TextTree.prototype.setFormats = function (formats) {
            this.formats = formats;
        };
        TextTree.prototype.buildTree = function () {
            for (var _i = 0, _a = this.textArray; _i < _a.length; _i++) {
                var text = _a[_i];
                var code = this.getCode(text);
                if (code != "") {
                    if (this.formats[code] != null && this.formats[code].color) {
                        this.addColorNode(text);
                    }
                    else {
                        this.addNonColorNode(text);
                    }
                }
                else {
                    this.root.appendChild(this.buildNode(text, false));
                }
            }
        };
        TextTree.prototype.addNonColorNode = function (text) {
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
        ;
        TextTree.prototype.addColorNode = function (text) {
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
        ;
        TextTree.prototype.moveUp = function () {
            if (this.root.getParentNode() == null) {
                return;
            }
            this.root = this.root.getParentNode();
        };
        ;
        TextTree.prototype.moveDown = function () {
            var lastChild = this.root.getLastChild();
            if (lastChild == null) {
                return;
            }
            if (this.isBreakTag(lastChild)) {
                return;
            }
            this.root = lastChild;
        };
        ;
        TextTree.prototype.isBreakTag = function (node) {
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
        ;
        TextTree.prototype.codeIsOpen = function (code) {
            var current = this.getLastInsertedNode();
            while (current != null) {
                if (current.getFormat() != null && current.getFormat().code == code) {
                    return current.getParentNode();
                }
                current = current.getParentNode();
            }
            return null;
        };
        ;
        TextTree.prototype.getLastInsertedNode = function () {
            if (this.root.getLastChild() == null) {
                return this.root;
            }
            return this.root.getLastChild();
        };
        ;
        TextTree.prototype.buildNode = function (text, hasFormat) {
            var node = new formatting.FormatNode();
            if (hasFormat) {
                node.setFormat(this.formats[this.getCode(text)]);
            }
            if (text.indexOf(this.delimiter) != -1) {
                node.setText(text.substring(2));
            }
            else {
                node.setText(text);
            }
            return node;
        };
        ;
        TextTree.prototype.getCode = function (text) {
            if (text.indexOf(this.delimiter) != -1) {
                return text.substring(1, 2);
            }
            return '';
        };
        TextTree.prototype.getRoot = function () {
            return this.root;
        };
        ;
        TextTree.prototype.getRealRoot = function () {
            var root = this.root;
            while (root.getParentNode() != null) {
                root = root.getParentNode();
            }
            return root;
        };
        ;
        TextTree.prototype.getFormats = function () {
            return this.formats;
        };
        ;
        return TextTree;
    }());
    formatting.TextTree = TextTree;
})(formatting || (formatting = {}));
var App;
(function (App) {
    var Location;
    (function (Location) {
        var Bank = (function () {
            function Bank() {
            }
            Bank.changeInteraction = function (path) {
                this.draw = !this.draw;
                $(document.forms.namedItem("bank_form")).attr("action", path);
            };
            Bank.draw = true;
            return Bank;
        }());
        Location.Bank = Bank;
    })(Location = App.Location || (App.Location = {}));
})(App || (App = {}));
var App;
(function (App) {
    var Location;
    (function (Location) {
        var Library = (function () {
            function Library() {
            }
            Library.selectBook = function (element) {
                var id = $(element).attr('data-book');
                if (!id) {
                    return;
                }
                var target = document.getElementById(id);
                target.style.display = target.style.display === "block" ? "none" : "block";
            };
            Library.getBookFromServer = function (element) {
                if (element.value == '0') {
                    return;
                }
                var connection = new Ajax.AjaxConnector();
                connection.getData("book/get", { bookId: element.value }, function (book) {
                    var form = document.forms.namedItem('book_edit');
                    form.elements.namedItem('theme').value = book.theme;
                    form.elements.namedItem('title').value = book.title;
                    form.elements.namedItem('content').value = book.content;
                });
            };
            return Library;
        }());
        Location.Library = Library;
    })(Location = App.Location || (App.Location = {}));
})(App || (App = {}));
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
var mail;
(function (mail) {
    var Messenger = (function () {
        function Messenger() {
            this.messages = {};
            this.charId = -1;
            this.needsUpdate = false;
            this.listRoot = $('#messageList');
            this.importantListRoot = $('#importantMessages');
            this.sentListRoot = $('#sentMessages');
        }
        Messenger.prototype.getMessages = function () {
            var _this = this;
            var con = new Ajax.AjaxConnector();
            con.getData("mail/get/" + this.charId.toString(), {}, function (messages) {
                _this.needsUpdate = true;
                _this.messages = messages;
                _this.updateList();
            });
        };
        Messenger.prototype.updateList = function () {
            this.listRoot.empty();
            this.importantListRoot.empty();
            this.sentListRoot.empty();
            if (this.charId == 0 && !this.needsUpdate) {
                return;
            }
            for (var index in this.messages) {
                var element = this.createMessageElement(this.messages[index]);
                if (this.messages[index].important && !this.messages[index].sent) {
                    this.importantListRoot.append(element);
                }
                else if (this.messages[index].sent) {
                    this.sentListRoot.append(element);
                }
                else {
                    this.listRoot.append(element);
                }
            }
            if (this.listRoot.html().trim() == "") {
                this.listRoot.append($('<li>').html("Du hast keine Nachrichten."));
            }
            if (this.importantListRoot.html().trim() == "") {
                this.importantListRoot.append($('<li>').html("Du hast keine wichtigen Nachrichten."));
            }
            if (this.sentListRoot.html().trim() == "") {
                this.sentListRoot.append($('<li>').html("Du hast keine Nachrichten gesendet."));
            }
            this.needsUpdate = false;
        };
        Messenger.prototype.changeChar = function (id) {
            if (id == this.charId) {
                return;
            }
            this.needsUpdate = true;
            this.messages = {};
            this.charId = id;
            this.getMessages();
        };
        Messenger.prototype.search = function (attempt) {
            if (attempt == "") {
                $("#searchList").html("");
                return;
            }
            var _this = this;
            var con = new Ajax.AjaxConnector();
            con.getData('char/search', { "attempt": attempt }, function (matches) {
                var formatter = new formatting.Formatter();
                var list = document.getElementById("searchList");
                list.innerHTML = "";
                for (var _i = 0, matches_1 = matches; _i < matches_1.length; _i++) {
                    var char = matches_1[_i];
                    var li = document.createElement('li');
                    li.setAttribute("data-target", char.name);
                    $(li).html(char.label);
                    li.onclick = _this.setTarget;
                    formatter.iterateNodes(li);
                    list.appendChild(li);
                }
            });
        };
        Messenger.prototype.setTarget = function (event) {
            var target = null;
            if ($(event.target).attr('data-target')) {
                target = $(event.target).attr('data-target');
            }
            else {
                target = $(event.target).parents('*[data-target]').attr('data-target');
            }
            document.forms.namedItem('messageForm').setAttribute('target', target);
        };
        Messenger.prototype.changeVisibility = function (event) {
            var target = $(event.target).attr("data-target");
            var element = $("#" + target);
            var display = element.css("display");
            element.css("display", display == "none" ? "" : "none");
            var con = new Ajax.AjaxConnector();
            con.getData('mail/read/' + target.substring(4), {}, function (data) {
                if (data['message']) {
                    element.parent('li').find('img').attr('src', '/images/mail_icons/mail_read_ico.png');
                }
            });
        };
        Messenger.prototype.moveToImportant = function (id) {
            for (var index in this.messages) {
                if (id == this.messages[index].id) {
                    this.messages[index].important = true;
                    this.sendUpdatedMessage(this.messages[index]);
                    return;
                }
            }
        };
        Messenger.prototype.moveToUnimportant = function (id) {
            for (var index in this.messages) {
                if (id == this.messages[index].id) {
                    this.messages[index].important = false;
                    this.sendUpdatedMessage(this.messages[index]);
                    return;
                }
            }
        };
        Messenger.prototype.deleteMessage = function (id) {
            document.forms.namedItem('del_form').elements['id'].value = id;
            var _this = this;
            this.messages = {};
            var con = new Ajax.AjaxConnector();
            con.submitForm(document.forms.namedItem('del_form'), function () {
                _this.getMessages();
            });
        };
        Messenger.prototype.answerMessage = function (id) {
            for (var index in this.messages) {
                if (id == this.messages[index].id) {
                    var message = this.messages[index];
                }
            }
            document.forms.namedItem('messageForm').elements['target'].value = message.sender;
            document.forms.namedItem('messageForm').elements['subject'].value = "AW: " + message.subject;
            document.forms.namedItem('messageForm').elements['content'].value = "-----------------------------------<br />\n\rHistorie<br />\n\r" + message.subject
                + "<br \>\n" + message.content;
        };
        Messenger.prototype.createMessageElement = function (message) {
            var li = $('<li>');
            var _this = this;
            if (!message.sent) {
                if (message.important) {
                    li.append($('<img>').attr({ src: '/images/mail_icons/mail_important_ico.png' }).addClass('mailimage').css({ float: "left", width: "40px", height: "40px" }));
                }
                else if (message.read) {
                    li.append($('<img>').attr({ src: '/images/mail_icons/mail_read_ico.png' }).addClass('mailimage').css({ float: "left", width: "40px", height: "40px" }));
                }
                else {
                    li.append($('<img>').attr({ src: '/images/mail_icons/mail_unread_ico.png' }).addClass('mailimage').css({ float: "left", width: "40px", height: "40px" }));
                }
                li.append($('<button>').html('L&ouml;schen').css('float', 'right').attr("data-target", message.id).click(function (data, event) {
                    var id = parseInt($(event.target).attr("data-target"));
                    _this.deleteMessage(id);
                }));
                if (message.important) {
                    li.append($('<button>').html('Unwichtig').css('float', 'right').attr("data-target", message.id).click(function (data, event) {
                        var id = parseInt($(event.target).attr("data-target"));
                        _this.moveToUnimportant(id);
                    }));
                }
                else {
                    li.append($('<button>').html('Wichtig').css('float', 'right').attr("data-target", message.id).click(function (data, event) {
                        var id = parseInt($(event.target).attr("data-target"));
                        _this.moveToImportant(id);
                    }));
                }
                li.append($('<button>').html('Antworten').css('float', 'right').attr("data-target", message.id).click(function (data, event) {
                    var id = parseInt($(event.target).attr("data-target"));
                    _this.answerMessage(id);
                }));
            }
            else {
                li.append($('<img>').attr({ src: '/images/mail_icons/mail_sent_ico.png' }).addClass('mailimage').css({ float: "left", width: "40px", height: "40px" }));
            }
            li.append($('<span>').css({ 'font-size': "1.2em", 'font-weight': "bold" })
                .attr("data-target", "msg_" + message.id)
                .html(message.subject + (message.read || message.sent ? '' : ' (neu)'))
                .addClass('clickable')
                .click(function (data, event) {
                _this.changeVisibility(event);
            }))
                .append('<br>');
            li.append($('<span>').html(message.sender + " <span style='font-size: 1.2em'>»</span> " + message.addressee))
                .css('font-weight', !message.read && !message.sent ? "bold" : "normal").append($('<br />'));
            li.append($('<span>').html(message.created))
                .css('font-weight', !message.read && !message.sent ? "bold" : "normal");
            li.append($('<br />'));
            li.append($('<p>')
                .attr("id", "msg_" + message.id)
                .html(message.content)
                .css("display", "none"));
            li.append('<br />').append('<br />');
            return li;
        };
        Messenger.prototype.sendUpdatedMessage = function (message) {
            document.forms.namedItem('imp_form').elements['id'].value = message.id;
            this.needsUpdate = true;
            var _this = this;
            var con = new Ajax.AjaxConnector();
            con.submitForm(document.forms.namedItem('imp_form'), function () {
                _this.updateList();
            });
        };
        return Messenger;
    }());
    mail.Messenger = Messenger;
    var MailLink = (function () {
        function MailLink() {
            this.count = -1;
            this.firstTimeLoad = true;
            this.link = $("#maillink");
            var _this = this;
            this.audio = document.createElement("audio");
            $(this.audio).attr("src", getBaseURL() + "resources/audio/mail.mp3");
            $(document.body).append(_this.getAudio());
        }
        MailLink.prototype.getFirstTimeLoad = function () {
            return this.firstTimeLoad;
        };
        MailLink.prototype.setFirstTimeLoad = function (load) {
            this.firstTimeLoad = load;
        };
        MailLink.prototype.getAudio = function () {
            return this.audio;
        };
        MailLink.prototype.setAudio = function (audio) {
            this.audio = audio;
        };
        MailLink.prototype.setCount = function (count) {
            if (!this.link) {
                return;
            }
            this.count = count;
            this.link.html("Mail" + (count > 0 ? " (Neu: " + count + ")" : ""));
        };
        MailLink.prototype.getCount = function () {
            return this.count;
        };
        return MailLink;
    }());
    mail.MailLink = MailLink;
})(mail || (mail = {}));
var navigation;
(function (navigation) {
    var KeyNavigator = (function () {
        function KeyNavigator() {
            this.map = {};
            var that = this;
            $('.nav').each(function (index, value) {
                that.addNav(value);
            });
            $('body').on("keydown", function (event) {
                that.navigate(event);
            });
        }
        KeyNavigator.prototype.addNav = function (nav) {
            if (nav.children.length === 0) {
                nav.innerHTML = this.prepareNav(nav);
            }
            else {
                for (var key = 0; key < nav.children.length; key++) {
                    var html = this.prepareNav(nav.children[key]);
                    if (html !== null) {
                        nav.children[key].innerHTML = html;
                        return;
                    }
                }
            }
        };
        KeyNavigator.prototype.prepareNav = function (nav) {
            var text = nav.textContent;
            if (text) {
                for (var index = 0; index < text.length; index++) {
                    var char = text.charAt(index);
                    if (!(char.toLowerCase() in this.map) && isNaN(char)) {
                        this.map[char.toLowerCase()] = nav;
                        return text.slice(0, index) + "<span style='text-decoration: underline;'>"
                            + char + "</span>" + text.slice(index + 1);
                    }
                }
            }
            return text;
        };
        KeyNavigator.prototype.navigate = function (event) {
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
        };
        return KeyNavigator;
    }());
    navigation.KeyNavigator = KeyNavigator;
})(navigation || (navigation = {}));
var navigation;
(function (navigation) {
    function navigate(locId, params) {
        var navigator = new navigation.Navigator();
        var array = {
            "locId": "" + locId
        };
        var paramArray = params.split(";");
        for (var _i = 0, paramArray_1 = paramArray; _i < paramArray_1.length; _i++) {
            var entry = paramArray_1[_i];
            var keyValue = entry.split('=');
            array[keyValue[0]] = keyValue[1];
        }
        navigator.createForm('', array);
        navigator.navigate();
    }
    navigation.navigate = navigate;
    function enterHouse(locId) {
        var form = document.createElement("form");
        form.method = "POST";
        var op = document.createElement("input");
        op.type = "hidden";
        op.name = "op";
        op.value = "house_enter";
        var id = document.createElement("input");
        id.type = "hidden";
        id.name = "entrance";
        id.value = "" + locId;
        var uuidInput = document.createElement("input");
        uuidInput.type = "hidden";
        uuidInput.name = "uuid";
        uuidInput.value = uuid;
        form.appendChild(op);
        form.appendChild(id);
        form.appendChild(uuidInput);
        document.body.appendChild(form);
        form.submit();
    }
    navigation.enterHouse = enterHouse;
    function relocate(target) {
        var navigator = new navigation.Navigator();
        navigator.createForm(target, {});
        navigator.navigate();
    }
    navigation.relocate = relocate;
})(navigation || (navigation = {}));
var navigation;
(function (navigation) {
    var Navigator = (function () {
        function Navigator() {
        }
        Navigator.prototype.createForm = function (target, fields) {
            var form = document.createElement('form');
            form.method = "POST";
            if (target != null && target != '') {
                form.action = target;
            }
            var id = document.createElement("input");
            id.type = "hidden";
            id.name = "uuid";
            id.value = uuid;
            form.appendChild(id);
            for (var key in fields) {
                var input = document.createElement('input');
                input.type = "hidden";
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            }
            this.form = form;
        };
        Navigator.prototype.navigate = function () {
            document.body.appendChild(this.form);
            this.form.submit();
        };
        return Navigator;
    }());
    navigation.Navigator = Navigator;
})(navigation || (navigation = {}));
var Sorting;
(function (Sorting) {
    var DateSortPolicy = (function () {
        function DateSortPolicy() {
        }
        DateSortPolicy.prototype.sort = function (value, compare, reverse) {
            var dateA = new Date(value);
            var dateB = new Date(compare);
            return reverse * (dateA > dateB ? -1 : dateA < dateB ? 1 : 0);
        };
        return DateSortPolicy;
    }());
    Sorting.DateSortPolicy = DateSortPolicy;
})(Sorting || (Sorting = {}));
var Sorting;
(function (Sorting) {
    var NumberSortPolicy = (function () {
        function NumberSortPolicy() {
        }
        NumberSortPolicy.prototype.sort = function (value, compare, reverse) {
            var result = 0;
            var floatValue = parseFloat(value);
            var floatCompare = parseFloat(compare);
            if (floatValue < floatCompare) {
                result = -1;
            }
            if (floatValue > floatCompare) {
                result = 1;
            }
            return reverse * result;
        };
        return NumberSortPolicy;
    }());
    Sorting.NumberSortPolicy = NumberSortPolicy;
})(Sorting || (Sorting = {}));
var Sorting;
(function (Sorting) {
    var StandardSortPolicy = (function () {
        function StandardSortPolicy() {
        }
        StandardSortPolicy.prototype.sort = function (value, compare, reverse) {
            if (isNaN(Number(value)) || isNaN(Number(value))) {
                return reverse * value.localeCompare(compare);
            }
            return reverse * Number(value) - Number(compare);
        };
        return StandardSortPolicy;
    }());
    Sorting.StandardSortPolicy = StandardSortPolicy;
})(Sorting || (Sorting = {}));
var Sorting;
(function (Sorting) {
    var TableSorter = (function () {
        function TableSorter() {
        }
        TableSorter.prototype.makeAllSortable = function (parent) {
            parent = parent || document.body;
            var tables = parent.getElementsByTagName('table');
            var index = tables.length;
            while (--index >= 0) {
                this.makeSortable(tables[index]);
            }
        };
        TableSorter.prototype.makeSortable = function (table) {
            var _this = this;
            var th = table.tHead, i;
            th && (th = th.rows[0]) && (th = th.cells);
            if (th)
                i = th.length;
            else
                return;
            while (--i >= 0)
                (function (i) {
                    var dir = 1;
                    th[i].addEventListener('click', function () { _this.sortTable(table, i, (dir = 1 - dir)); });
                }(i));
        };
        TableSorter.prototype.sortTable = function (table, col, reverse) {
            var tb = table.tBodies[0], tr = Array.prototype.slice.call(tb.rows, 0), index, sortPolicy;
            reverse = -((+reverse) || -1);
            if ($(tr[0].cells[col]).attr('data-type') == 'date') {
                sortPolicy = new Sorting.DateSortPolicy();
                tr = tr.sort(function (a, b) {
                    var value = $(a.cells[col]).attr('data-value');
                    var compare = $(b.cells[col]).attr('data-value');
                    return sortPolicy.sort(value, compare, reverse);
                });
            }
            else {
                if ($(tr[0].cells[col]).attr('data-type') == 'number') {
                    sortPolicy = new Sorting.NumberSortPolicy();
                }
                else {
                    sortPolicy = new Sorting.StandardSortPolicy();
                }
                tr = tr.sort(function (a, b) {
                    var value = a.cells[col].textContent.trim();
                    var compare = b.cells[col].textContent.trim();
                    return sortPolicy.sort(value, compare, reverse);
                });
            }
            for (index = 0; index < tr.length; ++index) {
                tb.appendChild(tr[index]);
            }
        };
        return TableSorter;
    }());
    Sorting.TableSorter = TableSorter;
})(Sorting || (Sorting = {}));
