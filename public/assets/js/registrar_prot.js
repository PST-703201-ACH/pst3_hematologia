// Precargar medicinas
let medicinas = [];
async function cargarMedicinas() {
    try {
        const respuesta = await fetch('/admin/obtener-medicinas-disponibles');
        
        medicinas = await respuesta.json(); 
    } catch (error) {
        console.error("Error al cargar:", error);
    }
}

cargarMedicinas();

const medicinasSeleccionables = [...medicinas];

function enumerarFases() {
    const numeroFase = document.querySelectorAll('.numFa');
    
    numeroFase.forEach((fase, index) => {
        fase.textContent = `Fase №${index + 1}`;
        
        const tablaPadre = fase.closest('table');
        
        if (tablaPadre) {
            const tbodyFa = tablaPadre.querySelector('tbody');
            
            if (tbodyFa) {
                tbodyFa.setAttribute('id', `${index + 1}`);
            }
        }
    });
}

function enumerarMed(tbodyFa){
    const numeroMed = tbodyFa.querySelectorAll('.numMed');
    const selectMed = tbodyFa.querySelectorAll('.seleccion-med');
    const selectVia = tbodyFa.querySelectorAll('.seleccion-via');

    const numFase = tbodyFa.id;
    contador = 1;
    numeroMed.forEach((med, index) => {
        med.textContent = contador++;
        selectMed[index].setAttribute('name', `med_fase-${numFase}[]`);
        selectVia[index].setAttribute('name', `via_fase-${numFase}[]`);
   });
}


function agregarMed(tbody){
    try {

    // Creacion de nueva fila
    const trFa2 = document.createElement('tr');
    const tdMedNum = document.createElement('td');
    tdMedNum.className = "numMed";
    trFa2.appendChild(tdMedNum);
    const tdMed = document.createElement('td');
    const select = document.createElement('select');
    select.className = 'form-control seleccion-med';
    select.innerHTML = '<option value="" disabled selected>Seleccione un medicamento</option>';
    tdMed.appendChild(select);
    trFa2.appendChild(tdMed);
    const tdViaAdmin = document.createElement('td');
    const selectVia = document.createElement('select');
    selectVia.className = 'form-control seleccion-via';
    selectVia.innerHTML = '<option value="" disabled selected>Seleccione una via de administracion</option>'
    +'<option value="1">Intramuscular</option>'
    +'<option value="2">Indovenoso</option>'
    +'<option value="3">Oral</option>';
    tdViaAdmin.appendChild(selectVia);
    trFa2.appendChild(tdViaAdmin);
    var tdEliminar = document.createElement('td');
    var btnEliminar = document.createElement('button');
    btnEliminar.className = "btn btn-danger";
    btnEliminar.type = "button";
    const iconoBoton = document.createElement('i');
    iconoBoton.className = "fas fa-solid fa-minus";
    btnEliminar.appendChild(iconoBoton);
    tdEliminar.appendChild(btnEliminar);
    trFa2.appendChild(tdEliminar);

    let selectAnterior = null;

    function refrescarFiltros() {
        const idsOcupados = Array.from(tbody.querySelectorAll('.seleccion-med'))
        .map(sel => parseInt(sel.value))
        .filter(id => !isNaN(id));

        tbody.querySelectorAll('.seleccion-med').forEach(otroSelect => {
            const valorDeEsteSelect = parseInt(otroSelect.value);

            Array.from(otroSelect.options).forEach(opcion => {
                const idOpcion = parseInt(opcion.value);
                if (isNaN(idOpcion)) return;

                if (idsOcupados.includes(idOpcion) && idOpcion !== valorDeEsteSelect) {
                    opcion.style.display = 'none';
                } else {
                    opcion.style.display = '';
                }
            });
        });
    };

    select.addEventListener('change', () => {
        const idMed = parseInt(select.value);

        if (isNaN(idMed) || !idMed) {
            selectAnterior = null;
            refrescarFiltros();
            return; 
        }

        selectAnterior = idMed;
        
        refrescarFiltros();
    });


    btnEliminar.addEventListener('click', () => {
        trFa2.remove();
        enumerarMed(tbody);
        refrescarFiltros();
    });

    
    medicinas.forEach(med => {
        select.innerHTML += `<option value="${med.medicina_id}">${med.descripcion}</option>`;
    });

    tbody.appendChild(trFa2);
    enumerarMed(tbody);
    
    refrescarFiltros();
    
    } catch (error) {
        console.error("Error:", error);
    }
}

