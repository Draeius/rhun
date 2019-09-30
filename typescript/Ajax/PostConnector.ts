
namespace Ajax {

    export class PostConnector extends AjaxConnector {

        public static readonly OOC_GET_URL: string = "ooc/get";
        public static readonly POST_GET_URL: string = "post/get/";

        private uuid: string;

        public constructor(uuid: string) {
            super();
            this.uuid = uuid;
        }

        public getNewOOC(lastPostId: number, limit: number, page: number, callback: Function) {
            this.getNew(PostConnector.OOC_GET_URL, lastPostId, limit, page, callback);
        }

        public getNewPosts(lastPostId: number, limit: number, page: number, callback: Function) {
            this.getNew(PostConnector.POST_GET_URL, lastPostId, limit, page, callback);
        }

        private getNew(url: string, lastPostId: number, limit: number, page: number, callback: Function) {
            this.getData(url, {
                lastId: lastPostId.toString(),
                limit: limit.toString(),
                page: page.toString()
            }, callback);
        }
    }

}