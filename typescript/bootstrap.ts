var rhun: UserSettings;
//array with the formats
var formats: formatting.FormatArray;

function getBaseURL(): string {
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
    Biography.AnimationUtils.floatToPosition(<HTMLElement> elem.get(0), randTopStart, -300, function () {
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

function initPosts() {
    $('[data-posts]').each(function () {
        let data = $(this).attr('data-posts').split(";");
        let form = <HTMLFormElement>document.getElementById(data[1]);
        let ooc = data[0] == "ooc";
        let submitButton = document.getElementById(data[2]);
        let area = new Content.PostArea(this, ooc);
        $(submitButton).click(function () {
            area.sendPost(form);
        });
    });
}

//apply formats
$(document).ready(() => {
    var keyNavigator = new navigation.KeyNavigator();
    var sorter = new Sorting.TableSorter();
    sorter.makeAllSortable(null);
    initPreview();
    initPosts();
});
