# Manual Técnico

## Sistema de Gestión Clínica y Administrativa del Servicio de Hematología
### «Dr. Walles C.» — Hospital Pediátrico «Dr. J.M. de los Ríos»

**Tipo de documento:** Borrador técnico  
**Enfoque:** Informe de ingeniería de software (PNFI)  
**Base de elaboración:** Código fuente, rutas, controladores, modelos, configuración y SQL de la migración del repositorio. No se utilizaron como evidencia los contenidos de `.atl/`, `docs/` ni `openspec/`.  
**Estado:** Requiere revisión técnica, clínica y de infraestructura antes de aprobarse.

> **Criterio de lectura:** Las afirmaciones describen el código inspeccionado, no una certificación de que el sistema esté desplegado o validado en producción. La marca **[POR ADJUNTAR / VERIFICAR]** identifica evidencia, definición o artefacto que no se pudo confirmar en esas fuentes.

## 1. Arquitectura del Sistema y Stack Tecnológico

### 1.1 Visión arquitectónica

La aplicación sigue la organización MVC ofrecida por Laravel:

1. **Presentación:** vistas Blade en `resources/views/`, recursos estáticos y componentes compartidos. Las vistas consumen rutas web y, en varios casos, endpoints JSON.
2. **Control:** controladores en `app/Http/Controllers/` reciben solicitudes, coordinan validación, reglas de aplicación y acceso a datos, y devuelven vistas, JSON, redirecciones o archivos PDF.
3. **Datos:** modelos Eloquent en `app/Models/` declaran tablas, claves primarias y relaciones; algunos controladores también consultan directamente mediante `DB`.
4. **Persistencia:** PostgreSQL está configurado como conexión disponible y el esquema de negocio está descrito por `database/estructura.sql`, ejecutado desde una migración de Laravel.

No se observa una separación uniforme en una capa de servicios o repositorios. Hay, al menos, una clase de métricas para el tablero médico bajo `app/Services/`; la lógica de otros procesos reside directamente en los controladores. **[POR ADJUNTAR / VERIFICAR]** Diagrama de componentes y justificación formal de la arquitectura.

### 1.2 Stack confirmado y límites de versión

| Componente | Evidencia del repositorio | Alcance / observación |
|---|---|---|
| Framework | Laravel `^12.0` en `composer.json` | La versión instalada en un entorno concreto debe verificarse con Composer. |
| PHP | `^8.2` en `composer.json` | Requisito declarado por Composer; validar extensiones y versión exacta en despliegue. |
| Base de datos | Conexión `pgsql` en `config/database.php`; esquema con sintaxis PostgreSQL | El servidor PostgreSQL 18 no queda impuesto por el esquema ni por Composer. La ruta predeterminada de `pg_dump` apunta a PostgreSQL 18 en Windows, pero eso no demuestra la versión del servidor. **[POR ADJUNTAR / VERIFICAR]** Versión y parámetros aprobados del servidor. |
| Vistas | Blade | Plantillas por área funcional y layouts/partials compartidos. |
| Interfaz | Assets referenciados desde `public/assets/adminlte/`; AdminLTE y Bootstrap 4 referenciados en las vistas | Hay además bibliotecas externas referenciadas por URL. **[POR ADJUNTAR / VERIFICAR]** Inventario de versiones, licencias y política de actualización de assets. |
| PDF | `barryvdh/laravel-dompdf` `^3.1` | Utilizado para reportes PDF en controladores. |

`config/database.php` conserva SQLite como conexión predeterminada si `DB_CONNECTION` no está definido. Para el entorno PostgreSQL esperado se requiere configurar explícitamente `DB_CONNECTION=pgsql` y los parámetros `DB_*` correspondientes.

### 1.3 Estructura relevante y responsabilidades

