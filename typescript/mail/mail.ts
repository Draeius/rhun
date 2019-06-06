module mail {

    interface Message {
        id: number;
        sender: string;
        addressee: string;
        read: boolean;
        subject: string;
        created: string;
        content: string;
        important: boolean;
        sent: string;
    }

    export class Messenger {
        private listRoot: JQuery;
        private importantListRoot: JQuery;
        private sentListRoot: JQuery;
        private messages: {[index: number]: Message} = {};
        private charId: number = -1;
        private needsUpdate = false;

        constructor() {
            this.listRoot = $('#messageList');
            this.importantListRoot = $('#importantMessages');
            this.sentListRoot = $('#sentMessages');
        }

        public getMessages(): void {
            var _this = this;

            var con = new Ajax.AjaxConnector();
            con.getData("mail/get/" + this.charId.toString(), {}, (messages: Message[]) => {
                _this.needsUpdate = true;
                _this.messages = messages;
                _this.updateList();
            });

        }

        public updateList() {
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
                } else if (this.messages[index].sent) {
                    this.sentListRoot.append(element);
                } else {
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

        public changeChar(id: number): void {
            if (id == this.charId) {
                return;
            }
            this.needsUpdate = true;
            this.messages = {};
            this.charId = id;
            this.getMessages();
        }

        public search(attempt: string) {
            if (attempt == "") {
                $("#searchList").html("");
                return;
            }
            var _this = this;
            var con = new Ajax.AjaxConnector();
            con.getData('char/search', {"attempt": attempt}, function (matches) {
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

        public setTarget(event: MouseEvent) {
            var target = null;
            if ($(event.target).attr('data-target')) {
                target = $(event.target).attr('data-target');
            } else {
                target = $(event.target).parents('*[data-target]').attr('data-target');
            }
            document.forms['messageForm']['target'].value = target;
        }

        public changeVisibility(event: MouseEvent) {
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

        public moveToImportant(id: number) {
            for (var index in this.messages) {
                if (id == this.messages[index].id) {
                    this.messages[index].important = true;
                    this.sendUpdatedMessage(this.messages[index]);
                    return;
                }
            }
        }

        public moveToUnimportant(id: number) {
            for (var index in this.messages) {
                if (id == this.messages[index].id) {
                    this.messages[index].important = false;
                    this.sendUpdatedMessage(this.messages[index]);
                    return;
                }
            }
        }

        public deleteMessage(id: number) {
            document.forms.namedItem('del_form').elements['id'].value = id;
            var _this = this;
            this.messages = {};

            var con = new Ajax.AjaxConnector();
            con.submitForm(document.forms.namedItem('del_form'), () => {
                _this.getMessages();
            });
        }

        public answerMessage(id: number) {
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

        private createMessageElement(message: Message): JQuery {
            var li = $('<li>');
            var _this = this;

            if (!message.sent) {
                if (message.important) {
                    li.append($('<img>').attr({src: '/images/mail_icons/mail_important_ico.png'}).addClass('mailimage').css({float: "left", width: "40px", height: "40px"}));
                } else if (message.read) {
                    li.append($('<img>').attr({src: '/images/mail_icons/mail_read_ico.png'}).addClass('mailimage').css({float: "left", width: "40px", height: "40px"}));
                } else {
                    li.append($('<img>').attr({src: '/images/mail_icons/mail_unread_ico.png'}).addClass('mailimage').css({float: "left", width: "40px", height: "40px"}));
                }
                li.append($('<button>').html('L&ouml;schen').css('float', 'right').attr("data-target", message.id).click((event: MouseEvent) => {
                    var id = parseInt($(event.target).attr("data-target"));
                    _this.deleteMessage(id);
                }));
                if (message.important) {
                    li.append($('<button>').html('Unwichtig').css('float', 'right').attr("data-target", message.id).click((event: MouseEvent) => {
                        var id = parseInt($(event.target).attr("data-target"));
                        _this.moveToUnimportant(id);
                    }));
                } else {
                    li.append($('<button>').html('Wichtig').css('float', 'right').attr("data-target", message.id).click((event: MouseEvent) => {
                        var id = parseInt($(event.target).attr("data-target"));
                        _this.moveToImportant(id);
                    }));
                }
                li.append($('<button>').html('Antworten').css('float', 'right').attr("data-target", message.id).click((event: MouseEvent) => {
                    var id = parseInt($(event.target).attr("data-target"));
                    _this.answerMessage(id);
                }));
            } else {
                li.append($('<img>').attr({src: '/images/mail_icons/mail_sent_ico.png'}).addClass('mailimage').css({float: "left", width: "40px", height: "40px"}));
            }
            li.append(
                $('<span>').css({'font-size': "1.2em", 'font-weight': "bold"})
                    .attr("data-target", "msg_" + message.id)
                    .html(message.subject + (message.read || message.sent ? '' : ' (neu)'))
                    .addClass('clickable')
                    .click((event: MouseEvent) => {
                        _this.changeVisibility(event);
                    }))
                .append('<br>');
            li.append($('<span>').html(message.sender + " <span style='font-size: 1.2em'>»</span> " + message.addressee))
                .css('font-weight', !message.read && !message.sent ? "bold" : "normal").append($('<br />'));
            li.append($('<span>').html(message.created))
                .css('font-weight', !message.read && !message.sent ? "bold" : "normal");
            li.append($('<br />'));
            li.append(
                $('<p>')
                    .attr("id", "msg_" + message.id)
                    .html(message.content)
                    .css("display", "none"));
            li.append('<br />').append('<br />');
            return li;
        }

        private sendUpdatedMessage(message: Message) {
            document.forms.namedItem('imp_form').elements['id'].value = message.id;
            this.needsUpdate = true;
            var _this = this;

            var con = new Ajax.AjaxConnector();
            con.submitForm(document.forms.namedItem('imp_form'), () => {
                _this.updateList();
            });
        }
    }

    export class MailLink {
        private link: JQuery;
        private count: number = -1;
        private audio: HTMLAudioElement;
        private firstTimeLoad: boolean = true;

        constructor() {
            this.link = $("#maillink");
            var _this = this;

            this.audio = document.createElement("audio");
            $(this.audio).attr("src", getBaseURL() + "resources/audio/mail.mp3");
            $(document.body).append(_this.getAudio());

            //            setInterval(() => {
            //                _this.checkMessages();
            //            }, 10000);
        }

        //        public checkMessages() {
        //            if (!this.link) {
        //                return;
        //            }
        //            var con = new Ajax.AjaxConnector();
        //            var _this = this;
        //            con.getData(Ajax.Operation.getAllMessages, { peek: 'true' }, (data) => {
        //                var current = $(data).length;
        //                if (current > _this.getCount()) {
        //                    _this.setCount(current);
        //                    console.log(_this.getFirstTimeLoad());
        //                    if (!_this.getFirstTimeLoad()) {
        //                        console.log('check');
        //                        _this.getAudio().currentTime = 0;
        //                        _this.getAudio().play();
        //                    }
        //                }
        //                _this.setFirstTimeLoad(false);
        //            });
        //        }

        public getFirstTimeLoad() {
            return this.firstTimeLoad;
        }

        public setFirstTimeLoad(load: boolean) {
            this.firstTimeLoad = load;
        }

        public getAudio() {
            return this.audio;
        }

        public setAudio(audio: HTMLAudioElement) {
            this.audio = audio;
        }

        public setCount(count: number) {
            if (!this.link) {
                return;
            }
            this.count = count;
            this.link.html("Mail" + (count > 0 ? " (Neu: " + count + ")" : ""));
        }

        public getCount() {
            return this.count;
        }
    }
}