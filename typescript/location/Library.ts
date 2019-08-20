
namespace App.Location {

    interface Book {
        theme: string;
        title: string;
        content: string;
    }

    export class Library {

        public static selectBook(element: HTMLElement) {
            var id = $(element).attr('data-book');
            if (!id) {
                return;
            }
            let target = document.getElementById(id);
            target.style.display = target.style.display === "block" ? "none" : "block";
        }

        public static getBookFromServer(element: HTMLSelectElement) {
            if (element.value == '0') {
                return;
            }
            var connection = new Ajax.AjaxConnector();
            connection.getData("book/get", {bookId: element.value}, function (book: Book) {
                let form = document.forms.namedItem('book_edit');
                (<HTMLSelectElement> form.elements.namedItem('theme')).value = book.theme;
                (<HTMLSelectElement> form.elements.namedItem('title')).value = book.title;
                (<HTMLSelectElement> form.elements.namedItem('content')).value = book.content;
            });
        }
    }
}