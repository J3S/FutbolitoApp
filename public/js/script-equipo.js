/**
 * Llena un tag ul con li's que son sacados de un arreglo o json, cada li
 * contiene los datos de un jugador y un boton para add o remover.
 *
 * @param {Array} lista de jugadores obtenidos como respuesta de ajax.
 * @param {Element} ul element que sera llenado con li's.
 * @param {string} accion del boton si de tipo add o remove.
 */
function fillJugsAjax( jugsAjaxResp, ulElement, btnAction ) {
    $(jugsAjaxResp).each(function(key, value) {
        var $liElement = $('<li/>').addClass("list-group-item");
        $liElement.css({
            "padding": "5px 15px",
            "text-align": "center",
        });

        // make content li element
        var $contentLi = $("<div/>").addClass("row");
        var $divNom = $("<div/>").addClass("col-xs-4").text(value.nombres);
        var $divApe = $("<div/>").addClass("col-xs-4").text(value.apellidos);
        if (value.categoria === null) {
            var $divCat = $("<div/>").addClass("col-xs-4").text("sin categoria");
        }else {
            var $divCat = $("<div/>").addClass("col-xs-4").text(value.categoria);
        }
        $contentLi.append($divNom, $divApe, $divCat);

        // make button
        var $checkBtn = $("<button/>");
        $checkBtn.attr({
            type: "button",
            name: "btnList",
            id:   value.id
        });

        //
        if (btnAction === "check") {
            $checkBtn.addClass("btn btn-success btn-xs");
            $checkBtn.append($("<i/>").addClass("fa fa-check"));
        } else if (btnAction === "times") {
            $checkBtn.addClass("btn btn-danger btn-xs");
            $checkBtn.append($("<i/>").addClass("fa fa-times"));
        }

        // wraping li content in col row bootstrap with btn, contentLi
        var $divLiRow = $("<div/>").addClass("row")
        var $divLiCol1 = $("<div/>").addClass("col-xs-1").append($checkBtn);;
        var $divLiCol11 = $("<div/>").addClass("col-xs-11").append($contentLi);
        $divLiRow.append($divLiCol1, $divLiCol11)
        $liElement.append($divLiRow);

        // append row li into
        ulElement.append($liElement);
    });
}


/**
 * Carga la lista de jugadores selecionados de ese equipo.
 *
 * @param {Element} ul element que sera llenado con li's.
 * @param {int} id del equipo.
 */
function loadSelectedJugadors(ulInputJugadores, idEquipo) {
    var path = "/equipo/"+idEquipo;
    $.getJSON(path, function ( data ) {
        fillJugsAjax(data, ulInputJugadores, "times");
    })
}


/**
 * Carga los jugadores selecionados y los disponibles para esa categoria
 * haciendo un requerimiento ajax
 *
 * @param {Element} select element que tiene la categoria de jugadores a cargar.
 */
function loadCatJugadors(selectorElement) {
    var inputCategoria = selectorElement.val();
    var listaJugadoresTag = $('#inputJugadores');
    var route = "/jugadores/" + inputCategoria;
    if (inputCategoria === "noSelected") {
        listaJugadoresTag.html('<li class="list-group-item">Ninguna Categoria selecionada</li>');
    } else {
        var ajaxRequest = $.ajax({
            url: route,
            async:false
        });
        ajaxRequest.done(function (jugadoresCategoriaResp) {
            // getAjax response has not jugadors(responde con string y no JSON)?
            if ($.type(jugadoresCategoriaResp) === "string") {
                listaJugadoresTag.html('<li class="list-group-item">' + jugadoresCategoriaResp + '</li>')
            } else {

                $("#JugadoresElegidos").empty();
                if (inputCategoria === $("#categoriaEquipo").val()) {
                    loadSelectedJugadors($("#JugadoresElegidos"), $("#idEquipo").val() );
                }

                listaJugadoresTag.empty();
                fillJugsAjax( jugadoresCategoriaResp, listaJugadoresTag, "check" );
            }
        });
    }
}

