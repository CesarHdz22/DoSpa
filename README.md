# DoSpa - Sistema de Gestión Instituto DO

**DoSpa** es una aplicación web tipo Single Page Application (SPA) diseñada para centralizar la administración del **Instituto DO**. Permite gestionar desde el registro de alumnas hasta el control de inventarios y flujos de ventas.

## Stack Tecnológico
* **Backend:** PHP para procesamiento de datos y lógica de servidor.
* **Frontend:** Interfaz dinámica construida con HTML5, CSS3 y JavaScript.
* **Base de Datos:** MySQL (Esquema incluido en la carpeta `/bd`).
* **Datos Externos:** Capacidad de manejo de archivos .csv y .xlsx para importación masiva.

## Estructura del Proyecto
Basado en el listado de archivos, el proyecto se organiza así:

* **Controladores Principales:** Archivos PHP en la raíz para ventas, inventario y agenda.
* **Base de Datos (`/bd`):** Script SQL `hykuueix_DO_Spa_Test.sql` para montaje rápido.
* **Estilos (`/css`):** Diseño modular (login, agenda, tablas, etc.).
* **Lógica JS (`/librerias`):** Scripts para el carrito, manejo de tablas y usuarios.
* **Recursos (`/img`):** Iconografía y logos oficiales.

## Funcionalidades Principales
* **Administración Académica:** Perfiles de alumnas/maestras y control de inscripciones a talleres.
* **Agenda:** Gestión de citas para interesadas y seguimiento de eventos.
* **Punto de Venta e Inventario:** Manejo de stock, creación de kits de productos y registro de pagos.
* **Seguridad:** Autenticación de usuarios y controladores de acceso.

## Instalación Rápida
1. **Clonar:** Descarga el repositorio en tu servidor local.
2. **DB:** Importa el archivo SQL de la carpeta `/bd`.
3. **Conexión:** Configura tus credenciales en `conexion.php`.
4. **Inicio:** Ejecuta `login.php` en tu navegador.

---
*Desarrollado para la optimización administrativa de Instituto DO.*
