
module formatting {
    export var formats: { [tag: string]: Format } = null;
    
    export interface Format {
        code: string;
        color?: string;
        style?: string;
        tag?: string;
    }

    export interface FormatArray {
        [key: string]: Format;
    }

    export function getFormats(callback: Function) {
        if (formatting.formats != null) {
            callback();
            return;
        }
//        var con = new ajax.AjaxConnector();
//        con.getData(ajax.Operation.formats, {}, function (formats: { [tag: string]: Format }){
//            console.log('receive new');
//            formatting.formats = formats;
//            callback();
//        });
//        if (formats) {
//            return formats;
//        }
        var json = '{"1":{"color":"000080","tag":"","style":"","code":"1"},"2":{"color":"00B000","tag":"","style":"","code":"2"},"3":{"color":"00B0B0","tag":"","style":"","code":"3"},"4":{"color":"B00000","tag":"","style":"","code":"4"},"5":{"color":"B000CC","tag":"","style":"","code":"5"},"6":{"color":"B0B000","tag":"","style":"","code":"6"},"7":{"color":"B0B0B0","tag":"","style":"","code":"7"},"8":{"color":"DDFFBB","tag":"","style":"","code":"8"},"9":{"color":"0066CC","tag":"","style":"","code":"9"},"G":{"color":"C20000","tag":"","style":"","code":"G"},"L":{"color":"A30000","tag":"","style":"","code":"L"},"K":{"color":"940000","tag":"","style":"","code":"K"},"J":{"color":"850000","tag":"","style":"","code":"J"},"F":{"color":"660000","tag":"","style":"","code":"F"},"D":{"color":"470000","tag":"","style":"","code":"D"},"n":{"color":"","tag":"br","style":"","code":"n"},"i":{"color":"","tag":"i","style":"","code":"i"},"¬":{"color":"","tag":"pre","style":"","code":"¬"},"b":{"color":"","tag":"strong","style":"","code":"b"},"H":{"color":"","tag":"span","style":"navhi","code":"H"},"c":{"color":"","tag":"center","style":"","code":"c"},"t":{"color":"F8DB83","tag":"","style":"","code":"t"},"T":{"color":"6b563f","tag":"","style":"","code":"T"},"g":{"color":"aaff99","tag":"","style":"","code":"g"},"v":{"color":"C4CBFF","tag":"","style":"","code":"v"},"V":{"color":"9A5BEE","tag":"","style":"","code":"V"},"R":{"color":"800080","tag":"","style":"","code":"R"},"r":{"color":"EEBBEE","tag":"","style":"","code":"r"},"q":{"color":"FF9900","tag":"","style":"","code":"q"},"Q":{"color":"FF6600","tag":"","style":"","code":"Q"},"~":{"color":"222222","tag":"","style":"","code":"~"},")":{"color":"999999","tag":"","style":"","code":")"},"&":{"color":"FFFFFF","tag":"","style":"","code":"&"},"^":{"color":"FFFF00","tag":"","style":"","code":"^"},"%":{"color":"FF00FF","tag":"","style":"","code":"%"},"$":{"color":"FF0000","tag":"","style":"","code":"$"},"#":{"color":"00FFFF","tag":"","style":"","code":"#"},"@":{"color":"00FF00","tag":"","style":"","code":"@"},"!":{"color":"003399","tag":"","style":"","code":"!"},"Ä":{"color":"440022","tag":"","style":"","code":"Ä"},"Ö":{"color":"660030","tag":"","style":"","code":"Ö"},"ú":{"color":"003333","tag":"","style":"","code":"ú"},"À":{"color":"38610B","tag":"","style":"","code":"À"},"Ò":{"color":"003300","tag":"","style":"","code":"Ò"},"ó":{"color":"1B2A0A","tag":"","style":"","code":"ó"},"á":{"color":"243B0B","tag":"","style":"","code":"á"},"é":{"color":"5a3982","tag":"","style":"","code":"é"},"è":{"color":"39185a","tag":"","style":"","code":"è"},"ù":{"color":"101907","tag":"","style":"","code":"ù"},"à":{"color":"EB30BC","tag":"","style":"","code":"à"},"ò":{"color":"C55091","tag":"","style":"","code":"ò"},"ü":{"color":"66FF66","tag":"","style":"","code":"ü"},"ä":{"color":"9F88FF","tag":"","style":"","code":"ä"},"ö":{"color":"FFA4FF","tag":"","style":"","code":"ö"},"€":{"color":"D783A3","tag":"","style":"","code":"€"},":":{"color":"CE5970","tag":"","style":"","code":":"},"|":{"color":"A13F72","tag":"","style":"","code":"|"},"P":{"color":"FA8474","tag":"","style":"","code":"P"},"]":{"color":"777777","tag":"","style":"","code":"]"},"A":{"color":"85715B","tag":"","style":"","code":"A"},"C":{"color":"6C7B8B","tag":"","style":"","code":"C"},";":{"color":"7BDF9C","tag":"","style":"","code":";"},"?":{"color":"339966","tag":"","style":"","code":"?"},"k":{"color":"E0FFFF","tag":"","style":"","code":"k"},"_":{"color":"4C044C","tag":"","style":"","code":"_"},"Y":{"color":"FF3E96","tag":"","style":"","code":"Y"},".":{"color":"71FAFD","tag":"","style":"","code":"."},"=":{"color":"c71585","tag":"","style":"","code":"="},"*":{"color":"00cc66","tag":"","style":"","code":"*"},"[":{"color":"444444","tag":"","style":"","code":"["},"(":{"color":"cccccc","tag":"","style":"","code":"("},"j":{"color":"66cc00","tag":"","style":"","code":"j"},"d":{"color":"669900","tag":"","style":"","code":"d"},"O":{"color":"457A00","tag":"","style":"","code":"O"},"Z":{"color":"035900","tag":"","style":"","code":"Z"},"u":{"color":"dB6Edb","tag":"","style":"","code":"u"},"h":{"color":"a3a3ff","tag":"","style":"","code":"h"},"E":{"color":"7373FF","tag":"","style":"","code":"E"},"B":{"color":"4747FF","tag":"","style":"","code":"B"},"o":{"color":"0085FF","tag":"","style":"","code":"o"},"m":{"color":"00c2FF","tag":"","style":"","code":"m"},"l":{"color":"87CEFF","tag":"","style":"","code":"l"},"I":{"color":"18C3DE","tag":"","style":"","code":"I"},"s":{"color":"00D1D1","tag":"","style":"","code":"s"},"+":{"color":"009191","tag":"","style":"","code":"+"},"y":{"color":"AB8F70","tag":"","style":"","code":"y"},"a":{"color":"85553C","tag":"","style":"","code":"a"},"S":{"color":"B56642","tag":"","style":"","code":"S"},"f":{"color":"D48561","tag":"","style":"","code":"f"},"W":{"color":"FFC47E","tag":"","style":"","code":"W"},"X":{"color":"FFFFCC","tag":"","style":"","code":"X"},"x":{"color":"ffff99","tag":"","style":"","code":"x"},"M":{"color":"FFd700","tag":"","style":"","code":"M"},"U":{"color":"FFBF26","tag":"","style":"","code":"U"},"w":{"color":"FF4A00","tag":"","style":"","code":"w"},"p":{"color":"FF4A00","tag":"","style":"","code":"p"},"e":{"color":"FF1F00","tag":"","style":"","code":"e"},"N":{"color":"E00000","tag":"","style":"","code":"N"},"{":{"color":"e0e0e0","tag":"","style":"","code":"{"},"Ü":{"color":"8A023F","tag":"","style":"","code":"Ü"}}';
        formatting.formats = JSON.parse(json);
        callback();
    }
}
