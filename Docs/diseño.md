# 🎨 Diseño — Sistema de Gestión para Salón de Belleza

> Documento de diseño visual y UX del producto. Resumen claro, componentes, accesibilidad y recomendaciones.

---

## ✨ Visión general

**Resumen:** Un sistema con tres interfaces coherentes: **Dashboard Administrativo**, **Reservas para Clientes** y **CRM de Clientes**. El enfoque es moderno, limpio y pensado para productividad y escalabilidad.

- Público objetivo: administradores, estilistas y clientes.
- Objetivos: eficiencia operativa, experiencia cliente clara y gestión de fidelización.
  🧭 Identidad visual

### 🎨 Paleta principal

El sistema utiliza una paleta cuidadosamente seleccionada que transmite profesionalismo y confianza:

**Paleta:**

| Uso                        |           Color | Hex       |
| -------------------------- | --------------: | :-------- |
| Primario (acciones)        |   Azul vibrante | `#135BEC` |
| Primario claro (bg/acento) |      Azul suave | `#E0EAFF` |
| Light background           |  Gris muy claro | `#F6F6F8` |
| Dark background            | Azul muy oscuro | `#101622` |

Superficies:

Modo claro: Blanco puro
Modo oscuro: #1a2233 / #151b2b

### ✍️ Tipografía

- **Fuente principal:** Manrope (300–800)
- **Ventajas:** excelente legibilidad, aspecto moderno y buena escalabilidad para jerarquías de contenido

### 🧩 Iconografía

- **Set:** Google Material Icons
- **Uso:** `Outlined` para navegación / elementos generales, `Filled` para estados activos y énfasis

## 🖥️ Arquitectura de las interfaces

### 1) Dashboard Administrativo

**Estructura:**

- Sidebar fijo (256px) con navegación principal
- Área principal fluida con scroll
- Sistema de grillas CSS Grid para KPIs y contenido

Sidebar fijo (256px) con navegación principal
Área principal fluida con scroll
Sistema de grillas CSS Grid para KPIs y contenido

**Componentes clave:**

- **KPI Cards**: métricas grandes, iconos en contenedores y barras de progreso.
- **Maestro de Día**: grid (4 cols), timeline vertical y bloques de cita.
- **Panel Analítico**: mini-gráficos, progreso hacia metas y alertas prioritarias.
- Diseño con borde de acento de color (derecha)
- Iconos en contenedores con fondo tintado
- Métricas grandes y legibles (3xl)
- Comparativas con indicadores visuales (flechas + porcentajes)
- Barras de progreso para métricas como ocupación
  Maestro de Día (Calendar)

Grid de 4 columnas (tiempo + 3 estilistas)
Timeline vertical con slots de 1 hora (80px cada uno)
Citas como bloques posicionados absolutamente
Código de colores por tipo de servicio
Hover effects para interactividad

Panel Lateral de Analítica

Mini gráfico de barras semanal
Indicador de progreso hacia meta
Panel de alertas con sistema de prioridad visual

**UX:**

- Header sticky
- Transiciones suaves en elementos interactivos
- Scrollbars discretos
- Sistema de badges para notificaciones y estados

### 2) Sistema de Reservas (Cliente)

Flujo de Usuario
**Flujo:** stepper (Servicios → Profesional → Fecha) con conexión visual y estados claros.
**Tarjetas de profesionales:** foto circular, nombre, especialidad, rating y estado de selección visual.

**Sidebar resumen (sticky):** desglose de servicios, total destacado e imágenes 2x2.

**Mobile:** footer sticky con acción principal, grid colapsable a 1 columna y botones táctiles (≥44px).

### 3) CRM (Ficha de Cliente)

**Layout:** grid 12 columnas (3|6|3) para perfil, historial y fidelización.

**Perfil:** diseño premium con gradiente, estado online, badges y CTA de próxima cita.

**Notas técnicas:** timeline, metadata (fecha + autor) y alertas coloreadas (ej. alergias).

**Historial:** tabla con headers sticky, filas con hover y acción "Ver detalles".

**Fidelización:** tarjeta con barra de progreso, niveles y estilo premium.

**Galería Antes/Después:** grid 2x2, labels overlay y agrupación por fecha.

