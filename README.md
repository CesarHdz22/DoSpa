# DoSpa - Sistema de Gestión Instituto DO

**DoSpa** es una Single Page Application (SPA) diseñada para la administración integral del **Instituto DO**. Este sistema centraliza el control de alumnas, procesos de inscripción, gestión de inventarios y seguimiento de talleres.

## Stack Tecnológico
* [cite_start]**Backend:** PHP (Scripts modulares para procesamiento de datos)[cite: 2, 3].
* [cite_start]**Frontend:** HTML5, CSS3 y JavaScript[cite: 4, 5, 6].
* [cite_start]**Base de Datos:** MySQL (Dump incluido)[cite: 4].
* [cite_start]**Formatos de Datos:** Soporte para .csv y .xlsx[cite: 3, 4].

## Estructura del Proyecto
[cite_start]El proyecto está organizado de la siguiente manera[cite: 1]:

* [cite_start]**Raíz (`/`):** Controladores y vistas principales como `main.php`, `inventario.php` y `ventas.php`[cite: 2, 3].
* [cite_start]**`bd/`:** Contiene el esquema `hykuueix_DO_Spa_Test.sql`[cite: 4].
* [cite_start]**`css/`:** Estilos específicos (ej. `agenda.css`, `login.css`, `perfil_alumna.css`)[cite: 4, 5].
* [cite_start]**`img/`:** Recursos visuales como `DO_SPA_logo.png`[cite: 5, 6].
* [cite_start]**`librerias/`:** Lógica de negocio en JS (`agenda.js`, `carrito.js`, `FunInventario.js`)[cite: 6].

## Funcionalidades Clave
* [cite_start]**Gestión Académica:** Registro de inscripciones y perfiles de alumnas[cite: 2, 3].
* [cite_start]**Sistema de Agenda:** Programación de citas para inscripciones e interesadas[cite: 2].
* [cite_start]**Control de Inventario:** Gestión de stock, edición de productos y kits[cite: 2, 3].
* [cite_start]**Módulo de Ventas:** Flujo de compra, registro de pagos y detalle de ventas[cite: 2, 3].
* [cite_start]**Seguridad:** Control de acceso mediante `login.php` y controlador de usuarios[cite: 3].

## Instalación
1. **Descargar:** Clona el repositorio en tu servidor local (XAMPP/Apache).
2. [cite_start]**Base de Datos:** Importa el archivo en `/bd/hykuueix_DO_Spa_Test.sql`[cite: 4].
3. [cite_start]**Configuración:** Ajusta las credenciales en `conexion.php`[cite: 2].
4. [cite_start]**Acceso:** Inicia desde `index.html` o `login.php`[cite: 2, 3].

---
*Desarrollado para el control administrativo de Instituto DO.*
