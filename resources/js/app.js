// Paso 1: Importar jQuery y asignarlo al objeto window.
// Esto es crucial y debe hacerse ANTES de importar AdminLTE o Bootstrap.
import './bootstrap';

import $ from 'jquery';
window.$ = window.jQuery = $;

// Paso 2: Importar el resto de las librerías
import 'bootstrap';
import 'admin-lte';
import 'datatables.net-bs5';

// Paso 3: Importar SweetAlert2 y asignarlo a window para un acceso fácil
import Swal from 'sweetalert2';
window.Swal = Swal;

console.log('Todos los scripts han sido cargados correctamente.');

