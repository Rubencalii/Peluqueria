# 💇 Sistema de Reservas para Peluquería

Este documento detalla el alcance, las funcionalidades y la estructura lógica del proyecto de gestión de reservas, optimizado para un entorno profesional.

## 📋 Resumen del Proyecto

El objetivo es crear una plataforma web que permita a los clientes reservar servicios de peluquería de forma autónoma, gestionar los pagos y automatizar los recordatorios para reducir el ausentismo.

---

## 🚀 Funcionalidades Principales (Alcance)

### 1. Sistema de Reservas (Frontend Cliente)

- **Interfaz SPA (Single Page Application):** Experiencia fluida con React para la selección de citas.
- **Selección de Servicio:** Listado de servicios con precio y duración.
- **Selección de Profesional:** Disponibilidad dinámica basada en el empleado elegido.
- **Calendario Inteligente:** Selección de franjas horarias evitando solapamientos en tiempo real.
- **Localización Total:** Soporte nativo para teclado español (tildes, "ñ") y formatos de fecha/moneda locales (DD/MM/AAAA y €).
- **Lista de Espera:** Sistema automático que notifica a clientes cuando se libera una franja horaria.
- **Reprogramación Inteligente:** Sugerencias automáticas de nuevas franjas ante cancelaciones.

### 2. Gestión de Pagos (Omnicanal)

El sistema gestionará el ciclo de vida financiero de la reserva:

- **Pago Online:** Pasarela Stripe integrada para cobros anticipados o fianzas.
- **Efectivo:** Marcado manual en la recepción para arqueo de caja.
- **Datáfono:** Registro de transacciones con tarjeta física en el local.
- **Política de Cancelaciones:** Configuración de penalizaciones por cancelación tardía.

### 3. Automatizaciones y Notificaciones

- **Confirmación Multi-canal:** Envío automático de Email y mensaje de WhatsApp al completar la reserva.
- **Recordatorios Activos:** Tarea programada (Cron Job) para enviar recordatorios 24h antes.
- **Fidelización:** Envío de ticket digital post-servicio y enlace a reseñas.
- **Notificaciones de Lista de Espera:** Alertas automáticas cuando se libera disponibilidad.

### 4. Panel de Administración (Backend)

- **Dashboard de Agenda:** Vista de calendario (maestro de día) para la peluquería.
- **Gestión de Recursos:** Control de horarios de empleados, vacaciones y especialidades.
- **Analítica de Negocio:** Reportes de ingresos filtrados por método de pago y profesional.
- **Sistema de Cajas:** Cuadre diario automático con desglose por método de pago.
- **Gestión de Comisiones:** Cálculo automático de comisiones por profesional según servicios realizados.

### 5. Gestión de Clientes (CRM)

- **Historial Completo:** Registro de todos los servicios realizados por cliente.
- **Preferencias Guardadas:** Profesional favorito, servicios habituales, frecuencia de visita.
- **Programa de Fidelización:** Sistema de puntos canjeables por descuentos o servicios.
- **Notas del Profesional:** Anotaciones privadas sobre preferencias, alergias o detalles técnicos del cliente.
- **Segmentación:** Clasificación de clientes por valor, frecuencia y recencia (análisis RFM).

### 6. Optimización de Agenda

- **Franjas Inteligentes:** Sugerencias basadas en histórico de cancelaciones y demanda.
- **Tiempos de Descanso:** Bloqueo automático entre citas para limpieza y descansos.
- **Predicción de Demanda:** Análisis de patrones por temporada, día de la semana y hora.
- **Tasa de Ocupación:** Métricas de aprovechamiento por profesional y franja horaria.

### 7. Gestión de Productos e Inventario

- **Catálogo de Productos:** Inventario de productos utilizados en servicios y venta retail.
- **Control de Stock:** Alertas automáticas de reposición al alcanzar stock mínimo.
- **Consumo por Servicio:** Trazabilidad de productos utilizados en cada cita.
- **Venta al Cliente:** Módulo de retail integrado con descuentos por fidelidad.

### 8. Experiencia de Usuario Mejorada

