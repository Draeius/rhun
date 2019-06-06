
module Logging {

    export const SHOW_DEBUG: boolean = true;
    export const SHOW_LOG: boolean = true;
    export const SHOW_WARNING: boolean = true;

    export class Logger {

        public static log(message: any): void {
            if (SHOW_LOG) {
                console.log(message);
            }
        }

        public static debug(message: any): void {
            if (SHOW_DEBUG) {
                console.debug(message);
            }
        }

        public static warning(message: any): void {
            if (SHOW_WARNING) {
                console.warn(message);
            }
        }

        public static error(message: any): void {
            console.error(message);
        }

    }
}
