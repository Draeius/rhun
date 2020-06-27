
namespace Ajax {

    export interface AjaxResponse {
        message: string;
    }

    export class AjaxConnector {

        private baseUrl: string;

        constructor() {
            this.baseUrl = this.getBaseURL();
        }

        public getData(url: string, params: {[key: string]: string}, callback: Function): void {
            this.submit({
                type: "GET",
                url: url,
                data: params,
            }, callback);
        }

        public postData(url: string, params: {[key: string]: string}, callback: Function): void {
            this.submit({
                type: "POST",
                url: url,
                data: params,
            }, callback)
        }

        private submit(request: JQuery.UrlAjaxSettings, callback: Function) {
            $.post(request)
                .done(function (data) {
                    Logging.Logger.debug("submit data:");
                    Logging.Logger.debug(data);
                    if (data['ERR'] && data['ERR'] == 'logout') {
                        $(document.body).css('color', '#2E2E2E');
                    } else {
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

        public submitForm(form: HTMLFormElement, callback: Function) {
            Logging.Logger.debug("form submit action: " + form.action);
            console.log(this.getBaseURL() + form.action)
            this.submit({
                type: "POST",
                url: form.action,
                data: $(form).serialize(), // serializes the form's elements.
            }, callback);
            return false;
        }

        public getBaseURL() {
            return window.location.protocol + "//" + window.location.host + "/";
        }

    }
}