- **Portfolio Digital:** Galería de trabajos realizados por cada profesional.
- **Sistema de Reseñas:** Valoraciones y comentarios de clientes con respuesta del profesional.
- **Chat en Tiempo Real:** Consultas pre-reserva para dudas sobre servicios.
- **PWA (Progressive Web App):** Aplicación instalable en móvil sin necesidad de tiendas de apps.

### 9. Gestión Visual y Marketing Automatizado

- **Galería Antes/Después:** Subida de fotos de transformaciones con consentimiento del cliente.
- **Etiquetado Inteligente:** Clasificación por técnica, tipo de servicio y profesional para búsqueda rápida.
- **Generación de Contenido:** Creación automática de posts para redes sociales con watermark del negocio.
- **Campañas Automatizadas:**
  - Recuperación de clientes inactivos (+3 meses sin visita)
  - Ofertas personalizadas de cumpleaños
  - Cupones de descuento para referidos
  - Promociones por temporada o clima
- **Sistema de Referidos:** Código único por cliente con tracking de conversiones y recompensas escalonadas.

### 10. Facturación y Gestión Financiera Avanzada

- **Facturación Electrónica:** Generación y envío automático de facturas digitales post-servicio.
- **Integración Contable:** Exportación a software de contabilidad (QuickBooks, Sage, A3).
- **Control de IVA:** Gestión automática por tipo de servicio y régimen fiscal.
- **Gestión de Proveedores:** Registro de compras, histórico de precios y alertas de renovación.
- **Rentabilidad por Servicio:** Cálculo automático (precio - costo productos - comisión).
- **Conciliación Bancaria:** Comparativa automática entre ventas registradas y movimientos bancarios.

### 11. Gamificación y Fidelización Avanzada

- **Sistema de Logros:** Insignias por fidelidad, número de visitas o servicios probados.
- **Niveles de Cliente:** Clasificación escalonada (Bronce, Plata, Oro, Platino) con beneficios incrementales.
- **Desafíos Mensuales:** Retos para probar nuevos servicios con recompensas.
- **Programa de Afiliados:** Dashboard de referidos con recompensas automáticas (ej: 3 referidos = servicio gratis).

### 12. Gestión de Personal Avanzada

- **Formación y Certificaciones:** Registro de cursos, certificaciones y especializaciones del personal.
- **Calendario de Formación:** Programación de cursos internos y externos.
- **Evaluaciones de Desempeño:** Objetivos trimestrales/anuales con seguimiento automático.
- **Gestión de Turnos:** Sistema de turnos rotativos con solicitud y aprobación de vacaciones.
- **Intercambio de Turnos:** Los empleados pueden intercambiar turnos con aprobación del administrador.
- **Control de Horas:** Registro de horas extra y banco de horas.

### 13. Funcionalidades Multi-sede

- **Panel Consolidado:** Vista unificada de todas las sedes en un solo dashboard.
- **Transferencia de Citas:** Reprogramación de clientes entre diferentes locales.
- **Inventario Compartido:** Transferencia de stock entre sedes.
- **Personal Flotante:** Empleados que trabajan en múltiples ubicaciones con agenda sincronizada.
- **Comparativa de Rendimiento:** Análisis de KPIs por sede (ingresos, ocupación, satisfacción).
- **Configuración Independiente:** Horarios, servicios y precios personalizables por local.

### 14. Inteligencia Artificial y Análisis Predictivo

- **Asistente Virtual (Chatbot):** Respuestas automáticas a preguntas frecuentes 24/7.
- **Recomendador de Servicios:** Sugerencias personalizadas basadas en historial del cliente.
- **Predicción de Visitas:** Estimación de cuándo volverá cada cliente según patrones históricos.
- **Detección de Riesgo de Abandono:** Alertas de clientes que probablemente no vuelvan.
- **Forecasting de Demanda:** Predicción de servicios más demandados por temporada.
- **Optimización de Precios:** Sugerencias de ajuste de precios basadas en competencia y demanda.
- **Análisis de Sentimiento:** Evaluación automática de reseñas para detectar problemas recurrentes.

### 15. Integraciones con Redes Sociales