$(document).ready(function() {
    // create case
    if ($("#inputCategoriaSelect").val() === "noSelected") {
        $("#inputCategoriaSelect").on('change', function(event) {
            var $li = $("#JugadoresElegidos");
            loadCatJugadors($(this));
        });
    }else {
        //update case
        loadCatJugadors($("#inputCategoriaSelect"));
        $("#inputCategoriaSelect").on('change', function(event) {
            loadCatJugadors($(this));
        });
    }

    // from lista elegibles(inputJugadores) to JugadoresElegidos
    $(document).on("click", "#inputJugadores li button", function(event) {
        $(this).addClass("btn-danger").removeClass("btn-success");
        $(this).html($("<i/>").addClass("fa fa-times"));
        $("#JugadoresElegidos").append($(this).parent().parent().parent());

    });

    // form JugadoresElegidos back to lista elegibles (inputJugadores)
    $(document).on("click", "#JugadoresElegidos li button", function(event) {
        $(this).addClass("btn-success").removeClass("btn-danger");
        $(this).html($("<i/>").addClass("fa fa-check"));
        $("#inputJugadores").append($(this).parent().parent().parent());
    });

    $("#btn_guardar").on('click', function() {
        var route = "/equipo";
        var token = $("#token").val();
        var inputNombre = $('#inputNombre').val();
        var inputEntrenador = $('#inputEntrenador').val();
        var inputCategoriaSelect = $('#inputCategoriaSelect').val();
        var elegidos = [];
        $("#JugadoresElegidos li").each(function(index, el) {
            elegidos.push($(el).find("button").attr("id"));
        });
        // elegidos[0] = 20000; para validar jugador desconocido
        // envia los datos al servidor en Json
        var ajaxRequest = $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
            type: 'POST',
            data: {
                entrenador: inputEntrenador,
                nombre: inputNombre,
                categoria: inputCategoriaSelect,
                ids: elegidos
            },
            dataType: 'json',
            error: function (xhr) {
                var alMjs = xhr.responseJSON;
                $(".alert.alert-danger").css("display", "block");
                $("#alerts").empty();
                $.each(alMjs, function(key, value) {
                    console.log(value[0]);
                    $("#alerts").append($("<li/>").text(value[0]));
                });
                window.location = "#";
            }
        });

        // se recibe si hay una respuesta (est caso vien del controller@store)
        ajaxRequest.done(function(data) {
            window.location = "/equipo/";
        });
    });

    $("#btn_actualizar").on('click', function() {
        var equipo = $("#idEquipo").val();
        var token = $("#token").val();
        var metodo = $("#method").val();
        var route = "/equipo/" + equipo;
        var inputNombre = $('#inputNombre').val();
        var inputEntrenador = $('#inputEntrenador').val();
        var inputCategoriaSelect = $('#inputCategoriaSelect').val();
        var elegidos = [];
        $("#JugadoresElegidos li").each(function(index, el) {
            elegidos.push($(el).find("button").attr("id"));
        });
        // envia los datos al servidor en Json
        var ajaxRequest = $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
            type: 'PUT',
            data: {
                entrenador : inputEntrenador,
                nombre     : inputNombre,
                categoria  : inputCategoriaSelect,
                id         : equipo,
                ids        : elegidos
            },
            dataType: 'json',
            error: function (xhr) {
                var alMjs = xhr.responseJSON;
                console.log(alMjs);
                $(".alert.alert-danger").css("display", "block");
                $("#alerts").empty();
                $.each(alMjs, function(key, value) {
                    console.log(value[0]);
                    $("#alerts").append($("<li/>").text(value[0]));
                });
                window.location = "#";
            }
        });

        // se recibe si hay una respuesta (est caso vien del controller@update)
        ajaxRequest.done(function(data) {
            window.location = "/equipo/";
        });
    });
});
