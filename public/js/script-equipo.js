$(document).ready(function() {
    $('#inputCategoriaSelect ').change(function() {
        var inputCategoria = $(this).val();
        var listaJugadoresTag = $('#inputJugadores');
        var route = "http://localhost:8000/jugadores/" + inputCategoria;
        listaJugadoresTag.empty();

        $.get(route, function (jugadoresCategoriaResp) {
            $(jugadoresCategoriaResp).each(function(key, value) {
                var $liElement = $('<li class="list-group-item"></li>');
                var contentLi = " " + value.nombres+" - "+value.categoria;
                var $checkLiBtn = $('<button type="button" '+' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>');
                $checkLiBtn.attr("id", value.id);
                $liElement.append($checkLiBtn, contentLi);
                listaJugadoresTag.append($liElement);
            });
        })
    });

});


$("#btn_guardar").on('click', function() {
    var route = "http://localhost:8000/equipo";
    var token = $("#token").val();
    var inputNombre = $('#inputNombre').val();
    var inputEntrenador = $('#inputEntrenador').val();
    var inputCategoriaSelect = $('#inputCategoriaSelect').val();

    // envia los datos al servidor en Json
    var ajaxRequest = $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        data: {
            entrenador: inputEntrenador,
            nombre: inputNombre,
            categoria: inputCategoriaSelect
        },
        dataType: 'json'
    });

    // se recibe si hay una respuesta (est caso vien del controller@create)
    ajaxRequest.done(function (data) {
        window.location="http://localhost:8000/equipo/"+data.idEquipo;
    });
});

$("#btn_actualizar").on('click', function() {
    var equipo = $("#idEquipo").val();
    var token = $("#token").val();
    var metodo = $("#method").val();
    var route = "http://localhost:8000/equipo/"+equipo;
    var inputNombre = $('#inputNombre').val();
    var inputEntrenador = $('#inputEntrenador').val();
    var inputCategoriaSelect = $('#inputCategoriaSelect').val();

    // envia los datos al servidor en Json
    var ajaxRequest = $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        data: {
            entrenador: inputEntrenador,
            nombre: inputNombre,
            categoria: inputCategoriaSelect
        },
        dataType: 'json'
    });

    // se recibe si hay una respuesta (est caso vien del controller@create)
    ajaxRequest.done(function (data) {
        window.location="http://localhost:8000/equipo/"+data.idEquipo;
    });
});

function cargarJugadores() {
    var inputCategoria = $("#inputCategoriaSelect").val();
    var listaJugadoresTag = $('#JugadoresElegidos');
    var route = "http://localhost:8000/jugadores/" + inputCategoria;
    listaJugadoresTag.empty();

    $.get(route, function (jugadoresCategoriaResp) {
        $(jugadoresCategoriaResp).each(function(key, value) {
            var $liElement = $('<li class="list-group-item"></li>');
            var $checkLiBtn = $('<button type="button" '+' class="btn btn-success btn-xs"><i class="fa fa-times"></i></button>');
            var contentLi = " " + value.nombres+" - "+value.categoria;
            $checkLiBtn.attr("id", value.id);
            $liElement.append($checkLiBtn, contentLi);
            listaJugadoresTag.append($liElement);
        });
    })
}