- **Publicación Automática:** Posts en Instagram/Facebook de trabajos realizados (con consentimiento).
- **Reservas desde Instagram:** Sistema de booking integrado en Instagram Business.
- **Importación de Mensajes:** Conversión de DMs en solicitudes de reserva.
- **Sincronización de Reseñas:** Consolidación de valoraciones de Google, Facebook y plataformas propias.
- **Stories Automáticas:** Generación de contenido efímero con promociones del día.

### 16. Gestión de Incidencias y Calidad

- **Sistema de Quejas:** Formulario post-servicio para reportar problemas.
- **Clasificación Automática:** Priorización de incidencias por gravedad.
- **Workflow de Resolución:** Asignación a responsables con seguimiento hasta cierre.
- **Registro de Incidentes:** Documentación de accidentes, alergias o problemas técnicos.
- **Alertas Médicas:** Avisos visibles al profesional sobre alergias o condiciones del cliente.
- **Consentimientos Digitales:** Firma electrónica para tratamientos especiales.

### 17. Comunicación Multicanal Avanzada

- **Centro de Notificaciones:** Panel unificado de todas las comunicaciones.
- **Plantillas Personalizables:** Editor de mensajes por tipo (confirmación, recordatorio, promoción).
- **A/B Testing:** Prueba de diferentes versiones de mensajes para optimizar conversión.
- **Multiidioma:** Soporte automático en inglés, francés y otros idiomas según preferencia del cliente.
- **Historial de Comunicaciones:** Registro completo de todos los mensajes enviados a cada cliente.

### 18. Sostenibilidad y Responsabilidad Social

- **Huella de Carbono:** Cálculo del impacto ambiental de productos utilizados.
- **Proveedores Eco-friendly:** Sugerencias de alternativas sostenibles.
- **Gestión de Residuos:** Registro de reciclaje y disposición responsable.
- **Donaciones Automáticas:** Porcentaje de cada servicio destinado a causas sociales.
- **Certificaciones Verdes:** Dashboard de cumplimiento de estándares ambientales.

---

## 🏗️ Estructura de Datos (Entidades)

- **User (Entity):** Gestiona tanto a Clientes como Empleados (Roles: `ROLE_CUSTOMER`, `ROLE_EMPLOYEE`, `ROLE_ADMIN`).
- **Service (Entity):** Nombre, descripción, duración en minutos y precio.
- **Appointment (Entity):** La pieza central. Almacena `start_at`, `end_at` (calculado), estados de cita y método de pago.
- **Payment (Entity):** Registro vinculado a la cita con ID de transacción y estado (pendiente/pagado).
- **Product (Entity):** Catálogo de productos con stock, precio de venta y precio de costo.
- **CustomerPreference (Entity):** Preferencias guardadas del cliente (profesional favorito, servicios frecuentes).
- **LoyaltyPoints (Entity):** Registro de puntos acumulados y canjeados por cliente.
- **ProfessionalNote (Entity):** Notas privadas del profesional sobre el cliente.
- **Review (Entity):** Valoraciones y comentarios de clientes sobre servicios recibidos.
- **WaitingList (Entity):** Cola de clientes esperando disponibilidad en fechas/horarios específicos.
- **CashRegister (Entity):** Registro de movimientos de caja diarios con cuadre automático.
- **BeforeAfterGallery (Entity):** Fotos de transformaciones con metadatos (servicio, profesional, técnica, consentimiento).
- **MarketingCampaign (Entity):** Campañas automatizadas con métricas de rendimiento (enviados, abiertos, conversiones).
- **ReferralCode (Entity):** Códigos únicos de referido por cliente con tracking de uso.
- **Invoice (Entity):** Facturas electrónicas con numeración correlativa y datos fiscales.
- **Supplier (Entity):** Proveedores con histórico de pedidos y condiciones comerciales.
- **Achievement (Entity):** Logros y badges disponibles con condiciones de desbloqueo.
- **CustomerLevel (Entity):** Niveles de fidelización con beneficios asociados.
- **Location (Entity):** Sedes del negocio con configuración independiente (multi-sede).
- **EmployeeShift (Entity):** Turnos de trabajo con disponibilidad por sede y horario.
- **TrainingRecord (Entity):** Registro de formaciones y certificaciones del personal.
- **Incident (Entity):** Incidencias reportadas con clasificación, estado y resolución.
- **Consent (Entity):** Consentimientos digitales firmados por clientes (RGPD, fotos, tratamientos).
- **SocialMediaPost (Entity):** Publicaciones programadas o automáticas en redes sociales.

