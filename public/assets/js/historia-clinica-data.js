// Historia clínica: datos ficticios alineados visualmente con estructura.sql.
// Este archivo debe reemplazarse por datos reales solo mediante una integración backend autorizada.
window.PST3ClinicalHistoryData = Object.freeze([
        {
            id: 'demo-paciente-001',
            hc: 'DEMO-HC-001',
            firstName: 'Paciente de demostración A',
            lastName: 'Ficha ilustrativa',
            birthDate: '2017-05-14',
            sex: 'Demostración',
            origin: 'Procedencia de ejemplo',
            phone: 'Dato de muestra',
            bloodType: 'O+ (muestra)',
            status: 'Ficha ficticia',
            representative: {
                name: 'Representante de muestra A',
                id: 'DEMO-REP-001',
                relationship: 'Ejemplo'
            },
            classification: {
                consultation: 'Primera consulta',
                pathology: 'Hemopatía (ejemplo)',
                diagnosis: 'Diagnóstico demostrativo',
                cie10: 'DEMO-CIE-001'
            },
            initial: {
                date: '03/03/2025',
                reason: 'Motivo de consulta de demostración; texto de ejemplo para conectar anamnesis.',
                personalHistory: {
                    hospitalizations: 'Ejemplo: dato pendiente de conectar',
                    allergies: 'Ejemplo: dato pendiente de conectar',
                    surgeries: 'Ejemplo: dato pendiente de conectar',
                    trauma: 'Ejemplo: dato pendiente de conectar',
                    epidemiological: 'Ejemplo: dato pendiente de conectar'
                },
                familyHistory: {
                    consanguinity: 'Ejemplo: dato pendiente de conectar',
                    siblings: 'Ejemplo de cantidad',
                    hematologic: 'Ejemplo: antecedentes familiares pendientes'
                },
                symptoms: [
                    'Fiebre: ejemplo',
                    'Pérdida de peso: ejemplo',
                    'Sudoración nocturna: ejemplo',
                    'Dolor óseo: ejemplo',
                    'Sangrado y localización: ejemplo'
                ],
                exam: {
                    general: 'Ejemplo: condición general',
                    pallor: 'Ejemplo: grado de palidez',
                    skinMucosa: 'Ejemplo: piel y mucosas',
                    lymphNodes: 'Ejemplo: cervicales, axilares e inguinales',
                    hepatomegaly: 'Ejemplo: hallazgo',
                    splenomegaly: 'Ejemplo: hallazgo',
                    masses: 'Ejemplo: hallazgo'
                },
                vitals: {
                    weight: 'Ejemplo kg',
                    height: 'Ejemplo cm',
                    surface: 'Ejemplo m²',
                    heartRate: 'Ejemplo lpm',
                    respiratoryRate: 'Ejemplo rpm'
                },
                hemogram: {
                    'Hemoglobina (HB)': 'Ejemplo g/dL',
                    'Hematocrito (Hcto)': 'Ejemplo %',
                    'VCM': 'Ejemplo fL',
                    'HCM': 'Ejemplo pg',
                    'CHCM': 'Ejemplo g/dL',
                    'Glóbulos blancos': 'Ejemplo /µL',
                    'Neutrófilos': 'Ejemplo %',
                    'Linfocitos': 'Ejemplo %',
                    'Monocitos': 'Ejemplo %',
                    'Eosinófilos': 'Ejemplo %',
                    'Plaquetas': 'Ejemplo /µL'
                },
                chemistry: {
                    'Glicemia': 'Ejemplo',
                    'Urea': 'Ejemplo',
                    'Creatinina': 'Ejemplo',
                    'TGO / TGP': 'Ejemplo',
                    'LDH': 'Ejemplo',
                    'Fósforo': 'Ejemplo',
                    'Ácido úrico': 'Ejemplo',
                    'Albúmina': 'Ejemplo',
                    'Calcio': 'Ejemplo',
                    'Bilirrubina': 'Ejemplo',
                    'Serologías virales': 'Ejemplo: resultados pendientes'
                },
                specialStudies: [
                    'Electroforesis de hemoglobina: ejemplo',
                    'Ecografía abdomino-renal / partes blandas: ejemplo',
                    'Biopsia de médula ósea: ejemplo',
                    'Citometría de flujo: ejemplo',
                    'Inmunohistoquímica: ejemplo'
                ],
                plan: {
                    medication: 'Plan demostrativo; no es una indicación médica.',
                    requestedLabs: 'Ejemplo: laboratorios pendientes',
                    referrals: 'Ejemplo: interconsultas pendientes',
                    nextAppointment: 'Fecha de ejemplo'
                }
            },
            treatment: {
                protocol: 'Protocolo de demostración',
                cycle: 'Ejemplo de ciclo',
                sessions: 'Ejemplo de sesiones',
                transfusions: 'Ejemplo de transfusiones'
            },
            controls: [
                {
                    date: '17/03/2025',
                    type: 'Consulta de control (ejemplo)',
                    subjective: 'Evolución de demostración: avances, síntomas y eventos adversos por conectar.',
                    symptoms: 'Ejemplo: fiebre, sangrados, aftas y eventos adversos.',
                    vitals: 'Peso, talla, SC, FC y FR: valores de muestra.',
                    exam: 'Palidez, adenopatías y organomegalias: hallazgos de muestra.',
                    treatment: 'Protocolo, ciclo, sesión y transfusiones: datos de ejemplo.',
                    labs: 'Resultados de control y procedimientos: datos de ejemplo.',
                    plan: 'Ajustes, estudios pendientes y próxima cita: plan demostrativo.',
                    nextAppointment: 'Fecha de ejemplo'
                }
            ]
        },
        {
            id: 'demo-paciente-002',
            hc: 'DEMO-HC-002',
            firstName: 'Paciente de demostración B',
            lastName: 'Ficha ilustrativa',
            birthDate: '2015-09-22',
            sex: 'Demostración',
            origin: 'Procedencia de ejemplo',
            phone: 'Dato de muestra',
            bloodType: 'A+ (muestra)',
            status: 'Ficha ficticia',
            representative: {
                name: 'Representante de muestra B',
                id: 'DEMO-REP-002',
                relationship: 'Ejemplo'
            },
            classification: {
                consultation: 'Primera consulta',
                pathology: 'Tumor benigno (ejemplo)',
                diagnosis: 'Diagnóstico demostrativo B',
                cie10: 'DEMO-CIE-002'
            },
            initial: {
                date: '11/04/2025',
                reason: 'Registro ficticio para probar la búsqueda por nombre o historia clínica.',
                personalHistory: {
                    hospitalizations: 'Ejemplo: dato pendiente de conectar',
                    allergies: 'Ejemplo: dato pendiente de conectar',
                    surgeries: 'Ejemplo: dato pendiente de conectar',
                    trauma: 'Ejemplo: dato pendiente de conectar',
                    epidemiological: 'Ejemplo: dato pendiente de conectar'
                },
                familyHistory: {
                    consanguinity: 'Ejemplo: dato pendiente de conectar',
                    siblings: 'Ejemplo de cantidad',
                    hematologic: 'Ejemplo: antecedentes familiares pendientes'
                },
                symptoms: [
                    'Fiebre: ejemplo',
                    'Pérdida de peso: ejemplo',
                    'Sudoración nocturna: ejemplo',
                    'Dolor óseo: ejemplo',
                    'Sangrado y localización: ejemplo'
                ],
                exam: {
                    general: 'Ejemplo: condición general',
                    pallor: 'Ejemplo: grado de palidez',
                    skinMucosa: 'Ejemplo: piel y mucosas',
                    lymphNodes: 'Ejemplo: cervicales, axilares e inguinales',
                    hepatomegaly: 'Ejemplo: hallazgo',
                    splenomegaly: 'Ejemplo: hallazgo',
                    masses: 'Ejemplo: hallazgo'
                },
                vitals: {
                    weight: 'Ejemplo kg',
                    height: 'Ejemplo cm',
                    surface: 'Ejemplo m²',
                    heartRate: 'Ejemplo lpm',
                    respiratoryRate: 'Ejemplo rpm'
                },
                hemogram: {
                    'Hemoglobina (HB)': 'Ejemplo g/dL',
                    'Hematocrito (Hcto)': 'Ejemplo %',
                    'VCM': 'Ejemplo fL',
                    'HCM': 'Ejemplo pg',
                    'CHCM': 'Ejemplo g/dL',
                    'Glóbulos blancos': 'Ejemplo /µL',
                    'Neutrófilos': 'Ejemplo %',
                    'Linfocitos': 'Ejemplo %',
                    'Monocitos': 'Ejemplo %',
                    'Eosinófilos': 'Ejemplo %',
                    'Plaquetas': 'Ejemplo /µL'
                },
                chemistry: {
                    'Glicemia': 'Ejemplo',
                    'Urea': 'Ejemplo',
                    'Creatinina': 'Ejemplo',
                    'TGO / TGP': 'Ejemplo',
                    'LDH': 'Ejemplo',
                    'Fósforo': 'Ejemplo',
                    'Ácido úrico': 'Ejemplo',
                    'Albúmina': 'Ejemplo',
                    'Calcio': 'Ejemplo',
                    'Bilirrubina': 'Ejemplo',
                    'Serologías virales': 'Ejemplo: resultados pendientes'
                },
                specialStudies: [
                    'Electroforesis de hemoglobina: ejemplo',
                    'Ecografía abdomino-renal / partes blandas: ejemplo',
                    'Biopsia de médula ósea: ejemplo',
                    'Citometría de flujo: ejemplo',
                    'Inmunohistoquímica: ejemplo'
                ],
                plan: {
                    medication: 'Plan demostrativo; no es una indicación médica.',
                    requestedLabs: 'Ejemplo: laboratorios pendientes',
                    referrals: 'Ejemplo: interconsultas pendientes',
                    nextAppointment: 'Fecha de ejemplo'
                }
            },
            treatment: {
                protocol: 'Sin protocolo conectado (ejemplo)',
                cycle: 'No aplica en la demostración',
                sessions: 'No aplica en la demostración',
                transfusions: 'No aplica en la demostración'
            },
            controls: []
        }
    ]);