| Ruta | Responsabilidad observada |
|---|---|
| `app/Http/Controllers/` | Controladores de autenticación, administración, citas, pacientes, consultas, tratamientos, enfermería y catálogos. |
| `app/Http/Requests/` | Validaciones especializadas mediante `FormRequest`; su uso no es uniforme en todos los flujos. |
| `app/Http/Middleware/` | `CheckRole` compara el `id_rol` autenticado con el rol requerido por la ruta. |
| `app/Models/` | Modelos Eloquent y relaciones con tablas del esquema `usuarios`, `logistica` y tablas del esquema `public`. |
| `app/Services/` | Servicios de aplicación; se observa el servicio de métricas del tablero médico. |
| `routes/web.php` | Rutas web públicas, autenticadas y agrupadas por rol. |
| `routes/console.php` | Punto de definición de comandos de consola. |
| `database/migrations/` | Migración que carga el SQL del esquema. |
| `database/estructura.sql` | DDL de esquemas, tablas, claves primarias y claves foráneas. |
| `resources/views/` | Interfaces Blade organizadas por administrador, administrativo, médico, enfermería, autenticación, reportes y componentes comunes. |
| `public/assets/` | Recursos estáticos locales referenciados por las vistas, incluidos assets de AdminLTE. |
| `bootstrap/app.php` | Registro del alias de middleware `role` y configuración de rutas y excepciones. |
| `config/` | Configuración del framework, incluida la conexión de base de datos. |

## 2. Modelo de Base de Datos y Diccionario Técnico

### 2.1 Organización lógica

El SQL define los esquemas `usuarios`, `logistica` y `desechable`; las tablas `persona`, `estado`, `municipio` y `parroquia` se crean sin calificar el esquema, por lo que corresponden al esquema predeterminado de PostgreSQL, normalmente `public`.

Los identificadores primarios se declaran como enteros `GENERATED BY DEFAULT AS IDENTITY`, salvo la clave compuesta de `desechable.paciente_representante`. Varias columnas de estado son enteros o cadenas, pero el DDL no establece un catálogo ni restricciones `CHECK` que formalicen todos sus valores.

### 2.2 Diccionario resumido de tablas

El siguiente diccionario se deriva de `database/estructura.sql`. Las columnas enumeradas son las más relevantes para entender la función de la tabla, no un reemplazo del DDL.