---

## 🛠️ Stack Tecnológico

- **Backend:** Symfony 8.0+ (PHP 8.4+)
- **Frontend Interactivo:** Symfony UX + React 18.
- **Estilos:** Tailwind CSS v3.4 (diseño responsive y moderno).
- **Tipado:** TypeScript (para robustez en el frontend).
- **Base de Datos:** MariaDB 11.4+ (Puerto: `3307`).
- **Integraciones:**
  - **Stripe:** Pagos online.
  - **Twilio / Messenger API:** Notificaciones WhatsApp.
  - **Symfony Mailer:** Comunicaciones por Email.
  - **Meta Business API:** Integración con Instagram/Facebook.
  - **Google Calendar API:** Sincronización de agendas.
  - **Accounting APIs:** QuickBooks, Sage o A3 para facturación electrónica.

---

## 📊 Analítica y Reportes

### Métricas de Negocio

- **Ingresos:** Desglose por servicio, profesional, método de pago y período.
- **Tasa de Ocupación:** Porcentaje de utilización de agenda por profesional.
- **Servicios Más Demandados:** Ranking de servicios por volumen y facturación.
- **Análisis de Cancelaciones:** Tasa de no-show y motivos de cancelación.
- **ROI por Canal:** Rendimiento de canales de adquisición (redes sociales, buscadores, boca a boca).
- **Valor del Cliente (LTV):** Predicción de ingresos por cliente a lo largo del tiempo.
- **Tasa de Retención:** Porcentaje de clientes que repiten por período.
- **Ticket Medio:** Gasto promedio por visita y por cliente.

### Reportes Operativos

- **Cuadre de Caja:** Comparación automática entre ventas registradas y efectivo/tarjeta.
- **Comisiones:** Cálculo de comisiones por profesional según servicios realizados.
- **Inventario:** Estado de stock con proyección de necesidades.
- **Nóminas:** Vinculación de servicios realizados con liquidación de sueldos.
- **Rentabilidad Real:** Margen neto por servicio (precio - productos - comisión - gastos).
- **Eficiencia de Personal:** Ingresos generados vs horas trabajadas.

### Dashboard Ejecutivo

- **KPIs en Tiempo Real:** Ingresos del día, ocupación actual, servicios pendientes.
- **Comparativa Temporal:** Año actual vs año anterior, mes vs mes.
- **Proyección de Ingresos:** Estimación de cierre de mes basada en tendencias.
- **Alertas Automáticas:** Notificaciones de objetivos no cumplidos o anomalías.
- **Análisis Multi-sede:** Comparativa de rendimiento entre diferentes locales.

---

## 🔒 Seguridad y Cumplimiento

### RGPD (Reglamento General de Protección de Datos)

- **Consentimiento Explícito:** Aceptación clara del tratamiento de datos personales.
- **Derecho al Olvido:** Proceso automatizado de eliminación de datos a solicitud del cliente.
- **Portabilidad de Datos:** Exportación de información personal en formato estándar (JSON/CSV).
- **Registro de Auditoría:** Trazabilidad completa de accesos y modificaciones a datos sensibles.
- **Minimización de Datos:** Recopilación únicamente de datos estrictamente necesarios.
- **Política de Privacidad:** Generación automática y aceptación obligatoria.

### Seguridad Técnica

- **Autenticación 2FA:** Doble factor obligatorio para empleados y administradores.
- **Cifrado de Datos:** Encriptación de información sensible (datos de pago, notas médicas).
- **Backup Automático:** Copias de seguridad diarias con retención de 30 días y almacenamiento externo.
- **Control de Acceso:** Permisos granulares por rol (empleado, administrador, propietario).
- **Logs de Seguridad:** Registro de intentos de acceso fallidos y actividades sospechosas.
- **Certificado SSL:** Comunicación encriptada HTTPS en toda la plataforma.
- **Protección DDoS:** Mitigación de ataques mediante CDN (Cloudflare).
- **Sanitización de Inputs:** Prevención de inyecciones SQL y XSS.

