# 🛒 Plataforma E-Commerce - 2º DAW

Este es el repositorio oficial de la web de e-commerce especializada en ropa urbana **_(SkyUrban)_**. La aplicación está construida siguiendo una arquitectura desacoplada moderna utilizando **_Laravel 11/12_** como backend robusto y **_Vue 3_** (Inertia.js) en el entorno cliente.

## 🏗️ 1. Arquitectura y Componentes del Sistema

El proyecto sigue el patrón de diseño **_MVC (Modelo-Vista-Controlador)_** adaptado al ecosistema de Inertia.js, eliminando la necesidad de construir una API con rutas tradicionales para el renderizado, manteniendo la reactividad de una SPA (Single Page Application).

- **Backend (Servidor)**: Laravel 12.3.0, PHP 8.2.12. Se encarga de la lógica de negocio, persistencia de datos, seguridad perimetral y autenticación.
- **Frontend (Cliente):** Vue 3 utilizando la Composition API y JavaScript. Gestiona la reactividad, los estados globales y los eventos mediante window.emitter.
- **Capa de Enlace:** Inertia.js actúa como puente de datos, permitiendo enviar datos directos desde los controladores de Laravel hacia las propiedades (Props) de Vue sin necesidad de construir endpoints Axios manuales en las operaciones principales.
- **Base de Datos:** Relacional (MySQL). Estructura gestionada mediante migraciones de Laravel.

## 🛠️ 2. Guía de Instalación y Despliegue Local

Sigue estos pasos detallados para replicar y arrancar el entorno de desarrollo desde cero sin intervención previa:

### Requisitos Previos

    PHP ≥ 8.2

    Composer v2

    Node.js ≥ 18 & NPM

    Servidor MySQL (XAMPP)

### Pasos de Configuración

1. Clonar el repositorio y acceder al directorio:

```bash
git clone https://github.com/Dameris/final\_proyect\_api.git
```

```bash
cd final_proyect_api
```

2. Instalar dependencias del backend (PHP):

```bash
composer install
```

3. Instalar dependencias del frontend (JavaScript):

```bash
npm install
```

4. Configurar las variables de entorno:
   Copia el archivo de ejemplo a tu archivo definitivo:

```bash
cp .env.example .env
```

Abre el archivo .env recién creado y configura las credenciales de tu base de datos local:

```properties
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skyurban_db
DB_USERNAME=root
DB_PASSWORD=
```

5. Generar la clave única de la aplicación:

```bash
php artisan key:generate
```

6. Ejecutar migraciones y poblado de datos (Seeders):

```bash
php artisan migrate:fresh --seed
```

7. Crear el enlace simbólico para el almacenamiento de imágenes:

```bash
php artisan storage:link
```

8. Arrancar los servidores de desarrollo:
   En una terminal ejecuta el servidor de Laravel:

```bash
php artisan serve
```

En otra terminal paralela, ejecuta el compilador en tiempo real de Vite para Vue:

```bash
npm run dev
```

Accede a la plataforma mediante: http://127.0.0.1:8000

## 🧬 3. Flujo de Trabajo en Git y Gitflow Coherente

Para garantizar el orden del proyecto y cumplir con las directrices de desarrollo ágil, se utiliza un sistema estricto de ramas y nomenclatura de mensajes:

### Ramas Principales

- _***main:***_ Contiene el código de producción completamente estable y testeado.
- _***develop:***_ Rama de integración donde se vuelcan las características terminadas.
- _***feature/:***_ Prefijo para el desarrollo de nuevas funcionalidades (Ej: **_feature/dark-mode_**).
- _***bugfix/:***_ Prefijo para la resolución de errores detectados (Ej: **_bugfix/stock-validation_**).

### Nomenclatura de Commits (Semantic Commits)

Los commits deben seguir la estructura estándar corporativa: **_[tipo]: [descripción en presente]_**

- _***feat:***_ Nueva característica
- _***fix:***_ Solución de un error
- _***docs:***_ Cambios en la documentación
- _***style:***_ Estilos CSS, paddings, maquetación

## 🔒 4. Seguridad, Roles y Middleware Real

La seguridad del sistema está estructurada en dos capas infranqueables que no dependen exclusivamente de la interfaz visual del cliente:

1.  **Middleware de Rutas (Kernel/Bootstrap):** Almacenado como alias perimetral en **_bootstrap/app.php._** Bloquea peticiones directas HTTP a endpoints administrativos si la sesión activa no posee el rol requerido (**role:admin**).

2.  **Policies de Laravel (Lógica de Dominio):** El modelo **_Product_** se encuentra blindado mediante **ProductPolicy.php**. Métodos específicos como **_update_** y **_delete_** evalúan mediante métodos de Spatie (**_$user->hasPermissionTo()_**) los privilegios del usuario autenticado, retornando excepciones HTTP **_403 Forbidden_** en ataques de suplantación de identidad.

## 🌐 5. Documentación de la API y Endpoints Públicos

La plataforma interactúa mediante peticiones REST asíncronas para flujos operativos críticos. A continuación se detallan los endpoints disponibles controlados:

