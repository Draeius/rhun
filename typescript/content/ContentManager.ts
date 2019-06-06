module content {
    export class ContentManager {
        private currentlyVisible;

        constructor(currVisible) {
            this.currentlyVisible = currVisible;
        };

        public switchContent(id) {
            if (id === this.currentlyVisible) {
                return;
            }
            var newElement = $('#' + id);
            if (typeof this.currentlyVisible === "undefined" || this.currentlyVisible === null) {
                newElement.velocity("fadeIn", 1500);
            } else {
                $('#' + this.currentlyVisible).velocity("fadeOut", 900, function() {
                    newElement.velocity("fadeIn", 1500);
                });
            }
            this.currentlyVisible = id;
        };

        public static openTab(evt: MouseEvent, tabName: string) {
            // Declare all variables
            var tabcontent: JQuery,
                tablinks: JQuery;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = $(evt.currentTarget).closest("ul").parent().find(".tabcontent");
            tabcontent.each((index, element) => {
                $(element).css("display", "none");
            });

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = $(evt.currentTarget).closest("ul").parent().find(".tablinks");
            tablinks.each((index, element) => {
                $(element).removeClass("active");
            });

            // Show the current tab, and add an "active" class to the link that opened the tab
            $('#' + tabName).css("display", "block");
            $(evt.currentTarget).addClass("active");
        }

        public getCurrentlyVisible() {
            return this.currentlyVisible;
        };
    };
}