---

## 🎯 Roadmap de Implementación

### Fase 1 - MVP (Mínimo Producto Viable) - [✅ COMPLETADO]

**Objetivo:** Sistema funcional básico para empezar operaciones

- [x] Arquitectura base y contenedores Docker (MariaDB)
- [x] Sistema de reservas (React Wizard integrado en Twig)
- [x] Gestión de servicios y profesionales (API + Admin)
- [x] Calendario con prevención de solapamientos (Lógica de ocupación)
- [x] Panel de administración High-Fidelity (Dashboard + CRM)
- [x] Autenticación Split-Screen (Login/Register LuxeSalon)

### Fase 2 - Pagos y Automatización - 2 meses

**Objetivo:** Reducir ausentismo y monetizar online

- Integración con Stripe (pagos anticipados)
- Recordatorios automáticos por WhatsApp
- Sistema de confirmación multi-canal
- Política de cancelaciones con penalizaciones
- Sistema de cajas y cuadre diario

### Fase 3 - CRM y Fidelización - 2-3 meses

**Objetivo:** Aumentar retención y valor del cliente

- Historial completo de clientes
- Programa de puntos y niveles
- Sistema de reseñas
- Notas del profesional sobre clientes
- Preferencias guardadas
- Lista de espera automática

### Fase 4 - Marketing y Contenido - 2 meses

**Objetivo:** Crecimiento orgánico y viral

- Galería antes/después con consentimiento
- Generación automática de contenido para RRSS
- Campañas automatizadas (inactivos, cumpleaños)
- Sistema de referidos con tracking
- Portfolio por profesional

### Fase 5 - Facturación y Finanzas - 1-2 meses

**Objetivo:** Cumplimiento legal y control financiero

- Facturación electrónica automática
- Integración con software contable
- Control de IVA por servicio
- Gestión de proveedores
- Rentabilidad por servicio
- Conciliación bancaria

### Fase 6 - Analítica y Optimización - 2 meses

**Objetivo:** Decisiones basadas en datos

- Dashboard ejecutivo con KPIs en tiempo real
- Predicción de demanda (IA básica)
- Análisis de ocupación y rentabilidad
- Reportes financieros completos
- Detección de riesgo de abandono
- Optimización de precios

### Fase 7 - Inventario y Retail - 1-2 meses

**Objetivo:** Nueva línea de ingresos

- Gestión de inventario de productos
- Venta de productos retail
- Control de stock con alertas
- Trazabilidad de consumo por servicio

### Fase 8 - Gamificación y Engagement - 1 mes

**Objetivo:** Aumentar frecuencia de visitas

- Sistema de logros e insignias
- Desafíos mensuales
- Programa de afiliados ampliado
- Dashboard de referidos

### Fase 9 - Gestión de Personal - 2 meses

**Objetivo:** Optimizar recursos humanos

- Gestión de turnos rotativos
- Solicitud de vacaciones
- Formación y certificaciones
- Evaluaciones de desempeño
- Control de horas y comisiones

### Fase 10 - Expansión Multi-sede - 3 meses

**Objetivo:** Preparar escalabilidad del negocio

- Panel consolidado multi-sede
- Transferencia de citas entre locales
- Personal flotante
- Inventario compartido
- Comparativa de rendimiento

### Fase 11 - IA y Automatización Avanzada - 3-4 meses

**Objetivo:** Operaciones autónomas e inteligentes

- Chatbot 24/7 para consultas
- Recomendador de servicios personalizado
- Predicción de visitas futuras
- Análisis predictivo de demanda
- Optimización dinámica de agenda

### Fase 12 - Integraciones Externas - 2-3 meses

**Objetivo:** Ecosistema conectado

- Integración con Instagram/Facebook
- Reservas desde redes sociales
- Sincronización con Google Calendar
- Publicación automática de contenido
- Integración con plataformas de terceros (Treatwell)