| Método HTTP | Endpoint          | Descripción                                                                                  | Requiere Auth | Códigos de Estado                          |
| ----------- | ----------------- | -------------------------------------------------------------------------------------------- | ------------- | ------------------------------------------ |
| GET         | /api/cart         | Obtiene los elementos del carrito del usuario activo indexando relaciones de stock.          | Sí            | 200 OK, 401 Unauthorized                   |
| POST        | /cart/{type}/{id} | Añade un producto específico (tshirt/jogger) especificando la talla seleccionada.            | Sí            | 200 OK, 400 Bad Request, 404 Not Found     |
| PUT         | /cart/{id}        | Actualiza las unidades deseadas verificando límites de stock relacionales de forma reactiva. | Sí            | 200 OK, 400 Bad Request, 422 Unprocessable |
| DELETE      | /cart/{id}        | Remueve una línea de artículo específica dentro de la cesta de la compra.                    | Sí            | 200 OK, 404 Not Found                      |
| POST        | /checkout         | Procesa la orden de compra mandando los datos del carrito e inyectando los datos de envío.   | Sí            | 201 Created, 422 Unprocessable Entity      |
| GET         | /api/tshirt/{id}  | Devuelve los metadatos y stocks en formato JSON de una camiseta en específico.               | No            | 200 OK, 404 Not Found                      |

Ejemplo de Payload (Request) - **_POST /checkout_**

```json
{

    "cart": \[

        {

            "id": 14,

            "size": "M",

            "quantity": 2,

            "product": { "id": 1, "tshirt_name": "Oversized Black" }

        }

    \],

    "shipping_details": {

        "fullName": "Daniel Merino",

        "address": "Calle Barbate",

        "city": "Cádiz",

        "zipCode": "11012",

        "phone": "+34625614329"

    }

}
```

## 🚀 6. Pipeline de Integración Continua (CI/CD)

El proyecto cuenta con un flujo de trabajo de Integración Continua automatizado mediante GitHub Actions (configurado en **_.github/workflows/ci-cd.yml_**).

Cada vez que se realiza una actualización (**_push_** o **_pull_** request) hacia las ramas principales, un servidor en la nube ejecuta de forma automática las siguientes fases de validación técnica:

1. **Entorno de Servidor**: Levanta un contenedor virtual con PHP 8.2 e instala de manera aislada y limpia las dependencias de Composer.

2. **Entorno de Base de Datos**: Inicializa un servicio en segundo plano de MySQL 8.0, configura las variables de entorno de prueba y ejecuta las migraciones (**_php artisan migrate_**) desde cero para certificar la consistencia y la reproducibilidad absoluta del esquema.

3. **Entorno de Cliente**: Instala Node.js, descarga los paquetes de NPM y compila el frontend con Vite (**_npm run build_**) para verificar que no existan fallos de sintaxis en los componentes Vue 3 e Inertia.

Este pipeline actúa como control de calidad previo, garantizando la estabilidad integral del software antes de proceder a la publicación manual final.

## 🌐 7. Proceso de Despliegue en Producción (Hostinger)

El despliegue de la aplicación en el entorno de producción se realiza de forma manual siguiendo una estrategia de empaquetado estructurado para garantizar la integridad de los archivos en el servidor de **_Hostinger_**.

### Pasos detallados del Despliegue:

1. **Empaquetado del Proyecto:**

Se genera un archivo comprimido **_.zip_** que contiene la estructura limpia del proyecto, excluyendo directorios locales pesados como **_node_modules_** (los assets de Vue ya van compilados para producción dentro de **_public/build_** gracias a **_npm run build_**).

2. **Carga y Extracción en Servidor:**

El archivo **_.zip_** se sube a través del Administrador de Archivos de Hostinger (o vía cliente SFTP) y se extrae directamente en la raíz del directorio **_public_html_**.

3. **Configuración de Seguridad y Redirección (.htaccess):**

Dado que Laravel utiliza la carpeta **_/public_** como el único punto de entrada seguro expuesto a Internet, se añade un archivo **_.htaccess_** en la raíz de **_public_html_** que actúa como configuración del servidor web Apache. Este archivo redirige el tráfico de forma limpia sin exponer el resto del código fuente del backend:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.\*)$ public/$1 \[L\]
</IfModule>
```

4. **Persistencia y Base de Datos:**

Se crea una base de datos MySQL relacional desde el panel de control de Hostinger. El esquema de tablas inicial se vuelca importando el archivo **_.sql_** generado localmente o ejecutando de forma remota los comandos de migración.

5. **Configuración del Entorno de Producción \***(.env)**\*:**

El archivo **_.env_** del servidor se edita minuciosamente para activar el modo seguro, asegurar el aislamiento de errores y enlazar los servicios web:

```properties
APP_ENV=production
APP_DEBUG=false
APP_URL=https://skyurban.space

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=u265459828_skyurban_db
DB_USERNAME=u265459828_db_skyurban
DB_PASSWORD=********
```

## ❓ 8. FAQ de Errores Comunes y Soluciones

- **Error:** **_Target class \[role\] does not exist._**
- - **Solución:** Ocurre al no registrar el alias de Spatie. Se resuelve encadenando el método **_$middleware->alias(\['role' => ...\])_** en el archivo interno **_bootstrap/app.php_**.
- **Error:** Los contadores del carrito aumentan el stock fantasma.
- - **Solución:** La función **_getMaxStock_** en el cliente ha sido corregida para consultar de manera pura la respuesta de los almacenes (**_stocks.stock_**) descartando acumulaciones redundantes del estado local.
