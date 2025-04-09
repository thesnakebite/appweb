class lenguaje {
    langs = [
        "es-ES",
        "al-AL",
        "en-EN",
        "fr-FR",
      ];

      charsets = [
        "UTF-8",
        "ISO-8859-1",
        "ISO-8859-15",
        "Windows-1252",
      ];

    idioma = '';

    constructor(){
        this.#setIdioma();
        this.#comparar();
    }

    #setIdioma(idioma){
        let idioma = navigator.language;
        this.idioma = idioma;
    }

    #comparar()
    {
        let meta = document.querySelector('meta[charset]');
        let i = 0;
            for(dato of this.langs){
                if(this.idioma == this.langs[i]){
                    meta.charset= this.charsets[i];
                    break
                }
                i++;
            }
    }
}


class Reloj extends lenguaje
{
    dias = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
    meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    fecha;
    zone;
    dia;
    dian;
    mes;
    year;
    hour;
    min;
    seg;
    html = '';

    constructor()
    {
        this.fecha = new Date();
        this.setReloj();
        this.setHtml();
    }

    setReloj()
    {
        this.zone = this.fecha.toLocaleString('timeZone');
        this.dia = this.fecha.getDay();
        this.dian = this.fecha.getDate();
        this.mes = this.fecha.getMonth();
        this.year = this.fecha.getFullYear();
        this.hour = this.fecha.getHours();
        this.min = this.fecha.getMinutes();
        this.seg = this.fecha.getSeconds();
    }

    setHtml()
    {
        this.html = `${this.dia} , ${this.dian} / ${this.mes} / ${this.year}  ${this.hour} : ${this.min} : ${this.seg}`;
    }

    getCogerFecha()
    {
        return this.html;
    }
}