function crearFase() {
    // Creacion de tabla
    const contenedorTabla = document.getElementById('fasesProt');
    const tablaFa = document.createElement('table');
    tablaFa.className = 'table table-striped';

    // Creacion de encabezado
    const theadTabla = document.createElement('thead');

    // Primera fila del encabezado
    const trFa0 = document.createElement('tr');
    const thFa0 = document.createElement('th');
    thFa0.colSpan = 3;
    const pNumero = document.createElement('p');
    pNumero.className = "numFa";
    pNumero.textContent = "Fase №";
    pNumero.style.textAlign = "center"; 
    thFa0.appendChild(pNumero);
    const thFa1 = document.createElement('th');
    const btnEliminarFa = document.createElement('button');
    btnEliminarFa.type = "button";
    btnEliminarFa.className = "btn btn-danger elimFa";
    const iconoBoton0 = document.createElement('i');
    iconoBoton0.className = "fas fa-solid fa-minus";
    btnEliminarFa.appendChild(iconoBoton0);
    thFa1.appendChild(btnEliminarFa);

    // Boton para eliminar una fase
    btnEliminarFa.addEventListener('click', function() {
        const fase = btnEliminarFa.closest('table');
        if (fase) {
            fase.remove();
            if (typeof enumerarFases === 'function') {
                enumerarFases();
            }
        }
        return;
    })    

    //Segunda fila del encabezado
    const trFa1 = document.createElement('tr');
    const thFa2 = document.createElement('td');
    thFa2.textContent = "#";
    const thFa3 = document.createElement('td');
    thFa3.textContent = "Medicamento";
    thFa3.style.textAlign = "center";
    const thFa4 = document.createElement('td');
    thFa4.textContent = "Via de administracion";
    thFa4.style.textAlign = "center";
    const thFa5 = document.createElement('th');




    const btnCrearFila = document.createElement('button');
    btnCrearFila.type = "button";
    btnCrearFila.className = "btn btn-success aggMed";
    const iconoBoton1 = document.createElement('i');
    iconoBoton1.className = "fas fa-solid fa-plus";
    btnCrearFila.appendChild(iconoBoton1);
    thFa5.appendChild(btnCrearFila);

    // Union de elementos del encabezado
    trFa0.appendChild(thFa0);
    trFa0.appendChild(thFa1);
    trFa1.appendChild(thFa2);
    trFa1.appendChild(thFa3);
    trFa1.appendChild(thFa4);
    trFa1.appendChild(thFa5);
    theadTabla.appendChild(trFa0);
    theadTabla.appendChild(trFa1);
    tablaFa.appendChild(theadTabla);

    // Creacion del cuerpo de la tabla
    const tbodyFa = document.createElement('tbody');

    // Creacion de fila inicial para la seleccion de medicamento
    agregarMed(tbodyFa);
    
    // Union de elementos del cuerpo
    tablaFa.appendChild(tbodyFa);
    
    // Union de la tabla al contenedor
    contenedorTabla.appendChild(tablaFa);
    enumerarFases();
    enumerarMed(tbodyFa);

    // Boton para la creacion de nueva fila para la seleccion de medicamento
    btnCrearFila.addEventListener('click', function() {
        agregarMed(tbodyFa);
    });

}

// Boton de crear nueva fase
document.getElementById('btnNuevaFase').addEventListener('click', () => {
    crearFase();
    enumerarFases();
});

// Control de envio y respuesta de registro de protocolo
document.addEventListener('DOMContentLoaded', function() {
    crearFase();

    const formularioProt = document.getElementById('formRegProt');
    const registroModal = new bootstrap.Modal(document.getElementById('registrandoModalProt'));

    if (formularioProt) {
        formularioProt.addEventListener('submit', async function(prot) {
            const confirmacion = confirm("¿Está seguro de registrar este nuevo protocolo?");
            prot.preventDefault();
            if (confirmacion) {

                const datos = new FormData(formularioProt);
                registroModal.show();

                try {
                    const respuesta = await fetch(formularioProt.action, {
                        method: 'POST',
                        body: datos,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const resultado = await respuesta.json();

                    if(resultado.status === "exito") {
                        let alertaProt = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-check"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        formularioProt.reset();
                        document.getElementById('fasesProt').innerHTML = '';
                        document.getElementById('alertCreateProt').innerHTML = alertaProt;
                        listarProtocolo();
                        setTimeout(function (){
                            document.getElementById('alertCreateProt').innerHTML = "";
                        }, 3000);

                        formularioProt.reset();
                    }else if(resultado.status === "error") {
                        let alertaProt = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-xmark"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertCreateProt').innerHTML = alertaProt;
                        setTimeout(function (){
                            document.getElementById('alertCreateProt').innerHTML = "";
                        }, 3000);

                    }else if (resultado.status === "errores") {
                        for (let campo in resultado.errores) {
                        const elemento = document.getElementById(campo);
                            if (elemento) {
                            const originalValue = elemento.value;
                            elemento.className = 'form-control border border-danger text-danger';
                            elemento.value = resultado.errores[campo]; 
                            elemento.style.pointerEvents = 'none';

                                setTimeout(function(){
                                  elemento.className = 'form-control';
                                  elemento.style.pointerEvents = '';
                                  elemento.value = originalValue;
                                }, 3000);
                            }
                        }
                    }
                } catch (error) {
                    console.error("Error:", error);
                } finally {
                    setTimeout(() => {
                    registroModal.hide();
                    }, 500);
                }
            }
        });
    }
});