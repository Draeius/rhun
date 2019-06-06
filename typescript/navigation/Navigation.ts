
module navigation {
    /**
        * Sends a request to the server to navigate to the location with the given id
        * @param locId The location id
        */
    export function navigate(locId: number, params: string) {
        var navigator = new navigation.Navigator();
        var array: { [key: string]: string } = {
            "locId": "" + locId
        };
        var paramArray = params.split(";");
        for (var entry of paramArray) {
            var keyValue = entry.split('=');
            array[keyValue[0]] = keyValue[1];
        }
        navigator.createForm('', array);
        navigator.navigate();
    }


    export function enterHouse(locId: number) {
        //create form to send
        var form = document.createElement("form");
        form.method = "POST";

        //create input with location id
        var op: HTMLInputElement = document.createElement("input");
        op.type = "hidden";
        op.name = "op";
        op.value = "house_enter";

        //create input with location id
        var id: HTMLInputElement = document.createElement("input");
        id.type = "hidden";
        id.name = "entrance";
        id.value = "" + locId;

        //append uuid
        var uuidInput: HTMLInputElement = document.createElement("input");
        uuidInput.type = "hidden";
        uuidInput.name = "uuid";
        uuidInput.value = uuid;

        //append input
        form.appendChild(op);
        form.appendChild(id);
        form.appendChild(uuidInput);

        document.body.appendChild(form);

        //send the whole stuff
        form.submit();
    }

    export function relocate(target: string) {
        var navigator = new navigation.Navigator();
        navigator.createForm(target, {});
        navigator.navigate();
    }
}