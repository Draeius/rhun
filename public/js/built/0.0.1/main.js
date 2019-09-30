var Util;
(function (Util) {
    class BroadcastCanvas {
        constructor() {
            this.page = 0;
        }
        earlierMotCT() {
            this.page++;
            var con = new Ajax.AjaxConnector();
            let _this = this;
            con.getData('motd/show/' + this.page, { coding: 'true' }, function (data) {
                if (data['error']) {
                    console.debug(data['error']);
                    _this.page--;
                }
                else {
                    document.getElementById('motct_content').innerHTML = data['content'];
                }
            });
        }
        laterMotCT() {
            if (this.page <= 0) {
                return;
            }
            this.page--;
            let con = new Ajax.AjaxConnector();
            let _this = this;
            con.getData('motd/show/' + this.page, { coding: 'true' }, function (data) {
                if (data['error']) {
                    console.debug(data['error']);
                    _this.page++;
                }
                else {
                    document.getElementById('motct_content').innerHTML = data['content'];
                }
            });
        }
    }
    Util.BroadcastCanvas = BroadcastCanvas;
})(Util || (Util = {}));
var rhun;
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
        let previewId = $(this).attr('data-preview');
        let element = document.getElementById(previewId);
        if (element) {
            new formatting.Preview(this, element);
        }
    });
}
$(document).ready(() => {
    var keyNavigator = new navigation.KeyNavigator();
    var sorter = new Sorting.TableSorter();
    sorter.makeAllSortable(null);
    initPreview();
});
var Util;
(function (Util) {
    class ImageMap {
        constructor(map, image) {
            this.previousWidth = 1500;
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
        resize() {
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
        addToOnload() {
            window.addEventListener("load", this.resize, false);
        }
    }
    Util.ImageMap = ImageMap;
})(Util || (Util = {}));
var Ajax;
(function (Ajax) {
    class AjaxConnector {
        constructor() {
            this.baseUrl = this.getBaseURL();
        }
        getData(url, params, callback) {
            this.submit({
                type: "GET",
                url: url,
                data: params,
            }, callback);
        }
        postData(url, params, callback) {
            this.submit({
                type: "POST",
                url: url,
                data: params,
            }, callback);
        }
        submit(request, callback) {
            $.post(request)
                .done(function (data) {
                Logging.Logger.debug("submit data:");
                Logging.Logger.debug(data);
                if (data['ERR'] && data['ERR'] == 'logout') {
                    $(document.body).css('color', '#2E2E2E');
                }
                else {
                    if (callback) {
                        callback(data);
                    }
                }
            })
                .fail(function (data) {
                Logging.Logger.error("failed to load data. URL: " + request.url);
                $(document.body).append(data.responseText);
            });
        }
        submitForm(form, callback) {
            Logging.Logger.debug("form submit action: " + form.action);
            this.submit({
                type: "POST",
                url: form.action,
                data: $(form).serialize(),
            }, callback);
            return false;
        }
        getBaseURL() {
            return window.location.protocol + "//" + window.location.host + "/";
        }
    }
    Ajax.AjaxConnector = AjaxConnector;
})(Ajax || (Ajax = {}));
var Ajax;
(function (Ajax) {
    class PostConnector extends Ajax.AjaxConnector {
        constructor(uuid) {
            super();
            this.uuid = uuid;
        }
        getNewOOC(lastPostId, limit, page, callback) {
            this.getNew(PostConnector.OOC_GET_URL, lastPostId, limit, page, callback);
        }
        getNewPosts(lastPostId, limit, page, callback) {
            this.getNew(PostConnector.POST_GET_URL, lastPostId, limit, page, callback);
        }
        getNew(url, lastPostId, limit, page, callback) {
            this.getData(url, {
                lastId: lastPostId.toString(),
                limit: limit.toString(),
                page: page.toString()
            }, callback);
        }
    }
    PostConnector.OOC_GET_URL = "ooc/get";
    PostConnector.POST_GET_URL = "post/get/";
    Ajax.PostConnector = PostConnector;
})(Ajax || (Ajax = {}));
class Queue {
    constructor() {
        this._store = [];
    }
    push(val) {
        this._store.push(val);
    }
    pop() {
        return this._store.shift();
    }
    peek() {
        return this._store[0];
    }
    peekLast() {
        return this._store[this._store.length - 1];
    }
    length() {
        return this._store.length;
    }
    [Symbol.iterator]() {
        let pointer = 0;
        let components = this._store;
        return {
            next() {
                if (pointer < components.length) {
                    return {
                        done: false,
                        value: components[pointer++]
                    };
                }
                else {
                    return {
                        done: true,
                        value: null
                    };
                }
            }
        };
    }
}
var Logging;
(function (Logging) {
    Logging.SHOW_DEBUG = true;
    Logging.SHOW_LOG = true;
    Logging.SHOW_WARNING = true;
    class Logger {
        static log(message) {
            if (Logging.SHOW_LOG) {
                console.log(message);
            }
        }
        static debug(message) {
            if (Logging.SHOW_DEBUG) {
                console.debug(message);
            }
        }
        static warning(message) {
            if (Logging.SHOW_WARNING) {
                console.warn(message);
            }
        }
        static error(message) {
            console.error(message);
        }
    }
    Logging.Logger = Logger;
})(Logging || (Logging = {}));
var Biography;
(function (Biography) {
    class AnimationUtils {
        static rotate(element, degree, duration) {
            var rotateZ = degree ? degree : 360;
            return $(element).velocity({
                rotateZ: rotateZ
            }, {
                delay: 0,
                easing: degree !== 0 ? "swing" : "linear",
                duration: duration,
                loop: degree === 0
            });
        }
        ;
        static shake(element, times) {
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
        }
        ;
        static blink(options) {
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
                if (random <= config.probability) {
                    $(this).velocity({ opacity: 1 }, config.fadeIn, function () {
                        $(this).velocity({ opacity: 0 }, config.fadeOut);
                    });
                }
            });
            window.setTimeout(function () {
                AnimationUtils.blink(config);
            }, config.delay);
        }
        ;
        static floatToPosition(element, startTop, startLeft, callback) {
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
        }
        ;
    }
    Biography.AnimationUtils = AnimationUtils;
    ;
})(Biography || (Biography = {}));
var bio;
(function (bio) {
    class Downfall {
        constructor(options) {
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
        start() {
            var _this = this;
            this.intervalId = setInterval(function () { _this.run(_this.config, _this.documentWidth, _this.documentHeight); }, this.config.newOn);
        }
        stop() {
            if (typeof this.intervalId !== "undefined") {
                clearInterval(this.intervalId);
            }
        }
        getRandomArrayEntry(array) {
            return array[Math.floor(Math.random() * array.length)];
        }
        run(config, docWidth, docHeight) {
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
        }
    }
    bio.Downfall = Downfall;
})(bio || (bio = {}));
var content;
(function (content) {
    class ContentManager {
        constructor(currVisible) {
            this.currentlyVisible = currVisible;
        }
        ;
        switchContent(id) {
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
        }
        ;
        static openTab(evt, tabName) {
            var tabcontent, tablinks;
            tabcontent = $(evt.currentTarget).closest("ul").parent().find(".tabcontent");
            tabcontent.each((index, element) => {
                $(element).css("display", "none");
            });
            tablinks = $(evt.currentTarget).closest("ul").parent().find(".tablinks");
            tablinks.each((index, element) => {
                $(element).removeClass("active");
            });
            $('#' + tabName).css("display", "block");
            $(evt.currentTarget).addClass("active");
        }
        getCurrentlyVisible() {
            return this.currentlyVisible;
        }
        ;
    }
    content.ContentManager = ContentManager;
    ;
})(content || (content = {}));
var Content;
(function (Content) {
    class FormHelper {
        constructor(elementId, replaceIds) {
            this.observedElement = $('#' + elementId);
            this.replaceIds = replaceIds;
            if (this.checkIds()) {
                this.getForm();
            }
        }
        action(html) {
            let parsed = $(html);
            for (let id of this.replaceIds) {
                $('#' + id).replaceWith(parsed.find('#' + id));
            }
        }
        init() {
            var _this = this;
            this.observedElement.change(() => {
                let data = this.form.serialize();
                $.ajax({
                    url: this.form.attr('action'),
                    type: this.form.attr('method'),
                    data: data,
                    success: (data) => {
                        _this.action(data);
                    }
                });
            });
        }
        getForm() {
            this.form = this.observedElement.closest('form');
        }
        checkIds() {
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
        }
    }
    Content.FormHelper = FormHelper;
})(Content || (Content = {}));
var Content;
(function (Content) {
    class PostArea {
        constructor(display, input, timer = 3000, ooc) {
            this.input = input;
            this.display = display;
            this.timer = timer;
            this.ooc = ooc;
            this.posts = new Queue();
            this.postCon = new Ajax.PostConnector(rhun.uuid);
            this.initialize();
        }
        sendPost(form) {
            this.postCon.submitForm(form, this.checkUpdate);
            return false;
        }
        reload() {
            this.loadPosts(0);
        }
        checkUpdate() {
            let lastId = this.posts.peekLast().id;
            if (!lastId) {
                lastId = 0;
            }
            this.loadPosts(lastId);
        }
        loadPosts(lastPostId) {
            if (this.ooc) {
                this.postCon.getNewOOC(lastPostId, rhun.oocLimit, this.page, this.doUpdate);
            }
            else {
                this.postCon.getNewPosts(lastPostId, rhun.postLimit, this.page, this.doUpdate);
            }
        }
        doUpdate(postData) {
            this.pageCount = postData.pages;
            for (let post of postData.posts) {
                this.posts.push(post);
                this.processPost();
            }
        }
        processPost() {
            let element = $(this.posts.peekLast().text);
            element.css("display", "none");
            $(this.display).append(element);
            if (this.deleteOldest()) {
                $(this.display).children().first().fadeOut(200, () => {
                    element.fadeIn(200);
                });
            }
            else {
                element.fadeIn(200);
            }
        }
        deleteOldest() {
            if (this.ooc && this.posts.length() > rhun.oocLimit) {
                this.posts.pop();
                return true;
            }
            else if (!this.ooc && this.posts.length() > rhun.postLimit) {
                this.posts.pop();
                return true;
            }
            return false;
        }
        setPage(page) {
            if (page > this.pageCount) {
                page = this.pageCount;
            }
            if (page < 1) {
                page = 1;
            }
            this.page = page;
        }
        nextPage() {
            this.setPage(this.page + 1);
        }
        previousPage() {
            this.setPage(this.page - 1);
        }
        initialize() {
            let _this = this;
            if (!this.ooc) {
                $("#post_previous").click(() => {
                    _this.previousPage();
                    _this.reload();
                });
                $("#post_earlier").click(() => {
                    _this.nextPage();
                    _this.reload();
                });
            }
            else {
                $("#ooc_previous").click(() => {
                    _this.previousPage();
                    _this.reload();
                });
                $("#ooc_earlier").click(() => {
                    _this.nextPage();
                    _this.reload();
                });
            }
        }
    }
    Content.PostArea = PostArea;
})(Content || (Content = {}));
var display;
(function (display) {
    class Infobox {
        static showInfobox(event, offsetX, offsetY) {
            var parent = event.currentTarget;
            if (!parent) {
                return;
            }
            var infobox = $(parent).find('.infobox');
            infobox.css('display', 'block');
            infobox.css('left', event.pageX + offsetX);
            infobox.css('top', event.pageY + offsetY);
        }
        static hideInfobox(event) {
            var parent = event.currentTarget;
            if (!parent) {
                return;
            }
            var infobox = $(parent).find('.infobox');
            infobox.css('display', 'none');
        }
        static isElementInViewport(el) {
            if (typeof jQuery === "function" && el instanceof jQuery) {
                var child = el.children[0];
            }
            var rect = child.getBoundingClientRect();
            return (rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth));
        }
    }
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
    class Formatter {
        applyFormats() {
            var body = document.body;
            this.iterateNodes(body);
        }
        ;
        iterateNodes(node) {
            this.getFormats(node);
        }
        ;
        getFormats(node) {
            var _this = this;
            formatting.getFormats(function () {
                _this.formats = formatting.formats;
                _this.iterateNodesAfterFormat(node);
            });
        }
        iterateNodesAfterFormat(node) {
            if (typeof node.nodeType == 'undefined') {
                return;
            }
            if (node.hasChildNodes()) {
                var children = Array.prototype.slice.call(node.childNodes);
                for (var child of children) {
                    this.iterateNodes(child);
                }
            }
            if (node.nodeType == 3) {
                this.handleTextNode(node);
            }
        }
        handleTextNode(node) {
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
        }
        ;
        insert(node, dom) {
            if (dom.children.length == 0) {
                return;
            }
            $(node).replaceWith($(dom.children));
        }
        ;
        getText(node) {
            var text = node.nodeValue.replace(/^\s+|[\n\r]|\s+$/g, '').trim();
            return text;
        }
        ;
        decodeText(text) {
            var txtArea = document.createElement("textarea");
            txtArea.innerHTML = text;
            return txtArea.innerText;
        }
        ;
        encodeText(text) {
            return $('<div/>').text(text).html();
        }
        ;
    }
    formatting.Formatter = Formatter;
})(formatting || (formatting = {}));
var formatting;
(function (formatting) {
    class FormatNode {
        constructor() {
            this.parentNode = null;
            this.children = [];
            this.text = "";
            this.format = null;
        }
        appendChild(child) {
            this.children.push(child);
            child.parentNode = this;
        }
        ;
        buildDOM() {
            var root = this.createRoot();
            for (var node of this.children) {
                root.appendChild(node.buildDOM());
            }
            return root;
        }
        ;
        createRoot() {
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
        }
        ;
        getLastChild() {
            if (this.children.length == 0) {
                return null;
            }
            return this.children[this.children.length - 1];
        }
        ;
        getParentNode() {
            return this.parentNode;
        }
        ;
        getFormat() {
            return this.format;
        }
        ;
        setFormat(format) {
            this.format = format;
        }
        ;
        getText() {
            return this.text;
        }
        ;
        setText(text) {
            this.text = text;
        }
        ;
    }
    formatting.FormatNode = FormatNode;
})(formatting || (formatting = {}));
var formatting;
(function (formatting) {
    class Preview {
        constructor(input, view) {
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
        update() {
            this.lastActive = Date.now();
        }
        getPreview(_this) {
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
        }
    }
    formatting.Preview = Preview;
})(formatting || (formatting = {}));
var formatting;
(function (formatting) {
    class TextTree {
        constructor(text) {
            this.delimiter = '`';
            this.root = new formatting.FormatNode();
            this.textArray = text.match(new RegExp(this.delimiter + "?.{1}[^" + this.delimiter + "]*", "g"));
        }
        setFormats(formats) {
            this.formats = formats;
        }
        buildTree() {
            for (var text of this.textArray) {
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
        }
        addNonColorNode(text) {
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
        }
        ;
        addColorNode(text) {
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
        }
        ;
        moveUp() {
            if (this.root.getParentNode() == null) {
                return;
            }
            this.root = this.root.getParentNode();
        }
        ;
        moveDown() {
            var lastChild = this.root.getLastChild();
            if (lastChild == null) {
                return;
            }
            if (this.isBreakTag(lastChild)) {
                return;
            }
            this.root = lastChild;
        }
        ;
        isBreakTag(node) {
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
        }
        ;
        codeIsOpen(code) {
            var current = this.getLastInsertedNode();
            while (current != null) {
                if (current.getFormat() != null && current.getFormat().code == code) {
                    return current.getParentNode();
                }
                current = current.getParentNode();
            }
            return null;
        }
        ;
        getLastInsertedNode() {
            if (this.root.getLastChild() == null) {
                return this.root;
            }
            return this.root.getLastChild();
        }
        ;
        buildNode(text, hasFormat) {
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
        }
        ;
        getCode(text) {
            if (text.indexOf(this.delimiter) != -1) {
                return text.substring(1, 2);
            }
            return '';
        }
        getRoot() {
            return this.root;
        }
        ;
        getRealRoot() {
            var root = this.root;
            while (root.getParentNode() != null) {
                root = root.getParentNode();
            }
            return root;
        }
        ;
        getFormats() {
            return this.formats;
        }
        ;
    }
    formatting.TextTree = TextTree;
})(formatting || (formatting = {}));
var App;
(function (App) {
    var Location;
    (function (Location) {
        class Bank {
            static changeInteraction(path) {
                this.draw = !this.draw;
                $(document.forms.namedItem("bank_form")).attr("action", path);
            }
        }
        Bank.draw = true;
        Location.Bank = Bank;
    })(Location = App.Location || (App.Location = {}));
})(App || (App = {}));
var App;
(function (App) {
    var Location;
    (function (Location) {
        class Library {
            static selectBook(element) {
                var id = $(element).attr('data-book');
                if (!id) {
                    return;
                }
                let target = document.getElementById(id);
                target.style.display = target.style.display === "block" ? "none" : "block";
            }
            static getBookFromServer(element) {
                if (element.value == '0') {
                    return;
                }
                var connection = new Ajax.AjaxConnector();
                connection.getData("book/get", { bookId: element.value }, function (book) {
                    let form = document.forms.namedItem('book_edit');
                    form.elements.namedItem('theme').value = book.theme;
                    form.elements.namedItem('title').value = book.title;
                    form.elements.namedItem('content').value = book.content;
                });
            }
        }
        Location.Library = Library;
    })(Location = App.Location || (App.Location = {}));
})(App || (App = {}));
var mail;
(function (mail) {
    class Messenger {
        constructor() {
            this.messages = {};
            this.charId = -1;
            this.needsUpdate = false;
            this.listRoot = $('#messageList');
            this.importantListRoot = $('#importantMessages');
            this.sentListRoot = $('#sentMessages');
        }
        getMessages() {
            var _this = this;
            var con = new Ajax.AjaxConnector();
            con.getData("mail/get/" + this.charId.toString(), {}, (messages) => {
                _this.needsUpdate = true;
                _this.messages = messages;
                _this.updateList();
            });
        }
        updateList() {
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
        }
        changeChar(id) {
            if (id == this.charId) {
                return;
            }
            this.needsUpdate = true;
            this.messages = {};
            this.charId = id;
            this.getMessages();
        }
        search(attempt) {
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
                for (var char of matches) {
                    var li = document.createElement('li');
                    li.setAttribute("data-target", char.name);
                    $(li).html(char.label);
                    li.onclick = _this.setTarget;
                    formatter.iterateNodes(li);
                    list.appendChild(li);
                }
            });
        }
        setTarget(event) {
            var target = null;
            if ($(event.target).attr('data-target')) {
                target = $(event.target).attr('data-target');
            }
            else {
                target = $(event.target).parents('*[data-target]').attr('data-target');
            }
            document.forms.namedItem('messageForm').setAttribute('target', target);
        }
        changeVisibility(event) {
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
        }
        moveToImportant(id) {
            for (var index in this.messages) {
                if (id == this.messages[index].id) {
                    this.messages[index].important = true;
                    this.sendUpdatedMessage(this.messages[index]);
                    return;
                }
            }
        }
        moveToUnimportant(id) {
            for (var index in this.messages) {
                if (id == this.messages[index].id) {
                    this.messages[index].important = false;
                    this.sendUpdatedMessage(this.messages[index]);
                    return;
                }
            }
        }
        deleteMessage(id) {
            document.forms.namedItem('del_form').elements['id'].value = id;
            var _this = this;
            this.messages = {};
            var con = new Ajax.AjaxConnector();
            con.submitForm(document.forms.namedItem('del_form'), () => {
                _this.getMessages();
            });
        }
        answerMessage(id) {
            for (var index in this.messages) {
                if (id == this.messages[index].id) {
                    var message = this.messages[index];
                }
            }
            document.forms.namedItem('messageForm').elements['target'].value = message.sender;
            document.forms.namedItem('messageForm').elements['subject'].value = "AW: " + message.subject;
            document.forms.namedItem('messageForm').elements['content'].value = "-----------------------------------<br />\n\rHistorie<br />\n\r" + message.subject
                + "<br \>\n" + message.content;
        }
        createMessageElement(message) {
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
                li.append($('<button>').html('L&ouml;schen').css('float', 'right').attr("data-target", message.id).click((data, event) => {
                    var id = parseInt($(event.target).attr("data-target"));
                    _this.deleteMessage(id);
                }));
                if (message.important) {
                    li.append($('<button>').html('Unwichtig').css('float', 'right').attr("data-target", message.id).click((data, event) => {
                        var id = parseInt($(event.target).attr("data-target"));
                        _this.moveToUnimportant(id);
                    }));
                }
                else {
                    li.append($('<button>').html('Wichtig').css('float', 'right').attr("data-target", message.id).click((data, event) => {
                        var id = parseInt($(event.target).attr("data-target"));
                        _this.moveToImportant(id);
                    }));
                }
                li.append($('<button>').html('Antworten').css('float', 'right').attr("data-target", message.id).click((data, event) => {
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
                .click((data, event) => {
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
        }
        sendUpdatedMessage(message) {
            document.forms.namedItem('imp_form').elements['id'].value = message.id;
            this.needsUpdate = true;
            var _this = this;
            var con = new Ajax.AjaxConnector();
            con.submitForm(document.forms.namedItem('imp_form'), () => {
                _this.updateList();
            });
        }
    }
    mail.Messenger = Messenger;
    class MailLink {
        constructor() {
            this.count = -1;
            this.firstTimeLoad = true;
            this.link = $("#maillink");
            var _this = this;
            this.audio = document.createElement("audio");
            $(this.audio).attr("src", getBaseURL() + "resources/audio/mail.mp3");
            $(document.body).append(_this.getAudio());
        }
        getFirstTimeLoad() {
            return this.firstTimeLoad;
        }
        setFirstTimeLoad(load) {
            this.firstTimeLoad = load;
        }
        getAudio() {
            return this.audio;
        }
        setAudio(audio) {
            this.audio = audio;
        }
        setCount(count) {
            if (!this.link) {
                return;
            }
            this.count = count;
            this.link.html("Mail" + (count > 0 ? " (Neu: " + count + ")" : ""));
        }
        getCount() {
            return this.count;
        }
    }
    mail.MailLink = MailLink;
})(mail || (mail = {}));
var navigation;
(function (navigation) {
    class KeyNavigator {
        constructor() {
            this.map = {};
            var that = this;
            $('.nav').each((index, value) => {
                that.addNav(value);
            });
            $('body').on("keydown", (event) => {
                that.navigate(event);
            });
        }
        addNav(nav) {
            if (nav.children.length === 0) {
                nav.innerHTML = this.prepareNav(nav);
            }
            else {
                for (let key = 0; key < nav.children.length; key++) {
                    let html = this.prepareNav(nav.children[key]);
                    if (html !== null) {
                        nav.children[key].innerHTML = html;
                        return;
                    }
                }
            }
        }
        prepareNav(nav) {
            let text = nav.textContent;
            if (text) {
                for (let index = 0; index < text.length; index++) {
                    let char = text.charAt(index);
                    if (!(char.toLowerCase() in this.map) && isNaN(char)) {
                        this.map[char.toLowerCase()] = nav;
                        return text.slice(0, index) + "<span style='text-decoration: underline;'>"
                            + char + "</span>" + text.slice(index + 1);
                    }
                }
            }
            return text;
        }
        navigate(event) {
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
        for (var entry of paramArray) {
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
    class Navigator {
        createForm(target, fields) {
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
        }
        navigate() {
            document.body.appendChild(this.form);
            this.form.submit();
        }
    }
    navigation.Navigator = Navigator;
})(navigation || (navigation = {}));
var Sorting;
(function (Sorting) {
    class DateSortPolicy {
        sort(value, compare, reverse) {
            var dateA = new Date(value);
            var dateB = new Date(compare);
            return reverse * (dateA > dateB ? -1 : dateA < dateB ? 1 : 0);
        }
    }
    Sorting.DateSortPolicy = DateSortPolicy;
})(Sorting || (Sorting = {}));
var Sorting;
(function (Sorting) {
    class NumberSortPolicy {
        sort(value, compare, reverse) {
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
        }
    }
    Sorting.NumberSortPolicy = NumberSortPolicy;
})(Sorting || (Sorting = {}));
var Sorting;
(function (Sorting) {
    class StandardSortPolicy {
        sort(value, compare, reverse) {
            if (isNaN(Number(value)) || isNaN(Number(value))) {
                return reverse * value.localeCompare(compare);
            }
            return reverse * Number(value) - Number(compare);
        }
    }
    Sorting.StandardSortPolicy = StandardSortPolicy;
})(Sorting || (Sorting = {}));
var Sorting;
(function (Sorting) {
    class TableSorter {
        makeAllSortable(parent) {
            parent = parent || document.body;
            var tables = parent.getElementsByTagName('table');
            var index = tables.length;
            while (--index >= 0) {
                this.makeSortable(tables[index]);
            }
        }
        makeSortable(table) {
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
        }
        sortTable(table, col, reverse) {
            var tb = table.tBodies[0], tr = Array.prototype.slice.call(tb.rows, 0), index, sortPolicy;
            reverse = -((+reverse) || -1);
            if ($(tr[0].cells[col]).attr('data-type') == 'date') {
                sortPolicy = new Sorting.DateSortPolicy();
                tr = tr.sort((a, b) => {
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
                tr = tr.sort((a, b) => {
                    var value = a.cells[col].textContent.trim();
                    var compare = b.cells[col].textContent.trim();
                    return sortPolicy.sort(value, compare, reverse);
                });
            }
            for (index = 0; index < tr.length; ++index) {
                tb.appendChild(tr[index]);
            }
        }
    }
    Sorting.TableSorter = TableSorter;
})(Sorting || (Sorting = {}));
