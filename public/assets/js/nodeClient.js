function Notification_OnEvent( event ) {
    //A reference to the Notification object
    var notif = event.currentTarget;
    //console.log( "Notification '" + notif.title + "' received event '" + event.type + "' at " + new Date().toLocaleString() )
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();
    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;
    return [year, month, day].join('-');
}

// Se obtiene el valor de la propiedad src de este script.
let cadena = $( 'script[src*="nodeClient.js"]' ).prop( 'src' );
// Se separa la cadena y se descarta lo que esté antes de '?', para luego
// separar mediante '&'.
let variables = cadena.split( '?' ).pop().split( '&' );
let tblDGP = 0;
// Se despliega en consola el nombre y valor de cada variable.
for ( var i = variables.length - 1; i >= 0; i-- ) {
		v = variables[ i ].split( '=' );
		if ( v[ 0 ] == 'tblDGP' ) tblDGP = v[ 1 ];
		//console.log(`${v[0]}: ${v[1]}`);
}
//
var Socket = io( 'https://app.ict.edu.mx:8094?tblDGP=' + tblDGP );
//
Socket.on( 'Actualiza', function( data ) {
  	console.log( 'Actualiza...' );
});
//
Socket.on( 'notification', function( data ) {
    //console.log('data 1 : ', data);
    // si es la persona 
    if ( data.message.TblDGP_id_Para == tblDGP ) {

		let url = '/dashboard/' + data.message.TblDGP_id_De + '/foto';
		let urlFoto = '/assets/img/user/user-2.jpg';
		$.getJSON( url ).done( function( datos ){
			// recuperamos la foto del empleado
			urlFoto = datos.foto;
			// notificacion en el navegador
			$.gritter.add({
				title: data.message.Titulo,
				text: data.message.Notificacion,
				image: urlFoto,
				sticky: true,
				time: '',
				class_name: 'my-sticky-class'
			});
			// notificacion en el SO
			var notif = showWebNotification( data.message.Titulo, data.message.Notificacion, urlFoto, null, 3000);
	        //handle different events
	        /*
			notif.addEventListener("show", Notification_OnEvent);
			notif.addEventListener("click", Notification_OnEvent);
			notif.addEventListener("close", Notification_OnEvent);
			*/
		});

    }
});

Socket.on( 'mensajero', function( data ) {
	console.log('data 2 : ', data);
	// determinamos si estamos en página de vigilancia
	if ( $( "#dMesajeroVigilancia" ).length ) {
		let hoy = formatDate( new Date() );
		// console.log( hoy, data.message.Fecha );
		if ( hoy == data.message.Fecha ) {
			// bandera para existe antes del final
			let bInyecta = false;
			// recoeremos todos los nodos buscndo el que ya no sea mayor al actual
			$( ".chats-item" ).each(function(){
				let oReferencia = $( this ).prev();
				let sHora = $( this ).data( "hora" );
				// si la hora es mayor al elemento a insertar
				bInyecta = ( sHora > data.message.Hora );
				// console.log( bInyecta );
				if ( bInyecta ) {
					// Recuperamos el elemento
					$.get( "/vigilancia/mensajero/mensaje", { id: data.message.TblM_id, final: 0 } )
  						.done(function( data ) {
    						console.log( "Data Loaded: " + data );
    						//$( data ).prependTo( oReferencia );
    						oReferencia.after( data );
  						});
  					return false;
				}
			});
			if ( ! bInyecta ) {
				// console.log( "No inserto nada." );
				// Recuperamos el elemento
				$.get( "/vigilancia/mensajero/mensaje", { id: data.message.TblM_id, final: 1 } )
  					.done(function( data ) {
    					console.log( "Data Loaded: " + data );
  						$( ".chats" ).append( data )
  					});
			}
		}
	  	// hacer algo aquí si el elemento existe
	}
});

Socket.on('LW', function(data) {
	// console.log('LW : ', data);
	Livewire.emit( data.Notificacion );
});

Socket.on('message', function(data) {
	console.log('data 2 : ', data);
	/*
	webNotification.showNotification('@' + data.username + ' dice: ', {
		body: data.message,
		icon: 'images/laravel.png',
		onClick: function onNotificationClicked() {
			console.log('Notification clicked.');
		},
		autoClose: 4000
	}, function onShow(error, hide) {
		if (error) {
			window.alert('Unable to show notification: ' + error.message);
		} else {
			console.log('Notification Shown.');
			setTimeout(function hideNotification() {
				console.log('Hiding notification....');
				hide();
			}, 5000);
		}
	});
	*/
});
