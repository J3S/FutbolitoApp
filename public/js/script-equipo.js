function loadCatJugadors(element) {
    var inputCategoria = element.val();
    var listaJugadoresTag = $('#inputJugadores');
    var route = "http://localhost:8000/jugadores/" + inputCategoria;
    if (inputCategoria.length === "noSelected") {
        listaJugadoresTag.html('<li class="list-group-item">Ninguna Categoria selecionada</li>');
    } else {
        var ajaxRequest = $.ajax({
            url: route,
            async:false
        });
        ajaxRequest.done(function (jugadoresCategoriaResp) {
            if ($.type(jugadoresCategoriaResp) === "string") { //getAjax response has not jugadors
                listaJugadoresTag.html('<li class="list-group-item">' + jugadoresCategoriaResp + '</li>')
            } else {
                listaJugadoresTag.empty();
                $("#JugadoresElegidos").empty();
                $(jugadoresCategoriaResp).each(function(key, value) {
                    var $liElement = $('<li/>').addClass("list-group-item");
                    $liElement.css({
                        "padding": "5px 15px",
                        "text-align": "center",
                    });
                    var $contentLi = $("<div/>").addClass("row");
                    var $divNom = $("<div/>").addClass("col-xs-4").text(value.nombres);
                    var $divApe = $("<div/>").addClass("col-xs-4").text(value.apellidos);
                    var $divCat = $("<div/>").addClass("col-xs-4").text(value.categoria);
                    // creando el contendo de li
                    $contentLi.append($divNom, $divApe, $divCat);
                    var $checkBtn = $("<button/>");
                    $checkBtn.attr({
                        type: "button",
                        class: "btn btn-success btn-xs",
                        id: value.id
                    });
                    $checkBtn.append($("<i/>").addClass("fa fa-check"));
                    // wraping li content in col row bootstrap with btn, contentLi
                    var $divLiRow = $("<div/>").addClass("row")
                    var $divLiCol1 = $("<div/>").addClass("col-xs-1").append($checkBtn);;
                    var $divLiCol11 = $("<div/>").addClass("col-xs-11").append($contentLi);
                    $divLiRow.append($divLiCol1, $divLiCol11)
                    $liElement.append($divLiRow);
                    listaJugadoresTag.append($liElement);
                });
            }
        });
    }
}

$(document).ready(function() {
    $('#inputCategoriaSelect ').change(function() {
        loadCatJugadors($(this));
    });
    $(document).on("click", "#inputJugadores li button", function(event) {
        $(this).addClass("btn-danger").removeClass("btn-success");
        $(this).html($("<i/>").addClass("fa fa-times"));
        $("#JugadoresElegidos").append($(this).parent().parent().parent());

    });

    $(document).on("click", "#JugadoresElegidos li button", function(event) {
        $(this).addClass("btn-success").removeClass("btn-danger");
        $(this).html($("<i/>").addClass("fa fa-check"));
        $("#inputJugadores").append($(this).parent().parent().parent());
    });

    $("#btn_guardar").on('click', function() {
        var route = "http://localhost:8000/equipo";
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

        // se recibe si hay una respuesta (est caso vien del controller@store)
        ajaxRequest.done(function(data) {
            alert(data.mensaje);
            window.location = "http://localhost:8000/equipo/";
        });
    });

    $("#btn_actualizar").on('click', function() {
        var equipo = $("#idEquipo").val();
        var token = $("#token").val();
        var metodo = $("#method").val();
        var route = "http://localhost:8000/equipo/" + equipo;
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
            alert('Equipo actualizado exitosamente');
            window.location = "http://localhost:8000/equipo/";
        });
    });
});
