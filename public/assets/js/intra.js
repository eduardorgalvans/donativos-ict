$(document).ready(function () {
	// borra la alerta
	window.setTimeout(function() {
	    $(".desaparecer").fadeTo(500, 0).slideUp(500, function(){
	        $(this).remove();
	    });
	}, 5000);
	// habilitar la impresion
	$(".btnPrint").printPage();
    // habilitar los toottips esn los elemtos que tengas el data-toggle
    $('[data-toggle="tooltip"]').tooltip();
    // 
    $('#cerrar-sesion').on('click', function(){
        $('#frm-cerrar-sesion').submit();
    });

    // Con esto se enfoca el cuadro de texto de búsqueda del select
    $('.select2').on('select2:open', function(e){
        const selectId = e.target.id

        $(`.select2-search__field[aria-controls='select2-${selectId}-results']`).each(function(i, v){
            v.focus();
        });
    });
});