| Tabla | Clave primaria | Función y atributos relevantes |
|---|---|---|
| `usuarios.usuario` | `usuario_id` | Cuenta de acceso: `persona_id`, `username` (único), `password_hash`, `status`, `id_rol`. |
| `usuarios.rol` | `rol_id` | Catálogo de roles, con `nombre`. El SQL no inserta ni asigna nombres de rol. |
| `usuarios.auditoria` | `auditoria_id` | Registro de `descripcion`, `modulo`, `id_usuario`, `fecha_hora` (por defecto `now()`) y `accion`. |
| `persona` | `persona_id` | Datos de identidad y contacto; `cedula` es única; incluye fecha de nacimiento, sexo y referencias geográficas. |
| `estado` | `estado_id` | Catálogo de estados; `nombre` es único y obligatorio. |
| `municipio` | `municipio_id` | Catálogo de municipios; `nombre` es obligatorio. |
| `parroquia` | `parroquia_id` | Catálogo de parroquias; `nombre` es obligatorio. |
| `logistica.paciente` | `paciente_id` | Identidad clínica vinculada a `persona`; `hc` es única y `status` es entero. |
| `logistica.cita` | `cita_id` | Datos de agenda, nombres de paciente/representante, número de historia, fecha/hora y referencia a consulta. |
| `logistica.consulta` | `consulta_id` | Episodio clínico: paciente, médico, tipo, enfermedad, fecha, signos/mediciones, antecedentes, síntomas, plan, próxima cita y estado. Varias mediciones están definidas como `text`. |
| `logistica.enfermedad` | `enfermedad_id` | Catálogo con `tipo` y `descripcion`. |
| `logistica.protocolo` | `protocolo_id` | Catálogo de protocolos, con nombre obligatorio. |
| `logistica.fase` | `fase_id` | Número de fase del protocolo. |
| `logistica.medicina` | `medicina_id` | Catálogo de medicamentos, con descripción. |
| `logistica.medicina_pro` | `id` | Asociación protocolo-medicamento-fase; incluye vía de administración. |
| `logistica.tratamiento` | `tratamiento_id` | Registro de tratamiento con `status` entero. |
| `logistica.medicina_trat` | `id` | Detalle de medicamento asociado a paciente, consulta, protocolo, médico y tratamiento; incluye vía, omisión y estado. |
| `logistica.observacion` | `observacion_id` | Observación de enfermería vinculada a paciente, enfermero y tratamiento; incluye gravedad, fecha/hora, descripción y acciones. |
| `desechable.antecedente_familiar` | `ant_fam_id` | Antecedentes familiares por paciente/consulta, parentesco, patología y descripción. |
| `desechable.examen` | `examen_id` | Catálogo de exámenes, unidad, valores de referencia y estado de texto. |
| `desechable.frotis_fsp` | `fsp_id` | Datos de frotis y observaciones asociados a consulta. |
| `desechable.representante` | `representante_id` | Persona representante y ocupación. |
| `desechable.resultado_laboratorio` | `resultado_id` | Resultado, examen, orden, valor y estado. |
| `desechable.tipaje_sanguineo` | `tipaje_id` | Información de tipaje, fenotipo, genética transfusional, método y usuario registrador. |
| `desechable.transfusion` | `transfusion_id` | Transfusión, paciente, unidad, consulta, fecha/hora, volumen y estado. |
| `desechable.unidad_hemocomponente` | `unidad_id` | Unidad de hemocomponente, código de bolsa único, componente, grupo/Rh, volumen, vencimiento y estado. |
| `desechable.orden_laboratorio` | `orden_id` | Solicitud vinculada a consulta, tipo de seguimiento, fecha y estado. |
| `desechable.paciente_representante` | `(paciente_id, representante_id)` | Asociación paciente-representante, parentesco e indicador `es_principal`. Tiene clave primaria compuesta. |

> En el DDL, las tablas bajo `desechable` se crean, pero no se observan claves foráneas que conecten sus columnas de referencia con las tablas de negocio. La tabla puente `paciente_representante` tampoco declara claves foráneas en el SQL inspeccionado.

### 2.3 Relaciones declaradas

- `usuarios.usuario.persona_id` → `persona.persona_id`; `usuarios.usuario.id_rol` → `usuarios.rol.rol_id`.
- `usuarios.auditoria.id_usuario` → `usuarios.usuario.usuario_id`.
- `logistica.paciente.persona_id` → `persona.persona_id`.
- `logistica.consulta.paciente_id` → `logistica.paciente.paciente_id`; `medico_id` → `usuarios.usuario.usuario_id`; `enfermedad_id` → `logistica.enfermedad.enfermedad_id`.
- `logistica.cita.consulta_id` → `logistica.consulta.consulta_id`.
- `logistica.medicina_pro` referencia protocolo, medicina y fase.
- `logistica.medicina_trat` referencia paciente, consulta, protocolo, tratamiento, médico y medicina.
- `logistica.observacion` referencia paciente, enfermero y tratamiento.
- `persona` referencia estado, municipio y parroquia.

Las relaciones Eloquent principales reflejan parte de estas asociaciones (`User`, `Persona`, `Paciente`, `Consulta`, `Cita`, `Auditoria`, `MedicinaProt`, `MedicinaTrat`, `Tratamiento` y `Observacion`). La cardinalidad uno-a-uno entre cita y consulta no está garantizada por una restricción `UNIQUE` sobre `cita.consulta_id`.

### 2.4 Estados, restricciones y observaciones del esquema

