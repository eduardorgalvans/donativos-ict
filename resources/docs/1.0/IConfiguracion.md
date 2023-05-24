# Configuración

---

- [Menú](#OpMenu)
- [Variables permanentes](#Permanentes)


Control y configuracion de la intranet

<a name="OpMenu"></a>
## Menú

El módulo **_Menú_** nos permite manipular los enlaces que aparecen a lado izquierdo de la aplicación agregando enlaces a cada uno de los módulos que se agreguen al sistema

---

![alt text](/assets/img/docs/I_C_M_1.png "Menú crear")

---

A continuación se listan las acciones que nos permite realizar este módulo:

1. **Nuevo** : nos permite agregar un nuevo enlace al menú.
1. **Filtrar** : nos permite filtrar y ordenar el contenido del listado en pantalla.
1. **Imprimir** : nos permite imprimir lo mostrado en el listado en pantalla.
1. **Exportar** : nso permite exportar a Excel lo mostrado en el listado en pantalla.
1. **Buscar** : nos permite buscar un texto en la información mostrándolo en el listado en pantalla.
1. **Eliminar el filtrado** : elimina cualquier filtro u/y ordenado que se aplicó al listado.
1. **Mostrar** : nos permite cambiar el numero de elementos mostrados en el listado en pantalla.
1. **Ver, modificar o Elimiar** : nos permite realizar acciones sobre el registro seleccionado del listado.

###Nuevo

A continuación se describe la inserción de un nuevo enlace en el menú; dando un click en _**Nuevo**_ (1) nos dirigira a la siguente pantalla donde nos solicitara la información necesaria para agregar el elemento al menú

---

![alt text](/assets/img/docs/I_C_M_2.png "Menú Nuevo")

---

En esta página se capturarán los aspectos importantes de cada uno de los menús, a continuación se describen cada uno de ellos:

- **Nodo Padre**: debe de seleccionar el del cual nodo se va a pertenecer el elemento a ingresar, todo nodo debe de contar con un padre.
- **Icono** : debe de seleccionar el icono del elemento, este se mostrará a un lado del nombre del elemento en el menú de la aplicación.
- **Nombre ** : debe de capturar una descripción del enlace, este campo debe de ser corto y conciso.
- **Módulo asociado** : debe de seleccionar un elemento que le dará la posibilidad de bloquear el acceso a los enlaces dependiendo si el permiso está en la relación de asignado al usuario.
- **Ruta** : debe de seleccionar un elemento de la lista de rutas que están dadas de alta en el aplicativo, a la cual le redirigirá al dar clic al enlace del menú.
- **Orden** : debe de introducir un valor numérico de ser necesario para obligar la reorganización de los electos del menú. 
> {info} Si se ingresa el orden de algún elemento deberá de darle a todos los elementos que pertenecen al nodo padre para que se ordenen al gusto del usuario, de no ser así los que no tengan el valor orden se listaran primero.
- **Elementos** : esta bandera nos permite indicar si ele elemento que se está ingresando puede agrupar otros elementos del menú.
- **Activo** : al habilitar esta bandera mostrara el tallere en la interfaz la _**Intranet**_.

Una vez que se proporcione la información requerida se presiona el botón _**Guardar**_ almacenara la información y redireccionara al listado de los elementos.

###Filtrar

El listado nos permite filtrar y ordenar el contenido que se visualizara en el módulo, para realizar esta acción se presiona el botón _**Filtrar**_ (2) esto nos permitirá visualizar del lado derecho como se muestra en la siguiente pantalla

---

![alt text](/assets/img/docs/I_C_M_3.png "Menú Filtrar")

---

A continuación se listan las acciones que nos permite realizar esta pestaña:

1. **Padre** : nos permite filtrar los nodos pertenecientes al padre seleccionado.
1. **Orden** : nos permite ordenar los nodos con base en la columna seleccionada de manera ascendente o descendente.

###Imprimir

El listado nos permite imprimir el contenido que se visualizara en el módulo, para realizar esta acción se presiona el botón _**Imprimir**_ (3) esto nos permitirá visualizar una ventana como la que se muestra a continuación.

---

![alt text](/assets/img/docs/I_C_M_4.png "Menú Imprimir")

---

###Exportar

El listado nos permite exportar a Excel el contenido que se visualizara en el módulo, para realizar esta acción se presiona el botón _**Exportar**_ (4) esto nos permitirá descargar un archivo con formato del contenido visualizado.

###Buscar

El listado nos permite filtrar el contenido que se visualizara en el módulo, para realizar esta acción se introduce el texto a localizar en el campo (5) y presiona el botón _**Buscar**_ esto nos recargará el listado mostrando el contenido que cumpla con la búsqueda.

###Eliminar el filtrado

Para restaurar el listado solo es necesario presionar el botón _**Eliminar el filtrado**_ (6) esto eliminar cualquier búsqueda o filtrado que esté aplicado al contenido del listado.

###Mostrar

El listado nos permite modificar la cantidad de elementos listados que se visualizara en el módulo, para realizar esta acción selecciona la cantidad que desear visualizar 10, 25, 50, 100 o todos en el combo _**Mostrar**_ (7) esto recargará el listado mostrando el contenido con la cantidad seleccionada.

###Ver 

A continuación se describe la visualización de un enlace del menú; dando un clic en _**Ver**_ (8) [ la lupa ] nos dirigirá a la siguiente pantalla donde nos mostrara la información del elemento al menú además de la información del último usuario que creo o modifico el elemento que visualizamos y las fechas de creación y modificación.

---

![alt text](/assets/img/docs/I_C_M_5.png "Menú Ver")

---

###modificar 

A continuación se describe la modificacion de un enlace del menú; dando un clic en _**modificar**_ (8) [ el lapiz ] nos dirigirá a la siguiente pantalla donde nos mostrara la información del elemento al menú.

---

![alt text](/assets/img/docs/I_C_M_6.png "Menú modificar")

---

Una vez que se realice el cambio de la información requerida se presiona el botón _**Guardar**_ almacenara la información y redireccionara al listado de los elementos.


###Elimiar

Para eliminar un elemento del listado solo es necesario presionar el botón _**Eliminar**_ (8) [ bote de basura ] esto eliminar el elemento seleccionado del listado.

> {warning} Esta eliminación no es permanente de ser necesario se puede restaurar el elemento eliminado.

<a name="Permanentes"></a>
## Variables permanentes

El módulo **_Variables permanentes_** nos permite administrar un grupo de elementos que pueden ser usados como variables de sesión permanentes que se pueden consultar vía una función de la librería y mantenerlos para recuperarlos en cuanto sea necesitara en cualquier apartado del código de la aplicación.


Para hacer uso de estas en el codigo es necesario hacer referencia a 2 funciones que se encuentran en la libreria 

- **putVariablePermante** : nos permite almacenar o modificar una variable, nos solicita 2 o 3 parametros:

1. Nombre de la variable
1. Valor que va a contener esta variable
1. Y opcinalmente el tipo de variable [ str, int, date, json ]; si no se proporciona por defecto asigna str
```shell
Libreria::putVariablePermante( 'Variable_permanente', 'Valor', 'str' );
```

- **getVariablePermanente**: permite recuperar el valor almacenado con el nombre de variable pasado por parámetro, de manera opcional se puede proporcionar un valor por defecto en el caso de que no exista. Nos lo va a devolver con el tipo de dato proporcionado.
```shell
$sVarPerm = Libreria::getVariablePermanente('Variable_permanente', 'Valor por defecto');
```

El módulo **_Variables permanentes_** nos permite manipular los elemento almacenados a continuación listamos las acciones que nos permite el módulo

---

![alt text](/assets/img/docs/I_C_VP_1.png "Menú crear")

---

A continuación se listan las acciones que nos permite realizar este módulo:

1. **Nuevo** : nos permite agregar un nuevo enlace al menú.
1. **Filtrar** : nos permite filtrar y ordenar el contenido del listado en pantalla.
1. **Imprimir** : nos permite imprimir lo mostrado en el listado en pantalla.
1. **Exportar** : nso permite exportar a Excel lo mostrado en el listado en pantalla.
1. **Buscar** : nos permite buscar un texto en la información mostrándolo en el listado en pantalla.
1. **Eliminar el filtrado** : elimina cualquier filtro u/y ordenado que se aplicó al listado.
1. **Mostrar** : nos permite cambiar el numero de elementos mostrados en el listado en pantalla.
1. **Ver, modificar o Elimiar** : nos permite realizar acciones sobre el registro seleccionado del listado.

###Nuevo

A continuación se describe la inserción de un nuevo enlace en el menú; dando un click en _**Nuevo**_ (1) nos dirigira a la siguente pantalla donde nos solicitara la información necesaria para agregar el elemento al menú

---

![alt text](/assets/img/docs/I_C_VP_2.png "Variable nueva")

---

En esta página se capturarán los aspectos importantes de cada uno de los menús, a continuación se describen cada uno de ellos:

- **Variable**: debe de introducir un identificador con el cual se va a referenciar la variable.
- **Valor** : debe de introducir un valor con el cual se va a almacenar el identificador. 
- **Tipo** : debe de seleccionar un el tipo del valor se almacenó en el identificador esto permitirá devolver ese tipo de valor.

Una vez que se proporcione la información requerida se presiona el botón _**Guardar**_ almacenara la información y redireccionara al listado de los elementos.

###Filtrar

El listado nos permite filtrar y ordenar el contenido que se visualizara en el módulo, para realizar esta acción se presiona el botón _**Filtrar**_ (2) esto nos permitirá visualizar del lado derecho como se muestra en la siguiente pantalla

---

![alt text](/assets/img/docs/I_C_M_3.png "Menú Filtrar")

---

A continuación se listan las acciones que nos permite realizar esta pestaña:

1. **Padre** : nos permite filtrar los nodos pertenecientes al padre seleccionado.
1. **Orden** : nos permite ordenar los nodos con base en la columna seleccionada de manera ascendente o descendente.

###Imprimir

El listado nos permite imprimir el contenido que se visualizara en el módulo, para realizar esta acción se presiona el botón _**Imprimir**_ (3) esto nos permitirá visualizar una ventana como la que se muestra a continuación.

---

![alt text](/assets/img/docs/I_C_M_4.png "Menú Imprimir")

---

###Exportar

El listado nos permite exportar a Excel el contenido que se visualizara en el módulo, para realizar esta acción se presiona el botón _**Exportar**_ (4) esto nos permitirá descargar un archivo con formato del contenido visualizado.

###Buscar

El listado nos permite filtrar el contenido que se visualizara en el módulo, para realizar esta acción se introduce el texto a localizar en el campo (5) y presiona el botón _**Buscar**_ esto nos recargará el listado mostrando el contenido que cumpla con la búsqueda.

###Eliminar el filtrado

Para restaurar el listado solo es necesario presionar el botón _**Eliminar el filtrado**_ (6) esto eliminar cualquier búsqueda o filtrado que esté aplicado al contenido del listado.

###Mostrar

El listado nos permite modificar la cantidad de elementos listados que se visualizara en el módulo, para realizar esta acción selecciona la cantidad que desear visualizar 10, 25, 50, 100 o todos en el combo _**Mostrar**_ (7) esto recargará el listado mostrando el contenido con la cantidad seleccionada.

###Ver 

A continuación se describe la visualización de un enlace del menú; dando un clic en _**Ver**_ (8) [ la lupa ] nos dirigirá a la siguiente pantalla donde nos mostrara la información del elemento al menú además de la información del último usuario que creo o modifico el elemento que visualizamos y las fechas de creación y modificación.

---

![alt text](/assets/img/docs/I_C_M_5.png "Menú Ver")

---

###modificar 

A continuación se describe la modificacion de un enlace del menú; dando un clic en _**modificar**_ (8) [ el lapiz ] nos dirigirá a la siguiente pantalla donde nos mostrara la información del elemento al menú.

---

![alt text](/assets/img/docs/I_C_M_6.png "Menú modificar")

---

Una vez que se realice el cambio de la información requerida se presiona el botón _**Guardar**_ almacenara la información y redireccionara al listado de los elementos.


###Elimiar

Para eliminar un elemento del listado solo es necesario presionar el botón _**Eliminar**_ (8) [ bote de basura ] esto eliminar el elemento seleccionado del listado.

> {warning} Esta eliminación no es permanente de ser necesario se puede restaurar el elemento eliminado.

