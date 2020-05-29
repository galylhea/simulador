$(document).ready(function() {
    keypressNumeric($('#txtValor'), true);
    pasteNumeric($('#txtValor'));
});
function keypressNumeric(input, punt = false) {
    input.attr('inputmode', 'numeric');
    input.keyup(function(e) {
        keyupNumericMovil(this, e);
        if (punt) puntitos(this, this.value.charAt(this.value.length - 1));
    })
    input.keypress(function(e) {
        if (isNumeric(e)) return e.preventDefault();
        if (limitLength(e, $(this).val())) return e.preventDefault();
    })
}

function keyupNumericMovil(self, event) {
    let excep = ['.', ' '];
    self.value = self.value.replace(/(,)|(-)/, '');
    let ultValue = self.value[self.value.length - 1];
    let changeValue = (isNaN(parseInt(self.value))) ? '' : self.value.slice(0, -1);
    if (event.key == 'Unidentified' && excep.includes(ultValue)) return self.value = changeValue;
}

//validar si es numero la tecla
function isNumeric(e) {
    return (e.keyCode < 48 || e.keyCode > 57);
}

//validar si es numerico el texto
function hasNumeric(value) {
    return (/\D/.test(value));
}

//validar numerico paste
function pasteNumeric(input, punt = false) {
    input.bind("paste", function(e) {
        //buscar contenido del paste
        var pastedData = e.originalEvent.clipboardData.getData('text');
        let contenido = this.value + pastedData;
        //detener evento de pegado si no es numerico
        if (hasNumeric(pastedData)) return e.preventDefault();
        if ($(this).attr('type') == 'number' && limitLength(e, (contenido))) return e.preventDefault();
        if (punt) e.preventDefault();
    });
}

//validar limite
function limitLength(e, value) {
    let input = $(e.target);
    return (input.prop('maxlength') && value.length > parseInt(input.attr('maxlength')));
}

/*Convertir numero en formato moneda a medida que se escribe*/
function puntitos(donde, caracter) {
    if (donde.value != '') {
        var pat = /[\*,\+,\(,\),\?,\,$,\[,\],\^]/;
        var valor = donde.value.split('$').join('');
        var largo = valor.length;
        var nums = new Array();
        var crtr = true;
        if (isNaN(caracter) || pat.test(caracter) == true) {
            if (pat.test(caracter) == true) {
                caracter = '\'' + caracter;
            }
            var carcter = new RegExp(caracter, "g");
            valor = valor.replace(carcter, "");
            donde.value = valor;
            crtr = false;
        } else {
            nums = new Array();
            var cont = 0;
            for (var m = 0; m < largo; m++) {
                if (valor.charAt(m) == "." || valor.charAt(m) == " ") {
                    continue;
                } else {
                    nums[cont] = valor.charAt(m);
                    cont++;
                }
            }
        }
        var cad1 = "",
            cad2 = "",
            tres = 0;
        if (largo > 3 && crtr == true) {
            for (var k = nums.length - 1; k >= 0; k--) {
                cad1 = nums[k];
                cad2 = cad1 + cad2;
                tres++;
                if ((tres % 3) == 0) {
                    if (k != 0) {
                        cad2 = "." + cad2;
                    }
                }
            }
            donde.value = cad2;
        }
        if (donde.value.charAt(0) != '$') {
            donde.value = '$' + donde.value;
        }
        if (donde.value.charAt(1) == '0') {
            let value = Array.from(donde.value);
            value.splice(2, 2);
            donde.value = value.join('');
        }
    }
    if (donde.value == '$') {
        donde.value = '';
    }
}