- En `User`, el código define `STATUS_ACTIVO = 1`, `STATUS_VERIFICAR = 2` y `STATUS_INACTIVO = 0`. El login bloquea explícitamente las cuentas inactivas y maneja el estado pendiente de verificación.
- En las rutas y controladores, consultas y tratamientos existentes se filtran por estados numéricos (por ejemplo, consulta `0`/`1` y tratamiento `0`/`1`). No se encontraron restricciones `CHECK` que definan globalmente el significado y dominio de esos valores.
- Hay unicidad declarada para `usuario.username`, `persona.cedula`, `paciente.hc`, `estado.nombre`, `examen.nombre` y `unidad_hemocomponente.codigo_bolsa`; varias columnas obligatorias se declaran `NOT NULL`.
- El SQL inspeccionado no contiene tablas para ciclos/sesiones/salas ni para la variante de tratamiento con protocolo que se referencia desde el flujo médico actual. **[POR ADJUNTAR / VERIFICAR]** Migración/esquema vigente para el modelo de tratamientos por protocolo.
- La migración `2026_10_04_011159_cargar_esquema.php` elimina con `DROP SCHEMA ... CASCADE` los esquemas no reservados existentes antes de ejecutar el SQL. Por lo tanto, **no se debe ejecutar esta migración contra una base con datos que se deban conservar**. Debe revisarse y probarse en una base aislada antes de cualquier instalación o actualización.
- La migración separa el SQL por punto y coma y elimina líneas de comentarios mediante una expresión regular. **[POR ADJUNTAR / VERIFICAR]** Pruebas que acrediten que este mecanismo soporta íntegramente el SQL actual y futuras migraciones incrementales.

### 2.5 Lineamientos para el DER

El DER definitivo debe mostrar los tres esquemas y las relaciones declaradas anteriormente, identificando claves primarias, foráneas, nulabilidad, unicidad y cardinalidades. Diferenciar las relaciones formalmente impuestas por el DDL de las asociaciones inferidas por nombres de columnas o por Eloquent. No representar como integridad referencial las tablas auxiliares `desechable` o la tabla puente mientras no se añadan y verifiquen sus claves foráneas.

**[POR ADJUNTAR / VERIFICAR]** DER aprobado, con leyenda de esquemas, cardinalidades, restricciones, dominios de estado y correspondencia con la base realmente desplegada.

## 3. Flujos de Negocio y Diagramas

### 3.1 Autenticación y selección de área

1. La pantalla de acceso se sirve desde `/login`; el envío de credenciales llega al `LoginController`.
2. El controlador valida que el usuario introduzca un identificador numérico de 7 u 8 dígitos y una contraseña.
3. `User::findByCredentials()` busca por `username` o por cédula de la persona asociada. La contraseña se compara con `Hash::check` contra `password_hash`.
4. Se rechazan cuentas inactivas, se maneja el estado de verificación y, si las credenciales son válidas, se inicia la sesión y se regenera el identificador de sesión.
5. `/dashboard` redirige según `id_rol`: 1 administrador, 2 administrativo, 3 médico y 4 enfermería. El middleware `auth` protege las rutas privadas.

El código también expone rutas para recuperación/cambio de contraseña. El flujo y sus condiciones operativas deben probarse antes de describirlo como un proceso de recuperación con token completo. **[POR ADJUNTAR / VERIFICAR]** Política institucional de contraseñas, expiración de sesión, MFA y recuperación de cuenta.

### 3.2 Roles y permisos observados

Los grupos de `routes/web.php` combinan autenticación con `role:<id>`. Los nombres de área siguientes son los usados en el código y en las vistas; el mapeo oficial de `usuarios.rol.nombre` depende de datos que no aparecen sembrados en el SQL.

| ID aplicado en rutas | Área funcional | Operaciones visibles en rutas |
|---:|---|---|
| 1 | Administrador | Gestión de personal/usuarios, catálogos de enfermedades, medicamentos y protocolos, consulta de auditoría y descarga de respaldo. |
| 2 | Administrativo | Tablero administrativo, agenda de citas, consulta/exportación de citas y reprogramación. |
| 3 | Médico | Pacientes, historias clínicas, consultas y programación de tratamientos/sesiones. |
| 4 | Enfermería | Jornada de tratamientos y registro/consulta de observaciones. |

