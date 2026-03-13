# Inconel Building - Guía de Instalación

## Requisitos del Sistema

- PHP 8.1
- MySQL 5.7+ o 8.0+
- Composer 2.x
- Extensiones PHP: `mysqli`, `mbstring`, `intl`, `json`, `xml`, `zip`, `gd`

---

## Instalación Local (Desarrollo)

### 1. Clonar o descomprimir el proyecto

```bash
# Si usas git:
git clone <repo-url> inconel
cd inconel

# O descomprime el ZIP en la carpeta `inconel/`
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar el entorno

```bash
cp .env.example .env
```

Edita `.env`:
```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost/inconel/public/'

database.default.hostname = localhost
database.default.database = inconel_db
database.default.username = tu_usuario
database.default.password = tu_contraseña
```

### 4. Crear la base de datos

```bash
mysql -u root -p < database/inconel_db.sql
```

O ejecutar migraciones (alternativa):
```bash
php spark migrate
php spark db:seed DatabaseSeeder
```

### 5. Permisos de escritura

```bash
chmod -R 777 writable/
```

### 6. Acceder a la aplicación

URL: `http://localhost/inconel/public/`

Credenciales por defecto:
- **Admin:** admin@inconel.com / Admin@123
- **Técnico:** tecnico@inconel.com / Tecnico@123

---

## Instalación en Hostinger

### Estructura en Hostinger

El hosting Hostinger tiene la siguiente estructura:
```
public_html/
    inconel/          ← Sube el proyecto aquí
        app/
        system/        ← Después de composer install
        writable/
        public/        ← Document root configurado con .htaccess
        vendor/        ← Después de composer install
        ...
```

### Paso 1: Subir archivos

1. Comprime el proyecto (sin `/vendor/`) en un `.zip`
2. En el panel de Hostinger, ve a **File Manager → public_html**
3. Crea la carpeta `inconel`
4. Sube y descomprime el `.zip` dentro de `public_html/inconel/`

### Paso 2: Ejecutar Composer en Hostinger

**Opción A (Terminal SSH):**
```bash
cd ~/public_html/inconel
composer install --no-dev --optimize-autoloader
```

**Opción B (Sin SSH):**
Ejecuta `composer install` localmente y sube también la carpeta `/vendor/`.

### Paso 3: Configurar la base de datos

1. En el panel de Hostinger → **Bases de datos MySQL** → Crear base de datos
2. Crea un usuario y asígnalo a la base de datos
3. Importa `database/inconel_db.sql` desde **phpMyAdmin**

### Paso 4: Configurar `.env`

Edita el archivo `.env` en el servidor:
```ini
CI_ENVIRONMENT = production

app.baseURL = 'https://tudominio.com/inconel/public/'

database.default.hostname = localhost
database.default.database = u123456789_inconel
database.default.username = u123456789_user
database.default.password = TuPasswordSegura
```

### Paso 5: Configurar PHP 8.1

El archivo `public/.htaccess` ya incluye:
```apache
AddHandler application/x-httpd-php81 .php
```

Esto asegura que **solo esta aplicación use PHP 8.1** sin afectar otras apps en el hosting.

Si tu Hostinger usa una directiva diferente, revisa en el panel:
**Hosting → Configuración Avanzada → Versión de PHP**

### Paso 6: Configurar el Document Root (si aplica)

Si el sitio debe ser `tudominio.com/inconel`, el `.htaccess` raíz redirige a `/public/`.

Si la app es el sitio principal (`tudominio.com`), configura en Hostinger:
- **Document Root:** `public_html/inconel/public`

### Paso 7: Permisos

```bash
chmod -R 755 ~/public_html/inconel/
chmod -R 777 ~/public_html/inconel/writable/
```

### Paso 8: Verificar instalación

Accede a: `https://tudominio.com/inconel/public/`

---

## Configuración Adicional

### Cambiar la URL base

Edita `app/Config/App.php`:
```php
public string $baseURL = 'https://tudominio.com/inconel/public/';
```

O en `.env`:
```ini
app.baseURL = 'https://tudominio.com/inconel/public/'
```

### Cambiar zona horaria

En `.env`:
```ini
app.appTimezone = America/New_York
```

Zonas horarias comunes:
- `America/New_York` (EST/EDT)
- `America/Chicago` (CST/CDT)
- `America/Los_Angeles` (PST/PDT)
- `America/Bogota` (Colombia)
- `America/Mexico_City` (México)

---

## Credenciales por Defecto

> **⚠️ IMPORTANTE:** Cambia estas contraseñas inmediatamente después de instalar.

| Usuario | Email | Contraseña | Rol |
|---------|-------|-----------|-----|
| Administrador | admin@inconel.com | Admin@123 | Administrador |
| Técnico Demo | tecnico@inconel.com | Tecnico@123 | Técnico |

---

## Estructura del Proyecto

```
inconel/
├── app/
│   ├── Config/          # Configuraciones CI4
│   ├── Controllers/     # Controladores
│   │   ├── Auth.php
│   │   ├── Dashboard.php
│   │   ├── Users.php
│   │   ├── Viviendas.php
│   │   └── Reportes.php
│   ├── Database/
│   │   ├── Migrations/  # Migraciones
│   │   └── Seeds/       # Seeders
│   ├── Filters/
│   │   └── AuthFilter.php
│   ├── Models/
│   │   ├── UsuarioModel.php
│   │   └── ViviendaModel.php
│   └── Views/
│       ├── auth/        # Login
│       ├── dashboard/   # Dashboard
│       ├── layouts/     # Template principal
│       ├── users/       # CRUD usuarios
│       ├── viviendas/   # CRUD viviendas
│       └── reportes/    # Todos los reportes
├── database/
│   └── inconel_db.sql   # Schema SQL
├── public/
│   ├── .htaccess        # Apache rules + PHP 8.1
│   ├── index.php        # Front controller
│   └── assets/
│       ├── css/custom.css
│       ├── js/app.js
│       └── img/
├── writable/            # Logs, cache, sessions
├── .env                 # Configuración (NO commitear)
├── composer.json
└── INSTALACION.md
```

---

## Roles y Permisos

| Funcionalidad | Administrador | Técnico |
|--------------|:---:|:---:|
| Ver Dashboard | ✅ | ✅ |
| CRUD Viviendas | ✅ | ✅ |
| CRUD Usuarios | ✅ | ❌ |
| Ver Reportes | ✅ | ✅ |
| Exportar Excel/PDF | ✅ | ✅ |

---

## Soporte

Para problemas con la instalación, verifica:
1. PHP 8.1 activo (`php -v`)
2. Extensiones: `php -m | grep -E "mysqli|mbstring|intl"`
3. Logs: `writable/logs/`
4. Debug: Cambia `CI_ENVIRONMENT = development` en `.env`
