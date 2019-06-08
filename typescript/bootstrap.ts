//make the variable known to the system. 
var uuid: string;

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


//apply formats
$(document).ready(() => {
    var keyNavigator = new navigation.KeyNavigator();
    var sorter = new Sorting.TableSorter();
    sorter.makeAllSortable(null);
});
