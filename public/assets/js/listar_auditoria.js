document.addEventListener('DOMContentLoaded', () => {
    listarAuditoria();
    const selectMod = document.querySelector('select[name="filtroMod_aud"]');
    const selectAcc = document.querySelector('select[name="filtroAcc_aud"]');
    const btnLimpiar = document.getElementById('btnLimpiar_usu');
    const inputBusqueda = document.querySelector('input[name="busqueda_aud"]');

    function buscarCombinado() {
        const texto = inputBusqueda ? inputBusqueda.value.trim() : '';
        const mod = selectMod ? selectMod.value : '';
        const acc = selectAcc ? selectAcc.value : '';

        if (btnLimpiar) {
            if (texto.length > 0 || mod !== '' || acc !== '') {
                btnLimpiar.classList.remove('d-none');
            } else {
                btnLimpiar.classList.add('d-none');
            }
        }

        listarAuditoria(texto, mod, acc); 
    }

    if (inputBusqueda) {
        inputBusqueda.addEventListener('input', buscarCombinado);
    }

    if (selectMod) {
        selectMod.addEventListener('change', buscarCombinado);
    }

    if (selectAcc) {
        selectAcc.addEventListener('change', buscarCombinado);
    }

    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', function() {
            if (inputBusqueda) inputBusqueda.value = '';
            if (selectMod) selectMod.value = '';
            if (selectAcc) selectAcc.value = '';
            this.classList.add('d-none');
            listarAuditoria('', '', '');
        });
    }
});



async function listarAuditoria(termino = '', mod = '', acc = '') {
    try {

        let parametros = [];
                
            if (termino) {
                parametros.push(`busqueda=${encodeURIComponent(termino)}`);
            }
            
            if (mod !== '') {
                parametros.push(`mod=${encodeURIComponent(mod)}`);
            }

            if (acc !== '') {
                parametros.push(`acc=${encodeURIComponent(acc)}`);
            }

            const url = parametros.length > 0 ? `/obtener-auditoria?${parametros.join('&')}` : '/obtener-auditoria';


        const respuesta = await fetch(url);
        const auditoria = await respuesta.json();

        let filas = '';
        let id = 1;
        let nombre = '';
        let modulo = '';
        let accion = '';

        auditoria.forEach(aud => {
            nombre = aud.usuario.persona.nombres+' '+aud.usuario.persona.apellidos;
            modulo = `<h4 class="badge badge-light">${aud.modulo}</h4>`;
            

            if (aud.accion == 'Registro') {
                accion = `<h4 class="badge badge-success">${aud.accion}</h4>`;
            }
            else if (aud.accion == 'Listado') {
                accion = `<h4 class="badge badge-info">${aud.accion}</h4>`;
            }
            else if (aud.accion == 'Consulta') {
                accion = `<h4 class="badge badge-secondary">${aud.accion}</h4>`;
            }
            else if (aud.accion == 'Actualizacion') {
                accion = `<h4 class="badge badge-primary">${aud.accion}</h4>`;
            }
            else if (aud.accion == 'Habilitacion/Inhabilitacion') {
                accion = `<h4 class="badge badge-danger">${aud.accion}</h4>`;
            }
            filas += `
                <tr>
                    <td>${id++}</td>
                    <td>${aud.fecha_hora}</td>
                    <td>${nombre}</td>
                    <td>${modulo}</td>
                    <td>${accion}</td>
                    <td>${aud.descripcion}</td>
                </tr>
            `;
        });

        document.getElementById('cuerpoTablaAuditoria').innerHTML = filas;

    } catch (error) {
        console.error("Error al listar:", error);
    }
}