### Fase 13 - Experiencia Premium - 2 meses

**Objetivo:** Diferenciación competitiva

- App móvil nativa (iOS/Android)
- Notificaciones push avanzadas
- Modo offline
- Widget de próxima cita
- Geolocalización inteligente

### Fase 14 - Sostenibilidad y RSC - 1 mes

**Objetivo:** Compromiso social y ambiental

- Cálculo de huella de carbono
- Proveedores eco-friendly
- Gestión de residuos
- Donaciones automáticas
- Certificaciones verdes

---

## 💰 Estimación de Costos y ROI

### Costos de Desarrollo (Estimados)

- **Fase 1 (MVP):** 15,000€ - 25,000€
- **Fase 2-5 (Core Features):** 30,000€ - 50,000€
- **Fase 6-10 (Advanced Features):** 40,000€ - 70,000€
- **Fase 11-14 (Premium & Scaling):** 50,000€ - 90,000€

**Total Desarrollo Completo:** 135,000€ - 235,000€

### Costos Operativos Mensuales

- **Hosting & Infraestructura:** 50€ - 150€/mes
- **Stripe (comisión):** 1.5% + 0.25€ por transacción
- **Twilio (WhatsApp):** ~0.005€ por mensaje
- **Email (SendGrid/Mailgun):** 15€ - 50€/mes
- **Almacenamiento (fotos):** 20€ - 100€/mes
- **Total Estimado:** 100€ - 400€/mes (depende del volumen)

### ROI Esperado (Ejemplo: Peluquería mediana)

**Situación Actual (sin sistema):**

- 30 citas/día × 25€ promedio = 750€/día
- 20% de no-shows (cancelaciones de último minuto) = -150€/día
- Ingreso real: 600€/día × 25 días = 15,000€/mes

**Con el Sistema (después de Fase 5):**

- Reducción de no-shows del 20% al 5% = +112.5€/día
- Upselling por recomendaciones (+10% ticket medio) = +75€/día
- Marketing automatizado recupera 5 clientes/mes = +625€/mes
- Programa de referidos: 3 clientes nuevos/mes = +450€/mes
- **Ingreso adicional estimado:** +3,450€/mes

**Retorno de Inversión:**

- Inversión Fase 1-5: ~70,000€
- Ingreso adicional: 3,450€/mes
- **ROI: 20 meses aproximadamente**

**Beneficios Intangibles:**

- Imagen profesional mejorada
- Reducción de tiempo administrativo (15h/semana = 900€/mes en costos)
- Mejor experiencia del cliente → más reseñas positivas
- Datos para decisiones estratégicas

---

## 🎓 Consideraciones de Implementación

### Mejores Prácticas

1. **Desarrollo Iterativo:** Lanzar MVP rápido y mejorar con feedback real.
2. **Testing Continuo:** Pruebas con usuarios reales en cada fase.
3. **Mobile First:** Diseño pensado primero para móviles (80% de reservas).
4. **Performance:** Optimización de carga (<3 segundos).
5. **Accesibilidad:** Cumplimiento WCAG 2.1 nivel AA.
6. **SEO:** Optimización para buscadores (Google My Business integration).

### Riesgos y Mitigación

- **Complejidad Técnica:** Empezar simple, escalar gradualmente.
- **Resistencia al Cambio:** Formación intensiva del personal y soporte continuo.
- **Dependencia de Internet:** Modo offline para consultas básicas.
- **Competencia:** Diferenciación por experiencia de usuario y automatización.
- **Costos de Mantenimiento:** Presupuesto del 15-20% anual del costo de desarrollo.

### Factores de Éxito

✅ Compromiso del equipo de la peluquería
✅ Formación adecuada del personal
✅ Comunicación clara del cambio a clientes
✅ Monitoreo constante de métricas
✅ Iteración basada en feedback
✅ Marketing del nuevo sistema (incentivos para primeros usuarios)

---

## 📞 Soporte y Mantenimiento

### Niveles de Soporte