`CheckRole` compara `id_rol` con el identificador exigido y responde con 403 si no coincide. No se observa en este middleware una matriz granular por acción, objeto clínico o asignación del paciente. **[POR ADJUNTAR / VERIFICAR]** Matriz de autorización aprobada y nombres/identificadores cargados en el catálogo de roles.

### 3.3 Paciente, cita e historia clínica

- La agenda administrativa registra datos del paciente y representante, crea registros asociados de persona, paciente, consulta inicial y cita, y guarda una entrada de auditoría.
- El médico puede listar pacientes, consultar historias clínicas y registrar consultas. Los controladores muestran filtros de búsqueda y estados para separar consultas pendientes y realizadas.
- Los modelos relacionan al paciente con su persona y con consultas; una consulta referencia al paciente, médico y, opcionalmente, enfermedad.
- El esquema representa al representante en tablas auxiliares, pero la ruta efectiva de asociación y la integridad referencial de esas tablas requieren confirmación.

**[POR ADJUNTAR / VERIFICAR]** Reglas clínicas para transición de estados, criterios para crear consulta/cita y tratamiento de reprogramaciones, cancelaciones y registros duplicados.

### 3.4 Tratamientos y atención de enfermería

El controlador médico de tratamientos presenta protocolos, pacientes, consultas, fases, medicamentos y salas; al asignar protocolo verifica que la consulta corresponda al paciente y registra auditoría en una transacción. Para programar una sesión, verifica que el tratamiento esté activo y que el medicamento pertenezca al protocolo; luego crea sala si no existe, obtiene/crea el ciclo y registra la sesión y auditoría.

El rol de enfermería dispone de rutas de jornada, consulta de tratamientos y registro de observaciones. El código de enfermería existente procesa el cumplimiento de medicamentos, justificación de omisiones y observaciones asociadas al paciente y tratamiento.

**Limitación funcional importante:** los controladores y `FormRequest` del flujo de protocolo/sesiones hacen referencia a modelos/tablas tales como `ProtocoloTratamiento`, `ProtocoloCiclo`, `ProtocoloSesion` y `Sala`, incluyendo `logistica.protocolo_tratamiento`, `logistica.protocolo_sesion` y `logistica.sala`. Esos modelos/tablas no aparecen en `app/Models/` ni en `database/estructura.sql` inspeccionados; además, las rutas de formulario referencian una vista cuyo archivo no figura en el inventario actual. No puede afirmarse que este flujo esté operativo con el esquema incluido. **[POR ADJUNTAR / VERIFICAR]** Migración, modelos, vista, estados y pruebas de aceptación vigentes.

### 3.5 Diagramas requeridos

1. **Casos de Uso:** actores Administrador, Administrativo, Médico y Enfermería; asociar operaciones comprobadas en las rutas. **[POR ADJUNTAR / VERIFICAR]**
2. **Clases / modelo de dominio:** `User`, `Rol`, `Persona`, `Paciente`, `Cita`, `Consulta`, `Enfermedad`, `Protocolo`, `Medicina`, `Tratamiento`, `MedicinaTrat`, `Observacion` y `Auditoria`; distinguir las asociaciones ORM de las FKs declaradas. **[POR ADJUNTAR / VERIFICAR]**
3. **Secuencia de autenticación:** navegador → ruta/login → `LoginController` → `User`/base de datos → sesión → `/dashboard` → área por rol. **[POR ADJUNTAR / VERIFICAR]**
4. **BPMN o secuencia de cita y consulta:** administrativo registra/agende → médico atiende y registra consulta → se programa seguimiento, cuando aplique. **[POR ADJUNTAR / VERIFICAR]**
5. **BPMN o secuencia de tratamiento:** médico asigna protocolo y sesiones → enfermería revisa jornada y registra cumplimiento/observación. Debe completarse tras verificar las tablas y modelos faltantes. **[POR ADJUNTAR / VERIFICAR]**

