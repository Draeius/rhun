
module editor {

    export function setData(form: HTMLFormElement, data: { [index: string]: string; }) {
        for (var key in data) {
            if (form.elements[key]) {
                setFieldValue(form.elements[key], data[key]);
            }
        }
    }

    function setFieldValue(field: HTMLElement, value: string) {
        if (field instanceof HTMLTextAreaElement) {
            $(field).html(value);
        } else if (field instanceof HTMLSelectElement) {
            $(field).find("option").each(function () {
                $(this).prop("selected", $(this).attr("value") == value);
            });
        } else if (field instanceof HTMLInputElement) {
            var fieldType = field.type;
            if (!fieldType) {
                return;
            }
            if (fieldType == "text" || fieldType == "number") {
                field.value = value;
            }
        }

    }
}