## ♻️ Patrones & estados

**Sistema de estados:**

- Verde: completado / éxito
- Rojo: alertas críticas
- Ámbar: advertencias / pendientes
- Azul (primary): acciones principales / estados activos

**Jerarquía visual:**

- Primario: títulos grandes (2xl–3xl)
- Secundario: subtítulos semibold
- Terciario: metadata (xs, slate-400/500)

**Espaciado:** sistema basado en múltiplos de 4px — padding cards: 20–24px; gaps: 12–24px; márgenes: 24px.

**Bordes y radios:** border-radius lg/xl, borders sutiles en modo claro y acentos laterales de 4px.

### 🌙 Modo oscuro

- **Background:** `#101622`; surfaces: `#1e293b` / `#151b2b`.
- **Textos:** primario `Slate-100`, secundario `Slate-300/400`.
- **Ajustes:** mayor contraste en bordes, opacidades y scrollbars adaptados.

## 🔘 Elementos interactivos

**Botones:**

- Primarios: background azul, shadow y hover sutil.
- Secundarios: outline con hover de fondo sutil.
- Terciarios: solo texto con underline en hover.

**Transiciones:** usar `transition-colors`, `transition-all`, `transition-transform` (~200–300ms).

```

Con duraciones implícitas (~200-300ms)

## Accesibilidad

### Consideraciones Implementadas

- **Contraste**: Ratios adecuados entre texto y fondo
- **Tamaños táctiles**: Mínimo 44x44px en elementos interactivos
- **Semantic HTML**: Headers, nav, main, aside correctamente anidados
- **ARIA labels**: En botones de iconos y navegación
- **Focus states**: Rings de enfoque visibles

### Áreas de Mejora

- Falta `aria-live` para notificaciones dinámicas
- Navegación por teclado podría mejorarse
- Alternativas para gráficos visuales

## Responsive Design

### Breakpoints

Uso de Tailwind con:
- `sm`: 640px
- `md`: 768px
- `lg`: 1024px

### Estrategias

**Mobile First:**
- Grid colapsable (1 col → 2 cols → 3 cols)
- Sidebar oculto en móvil
- Footer sticky para acciones rápidas
- Padding reducido en pantallas pequeñas

## Componentes Reutilizables

### Cards

Patrón base consistente:
```

- Background blanco/dark
- Border sutil
- Padding 20-24px
- Border radius xl
- Shadow sm
  Badges/Tags
  Múltiples variantes:

Estado (verde, rojo, ámbar)
Categoría (primary, purple)
Info (slate)

Avatares

Circulares con border decorativo
Tamaños consistentes (w-8 a w-24)
Indicadores de estado cuando aplica

Performance y Optimización
Estrategias Implementadas

CDN para recursos: Tailwind, fuentes, iconos
Imágenes optimizadas: Uso de Google Cloud optimizado
CSS mínimo: Solo estilos custom necesarios (scrollbars)
Sticky elements: Con z-index controlado

**Oportunidades:**

- Lazy loading de imágenes
- Code splitting de JS
- Compresión de assets
- Service Worker para PWA/offline

## ✅ Buenas prácticas aplicadas

- Consistencia visual en las tres interfaces
- Jerarquía clara de información
- Feedback visual en interacciones
- Espaciado armonioso y predecible
- Color con propósito
- Tipografía escalable y legible
- Iconografía consistente
- Estados visuales bien definidos

## 🧾 Conclusión

El sistema de diseño muestra madurez profesional: cohesión entre interfaces, atención al detalle y buen uso de Tailwind.

**Puntos fuertes:**

- Diseño visual pulido y profesional
- UX bien pensada para diferentes usuarios
- Sistema de componentes coherente
- Responsividad bien ejecutada

**Áreas de oportunidad:**

- Documentación de componentes
- Tests de accesibilidad más rigurosos
- Optimización de rendimiento en producción
- Animaciones más sofisticadas para transiciones de estado

---

## ✅ Checklist de entrega

- [x] Documentar componentes y tokens (colores, tipografías, spacings)
- [x] Añadir ejemplos visuales (swatches, cards, inputs)
- [ ] Auditoría de accesibilidad (axe/manual)
- [ ] Plan de performance para producción

---