## 4. Seguridad, Auditoría y Mantenimiento

### 4.1 Control de acceso y validación

- Las rutas funcionales se agrupan bajo `auth`; las áreas se protegen adicionalmente con el middleware `role` registrado en `bootstrap/app.php`.
- El modelo `User` oculta `password_hash` y `remember_token` en su serialización y declara `password_hash` como campo de autenticación de Laravel.
- Hay clases `FormRequest` para datos de paciente, consulta, tratamiento por protocolo, sesiones y observaciones; contienen reglas de formato, existencia, rango o rol según cada clase.
- La validación no está centralizada ni aplicada de forma uniforme: varios métodos de controladores usan `Request` y validación manual. La existencia de una clase `FormRequest` no demuestra que todos los endpoints relacionados la utilicen.
- Deben verificarse autorización por registro clínico, protección contra acceso horizontal, limitación de intentos de autenticación, configuración de HTTPS, gestión de secretos y políticas de sesión. **[POR ADJUNTAR / VERIFICAR]** Evidencia de pruebas de seguridad y configuración productiva.

### 4.2 Auditoría y trazabilidad

`usuarios.auditoria` almacena descripción, módulo, usuario, fecha/hora y acción. El modelo `Auditoria` y varios controladores crean eventos para actividades como consultas de listados, exportación, asignación de protocolo y descarga de respaldo. Algunas operaciones de tratamiento agrupan escritura clínica y auditoría dentro de una transacción.

**La inmutabilidad no está garantizada por el código revisado:** el DDL no declara triggers, reglas ni privilegios que impidan `UPDATE`/`DELETE`; tampoco se observa una política de retención o almacenamiento WORM. La aplicación registra eventos, pero eso no basta para certificar una bitácora inmutable. La auditoría tampoco se aplica a todos los cambios de forma centralizada. Además, algunos controladores obtienen el identificador de usuario desde parámetros de la solicitud, por lo que su procedencia y validación deben revisarse.

**[POR ADJUNTAR / VERIFICAR]** Requisitos de auditoría institucional, eventos obligatorios, protección contra alteración, retención, monitoreo y estrategia de revisión periódica.

### 4.3 Respaldo y restauración

Existe una ruta POST de respaldo dentro del grupo `admin` protegido por rol 1. El controlador invoca `pg_dump` mediante Symfony Process, usa configuración de la conexión `pgsql`, crea un archivo SQL temporal bajo `storage/app/backups`, registra la descarga y solicita su eliminación tras enviarlo. La ruta predeterminada de `pg_dump` es una instalación PostgreSQL 18 de Windows; puede modificarse mediante `PG_DUMP_BINARY`.

El respaldo de la aplicación usa formato SQL plano (`--format=plain`). El procedimiento operativo debe ejecutarse únicamente con autorización, credenciales protegidas y destino aprobado.

Ejemplo de respaldo manual equivalente en formato plano (sustituir los valores por secretos gestionados fuera del historial de comandos):

```powershell
pg_dump --host=<HOST> --port=<PUERTO> --username=<USUARIO> --dbname=<BASE> --format=plain --file=<RUTA_SEGURA>\hematologia.sql
```

La restauración de un archivo SQL plano se realiza con una herramienta cliente compatible, por ejemplo `psql`, hacia una base previamente creada y aprobada. Antes de restaurar, comprobar integridad, versión, destino y existencia de respaldo previo. Definir pruebas de restauración periódicas, cifrado, retención, ubicación externa y responsables.

**Advertencia:** la migración actual elimina esquemas no reservados. No combinar la restauración de datos con su ejecución hasta corregir y validar ese comportamiento en una copia aislada.

**[POR ADJUNTAR / VERIFICAR]** Calendario de respaldos automáticos, RPO/RTO, custodia, cifrado, retención, prueba de restauración y procedimiento institucional ante incidentes. La implementación inspeccionada describe una descarga bajo demanda, no un calendario automatizado.

