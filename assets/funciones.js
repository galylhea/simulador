$(document).ready(function() {
    keypressNumeric($('#txtValor'), true);
    pasteNumeric($('#txtValor'));

    keypressNumeric($('#txtGradiente'), true);
    pasteNumeric($('#txtGradiente'));

    keypressNumeric($('#txtSeguro'), true);
    pasteNumeric($('#txtSeguro'));

    $("#chk-3").change(function() {//Habilitar/ deshabilitar campo de gradiente
        if ($(this).is(':checked')){
           $('#txtGradiente').prop('disabled', false);
        } else {
            $('#txtGradiente').prop('disabled', true);
            $('#txtGradiente').val('');
        }
    });
});

/* Evento del teclado para caracteres numéricos */
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

/* Evento del teclado para caracteres numéricos versión móvil */
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

/* Función para simular crédito */
function simular() {
    let valor = $.trim($('#txtValor').val()).replace('$', '');// Reemplazar puntos por vacío para convertir en número
    valor = valor.split('.').join('');
    let plazo = $('#txtPlazo').val();
    let gradiente = $.trim($('#txtGradiente').val()).replace('$', '');// Reemplazar puntos por vacío para convertir en número
    gradiente = gradiente.split('.').join('');
    let seguro = $.trim($('#txtSeguro').val()).replace('$', '');// Reemplazar puntos por vacío para convertir en número
    seguro = seguro.split('.').join('');
    let cuota_inicial = $('#txtCuotaInicial').val();

    if (valor == ''){// Validar que se ingrese valor de préstamo
        Swal.fire({
            type: 'warning',
            title: 'Notificación',
            text: 'Ingrese un valor de préstamo'
        });
        return false;
    }

    if (plazo == 0){// Validar que se seleccione plazo
        Swal.fire({
            type: 'warning',
            title: 'Notificación',
            text: 'Seleccione un plazo'
        });
        return false;
    }

    if (valor < 1000000 || valor > 100000000){// Validar que se ingrese valor de préstamo
        Swal.fire({
            type: 'warning',
            title: 'Notificación',
            text: 'El valor del préstamo debe ser entre $1.000.000 y $100.000.000'
        });
        return false;
    }

    if ($(".tipo:checked").length == 0){
        Swal.fire({
            type: 'warning',
            title: 'Notificación',
            text: 'Seleccione por lo menos un tipo de amortización'
        });
        return false;
    }

    let tipo = '';// Agrupar opciones chequeadas por el usuario
    $.each($(".tipo:checked"), function(){
        tipo += $(this).val() + ',';
    });

    $('#resultado').addClass('hidden');
    $('#btn-simular').prop('disabled', true);
        Swal.fire({
        title: '',
        html: 'Por favor, espere...',
        timerProgressBar: true,
        allowEscapeKey: false,
        allowOutsideClick: false,
        onBeforeOpen: () => {
            Swal.showLoading()
        }
        })
    $.ajax({
        url:'ajxCalculo.php',
        type:'POST',
        data:{valor: valor, plazo: plazo, gradiente: gradiente, tipo: tipo, seguro: seguro, cuota_inicial: cuota_inicial},
        dataType:'html',
        success:function (r) {
            //console.log(r);
            $('#resultado').removeClass('hidden');
            $('#contenedor').html(r);
            swal.close();
            $('#btn-simular').prop('disabled', false);
        }
    });


}