module entities {

    export interface House {
        id: number
        entranceId: number;
        maxRooms: number;
        title: string;
        status: string;
    }
}