### 4.4 Instalación y despliegue

Los pasos siguientes son una guía condicionada; no deben interpretarse como evidencia de un despliegue validado.

1. **Preparar el entorno:** instalar una versión de PHP compatible con `^8.2`, Composer y extensiones requeridas por Laravel y PDO PostgreSQL. Instalar PostgreSQL con versión y parámetros aprobados. **[POR ADJUNTAR / VERIFICAR]** Lista exacta de extensiones PHP, versiones de servidor soportadas y arquitectura de despliegue.
2. **Obtener dependencias:** en la raíz del proyecto, ejecutar `composer install --no-dev --optimize-autoloader` para producción (o `composer install` en desarrollo).
3. **Configurar variables:** crear `.env` a partir de una plantilla aprobada y mantenerla fuera del control de versiones. Definir al menos `APP_ENV`, `APP_DEBUG=false`, `APP_KEY`, `APP_URL`, `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` y las variables de correo si se habilitan notificaciones. No registrar contraseñas reales en este manual.
4. **Crear base de datos y usuario:** usar una base dedicada al sistema y un usuario con permisos mínimos necesarios. Configurar `PG_DUMP_BINARY` con la ruta absoluta y existente de `pg_dump` cuando se habilite el respaldo por aplicación.
5. **Revisar el esquema antes de migrar:** revisar la migración destructiva descrita en la sección 2.4. No ejecutar `php artisan migrate`, ni los scripts de instalación que lo invocan, contra datos existentes. Primero corregir la migración para que use cambios incrementales seguros y probarla con una base desechable y datos de ensayo.
6. **Aplicar esquema y datos iniciales:** tras la revisión y aprobación técnica, instalar el esquema y catálogos requeridos. El SQL incluido no aporta los nombres de roles ni todos los catálogos/datos iniciales necesarios. **[POR ADJUNTAR / VERIFICAR]** Seeders, estrategia de actualización y cuentas iniciales seguras.
7. **Verificar funcionalidades:** comprobar inicio/cierre de sesión, permisos de los cuatro roles, agenda, historia clínica, consultas, tratamientos, enfermería, auditoría, exportaciones PDF y respaldo/restauración en un entorno no productivo. Confirmar previamente el esquema faltante del flujo de tratamientos.
8. **Publicar:** apuntar el servidor web al directorio `public/`; habilitar HTTPS, permisos mínimos de escritura para `storage/` y `bootstrap/cache/`, logs y monitoreo. Configurar `APP_DEBUG=false` y limpiar/optimizar configuración y vistas conforme al procedimiento aprobado.
9. **Operación:** documentar responsables, ventanas de mantenimiento, revisión de logs, política de actualización de Composer y activos front-end, y procedimiento de reversa. **[POR ADJUNTAR / VERIFICAR]** Topología, proxy/TLS, gestión de secretos, CI/CD, monitoreo y plan de recuperación.

> No ejecutar automáticamente `composer setup` en un entorno con datos: el script definido por el proyecto incluye `php artisan migrate --force`, y la migración actual puede eliminar esquemas existentes.

### 4.5 Mantenimiento

- Mantener cambios de esquema versionados como migraciones incrementales y no destructivas; respaldar y probar restauración antes de cada cambio con riesgo de pérdida.
- Mantener alineados DDL, modelos Eloquent, `FormRequest`, rutas y pruebas automatizadas.
- Revalidar la matriz de roles y revisar periódicamente privilegios y registros de auditoría.
- Ejecutar pruebas de regresión en una base de ensayo con datos ficticios; no copiar datos clínicos reales a ambientes de desarrollo sin autorización y controles aprobados.
- Registrar versiones desplegadas de PHP, Laravel, PostgreSQL, extensiones y assets de interfaz en el acta de liberación. **[POR ADJUNTAR / VERIFICAR]** Procedimiento de mantenimiento, responsables y ventanas de servicio.
