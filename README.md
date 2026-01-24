DoSpa - Sistema de Gestión Instituto DO

DoSpa es una aplicación web tipo SPA (Single Page Application) diseñada para la administración integral del Instituto DO. El sistema facilita el control de alumnas, procesos de inscripción, gestión de inventarios y seguimiento de talleres.



🛠️ Stack Tecnológico

Backend: PHP (Arquitectura basada en scripts modulares para procesamiento de datos).


Frontend: HTML5, CSS3 (Diseño modular) y JavaScript.



Base de Datos: MySQL (Incluye dump SQL para pruebas).


Librerías: Scripts personalizados para manejo de agendas, carritos de compra y tablas dinámicas.

📁 Estructura del Proyecto
El proyecto está organizado de la siguiente manera:


Raíz (/): Contiene los controladores de lógica y vistas principales como main.php, inventario.php, y ventas.php.


bd/: Contiene el script SQL hykuueix_DO_Spa_Test.sql para la estructura de la base de datos.


css/: Hojas de estilo específicas para cada módulo (ej. agenda.css, login.css, perfil_alumna.css).


img/: Iconografía y logotipos del sistema (ej. DO_SPA_logo.png).


librerias/: Lógica de negocio en el cliente, incluyendo agenda.js y carrito.js.


Archivos muestra.../: Documentación de apoyo y bases de datos de ejemplo en formatos .csv y .xlsx para alumnas, ingresos y egresos.

🚀 Funcionalidades Clave

Gestión Académica: Registro de inscripciones, perfiles de alumnas/maestras y control de módulos de curso.


Sistema de Agenda: Programación de citas para inscripciones e interesadas.


Control de Inventario: Gestión de stock, edición de productos y configuración de kits.


Módulo de Ventas: Flujo de compra, registro de pagos y visualización de detalles de venta.


Seguridad: Control de acceso mediante login.php y gestión de usuarios.

⚙️ Instalación
Clonar el repositorio: Descarga los archivos en tu servidor local (Apache/PHP).


Configurar Base de Datos: Importa el archivo ubicado en /bd/hykuueix_DO_Spa_Test.sql.


Conexión: Ajusta los parámetros en conexion.php para apuntar a tu servidor local.


Ejecutar: Abre index.html o login.php en tu navegador.