- **Básico:** Resolución de incidencias en 48h, actualizaciones trimestrales.
- **Estándar:** Soporte en 24h, actualizaciones mensuales, ajustes menores.
- **Premium:** Soporte prioritario <8h, actualizaciones semanales, nuevas funcionalidades personalizadas.

### Plan de Mantenimiento

- **Actualizaciones de Seguridad:** Parches críticos en <24h.
- **Mejoras de Performance:** Optimizaciones trimestrales.
- **Nuevas Funcionalidades:** Roadmap semestral con priorización por impacto.
- **Backup y Recuperación:** Pruebas mensuales de restauración.
- **Monitoreo 24/7:** Alertas automáticas de caídas o errores críticos.

---

## 🌟 Casos de Uso Específicos

### Para el Cliente

1. **Juan quiere corte de pelo:**
   - Entra a la web desde su móvil
   - Selecciona "Corte de pelo hombre" (25€, 30 min)
   - Elige a su estilista favorito Pedro
   - Ve disponibilidad en tiempo real
   - Reserva para mañana a las 17:00
   - Paga 5€ de fianza por Stripe
   - Recibe confirmación por email y WhatsApp
   - 24h antes recibe recordatorio automático
   - Acude puntual, paga el resto en efectivo
   - Recibe factura electrónica por email
   - Se le suma 1 punto de fidelidad

2. **María descubre la peluquería por Instagram:**
   - Ve un antes/después impresionante
   - Hace clic en "Reservar" desde el post
   - Crea cuenta rápidamente
   - Usa código de descuento del 10% para nuevos clientes
   - Completa su primera visita
   - Queda encantada, recibe código de referido
   - Refiere a 2 amigas, obtiene servicio gratis

### Para el Empleado

1. **Pedro, estilista senior:**
   - Inicia sesión en su panel
   - Ve su agenda del día con 8 citas
   - Recibe notificación: cliente de las 10:00 canceló
   - Sistema ofrece la franja a lista de espera automáticamente
   - 10 minutos después, nueva reserva confirmada
   - Antes de cada cita, ve notas sobre preferencias del cliente
   - Al finalizar, marca servicio como completado
   - Sistema genera automáticamente post para Instagram con la transformación
   - Al final del día, ve sus comisiones acumuladas: 78€

2. **Laura, recepcionista:**
   - Llega el primer cliente sin reserva
   - Consulta disponibilidad en tiempo real
   - Encuentra hueco a las 11:30
   - Registra cita walk-in en 15 segundos
   - Cliente paga con tarjeta en datáfono
   - Registra pago en el sistema
   - Al final del día, hace cuadre de caja: todo coincide automáticamente

### Para el Administrador

1. **Carmen, dueña de la peluquería:**
   - Abre dashboard ejecutivo en su móvil
   - Ve que hoy llevan 540€ (vs 600€ objetivo)
   - Nota que el servicio "Mechas balayage" tiene 3 semanas de baja demanda
   - Crea campaña automática: 15% descuento en mechas para próxima semana
   - Sistema envía mensaje a 47 clientas que ya lo han hecho antes
   - 12 reservas nuevas en 2 horas
   - Objetivo del día cumplido con creces
   - Revisa reseñas: 4.8/5 promedio este mes
   - Programa formación de "Cortes tendencia 2026" para el equipo

---

## 📍 Estado de Implementación (Febrero 2026)

| Módulo              | Estado        | Descripción                                     |
| :------------------ | :------------ | :---------------------------------------------- |
| **Infraestructura** | ✅ Estable    | MariaDB 11 (3307), Symfony 8, Docker Compose.   |
| **Auth**            | ✅ Finalizado | Login/Registro con diseño split-screen premium. |
| **Admin Dashboard** | ✅ Finalizado | KPIs, Agenda "Maestro de Día" y Alertas.        |
| **CRM**             | ✅ Finalizado | Ficha de cliente con Galería y Notas Técnicas.  |
| **Booking Wizard**  | ✅ Funcional  | Flujo React de 3 pasos integrado.               |
| **Notificaciones**  | ⏳ Pendiente  | Integración con Mailer/WhatsApp (Fase 2).       |

_Última actualización: 11 de Febrero, 2026_
