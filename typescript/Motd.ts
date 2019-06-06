
module Util {

    class BroadcastCanvas {
        private page: number = 0;

        public earlierMotCT() {
            this.page++;
            var con = new Ajax.AjaxConnector();
            let _this = this;
            con.getData('motd/show/' + this.page, {coding: true}, function (data: any) {
                if (data['error']) {
                    console.debug(data['error']);
                    _this.page--;
                } else {
                    document.getElementById('motct_content').innerHTML = data['content'];
                }
            });
        }

        public laterMotCT() {
            if (this.page <= 0) {
                return;
            }
            this.page--;
            let con = new ajax.AjaxConnector();
            let _this = this;
            con.getData('motd/show/' + this.page, {coding: true}, function (data: any) {
                if (data['error']) {
                    console.debug(data['error']);
                    _this.page++;
                } else {
                    document.getElementById('motct_content').innerHTML = data['content'];
                }
            });
        }
    }

}
