
module Ajax {
    export interface AjaxResponse {
        message: string;
    }

    export class AjaxConnector {

        private baseUrl: string;

        constructor() {
            this.baseUrl = this.getBaseURL();
        }

        public getPosts(callback: Function, ooc: boolean = true, page: number = 1, limit: number = 10) {
            if (ooc) {
                var urlPart = "ooc/get"
            } else {
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
        }

        public getData(url: string, params: {[key: string]: string}, callback: Function): void {
            var url = this.baseUrl + url;
            //            url = this.addParams(url, params);
            console.log(url);
            $.post({
                type: "POST",
                url: url,
                data: params,
            })
                .done(function (data) {
                    if (data['ERR'] && data['ERR'] == 'logout') {
                        $(document.body).css('color', '#2E2E2E');
                    } else {
                        callback(data);
                    }
                })
                .fail(function (data) {
                    console.log("failed to get data. URL: " + url);
                    $(document.body).append(data.responseText);
                });
        }

        public submitForm(form: HTMLFormElement, callback: Function) {
            console.log(form.action);
            $.ajax({
                type: "POST",
                url: form.action,
                data: $(form).serialize(), // serializes the form's elements.
                success: function (data) {
                    //                    console.log(data);
                    if (callback) {
                        callback(data); // show response from the php script.
                    }
                },
                error: function (data) {
                    $(document.body).append(data.responseText);
                }
            });
            return false;
        }

        private getBaseURL() {
            return window.location.protocol + "//" + window.location.host + "/";
        }

    }
}