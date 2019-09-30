
namespace Content {

    export interface UpdateData {
        posts: Entity.Post[];
        pages: number;
    }

    export class PostArea {

        private input: HTMLElement;

        private display: HTMLElement;

        private timer: number;

        private ooc: boolean;

        private postCon: Ajax.PostConnector;

        private posts: Queue<Entity.Post>;

        private page: number;

        private pageCount: number;

        /**
         * @param display Das Element, in dem die Posts angezeigt werden
         * @param input Das Inputelement aus dem der Text des Posts gelesen wird
         * @param timer Die Anzahl an Millisekunden nach denen ein Update ausgeführt wird
         * @param ooc Gibt an, ob es sich um einen OOC handelt
         * @param previewOutput Das Element in dem die Vorschau ausgegeben wird.
         */
        public constructor(display: HTMLElement, input: HTMLElement, timer: number = 3000, ooc: boolean) {
            this.input = input;
            this.display = display;
            this.timer = timer;
            this.ooc = ooc;
            this.posts = new Queue<Entity.Post>();
            this.postCon = new Ajax.PostConnector(rhun.uuid);
            this.initialize();
        }

        public sendPost(form: HTMLFormElement) {
            this.postCon.submitForm(form, this.checkUpdate);
            return false;
        }

        public reload() {
            this.loadPosts(0);
        }

        public checkUpdate() {
            let lastId = this.posts.peekLast().id;
            if (!lastId) {
                lastId = 0;
            }
            this.loadPosts(lastId);
        }

        private loadPosts(lastPostId: number) {
            if (this.ooc) {
                this.postCon.getNewOOC(lastPostId, rhun.oocLimit, this.page, this.doUpdate);
            } else {
                this.postCon.getNewPosts(lastPostId, rhun.postLimit, this.page, this.doUpdate);
            }
        }

        public doUpdate(postData: UpdateData) {
            this.pageCount = postData.pages;

            for (let post of postData.posts) {
                this.posts.push(post);
                this.processPost();
            }
        }

        private processPost() {
            let element = $(this.posts.peekLast().text);
            element.css("display", "none");
            $(this.display).append(element);
            if (this.deleteOldest()) {
                $(this.display).children().first().fadeOut(200, () => {
                    element.fadeIn(200);
                });
            } else {
                element.fadeIn(200);
            }
        }

        private deleteOldest(): boolean {
            if (this.ooc && this.posts.length() > rhun.oocLimit) {
                this.posts.pop();
                return true;
            } else if (!this.ooc && this.posts.length() > rhun.postLimit) {
                this.posts.pop();
                return true;
            }
            return false;
        }

        public setPage(page: number) {
            if (page > this.pageCount) {
                page = this.pageCount;
            }
            if (page < 1) {
                page = 1;
            }
            this.page = page;
        }

        public nextPage() {
            this.setPage(this.page + 1);
        }

        public previousPage() {
            this.setPage(this.page - 1);
        }

        private initialize() {
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
            } else {
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

}