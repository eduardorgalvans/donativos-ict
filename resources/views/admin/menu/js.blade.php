<script src="{{ asset('/assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('/assets/plugins/bootstrap-iconpicker/dist/js/bootstrap-iconpicker.bundle.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $(".select2").select2();

        $( '#convert_example_2' ).iconpicker().on( 'change', function( e ) {
            $("#console").prepend(e.icon+'</br>');
            $('#sIcono').attr( 'class', '' ).addClass( e.icon );
            $('#Icono').val( e.icon );
        });

        $('#Permiso').on('change', function(){
            let ruta = $(this).children(':selected').data('ruta');

            if (!ruta) {
                ruta = '#';
            }

            $('#Ruta').val(ruta);
            $('#Ruta').trigger('change');
        });
    });
</script>
