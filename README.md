# Nexus Coworking System

Sistema avanzado para la gestión de espacios de coworking, reservas de salas y automatización de procesos administrativos.

## Requisitos Previos
- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite (Configurado por defecto)

## Instalación y Configuración
1. Clona este repositorio.
2. Copia el archivo `.env.example` a `.env` y configura las credenciales de Mailtrap.
3. Ejecuta `composer install`.
4. Ejecuta `php artisan key:generate`.
5. Ejecuta `php artisan migrate --seed`.
6. Ejecuta `npm install` y `npm run dev`.

## Credenciales de Prueba (Seeding)
- **Admin:** admin@nexus.com / password
- **Staff:** staff@nexus.com / password
- **Client:** client@nexus.com / password

## Diagrama Entidad-Relación (DER)
```mermaid
erDiagram
    USER ||--o{ RESERVATION : "makes"
    ROOM ||--o{ RESERVATION : "has"

    USER {
        bigint id PK
        string name
        string email
        string password
        string role "admin, staff, client"
        datetime deleted_at "Soft Deletes"
    }

    ROOM {
        bigint id PK
        string name
        text description
        integer capacity
        decimal price_per_hour
        datetime deleted_at "Soft Deletes"
    }

    RESERVATION {
        bigint id PK
        bigint user_id FK
        bigint room_id FK
        datetime start_time
        datetime end_time
        decimal total_price
        string status "pending, confirmed, cancelled"
        datetime deleted_at "Soft Deletes"
    }
```

## Despliegue en InfinityFree

### Datos de conexión (guardar)
| Recurso | Valor |
|---------|-------|
| Dominio | https://j5csvuzl.infinityfree.com |
| FTP host | ftpupload.net |
| FTP user | if0_41979766 |
| MySQL host | sql208.infinityfree.com |
| MySQL user | if0_41979766 |
| Volumen | vol10_1 |

### Pasos

1. **Abrí FileZilla Client** y conectate:
   - Host: `ftpupload.net`
   - Usuario: `if0_41979766`
   - Contraseña: la que pusiste al crear la cuenta
   - Puerto: `21`

2. **Subí los archivos**:
   - En FileZilla, del lado izquierdo (tu PC) navegá a la carpeta del proyecto
   - Del lado derecho (servidor) andá a `htdocs/`
   - Seleccioná **todo** (Ctrl+A) y arrastrá a la derecha
   - Esperá que termine (puede tardar varios minutos)

3. **Crear base de datos MySQL**:
   - En el panel de InfinityFree → **MySQL Databases**
   - Creá una base de datos con nombre: `if0_41979766_nexus`
   - Anotá la contraseña que pongas

4. **Configurar `.env`**:
   - En el panel → **File Manager** → navegá a `htdocs/`
   - Renombrá `.env.infinityfree` a `.env`
   - Editá `.env` y cambiá `CAMBIA_ESTA_CONTRASENA` por la contraseña real de MySQL

5. **Ajustar Document Root**:
   - En el panel → **Settings** → **Root Domain** → cambialo a `/public`
   - Si no te deja, ya está el `.htaccess` en la raíz que redirige automáticamente

6. **Ejecutar migraciones**:
   - En el panel → **PHP Command Line**
   - Escribí: `php artisan migrate --seed`
   - Si no funciona, importá manualmente via **phpMyAdmin**: abrí la BD, click **Import**, seleccioná el archivo SQL

7. **Activar cron** (para los recordatorios automáticos):
   - En el panel → **Cron Jobs**
   - Agregá: `php /home/vol10_1/htdocs/artisan reservations:send-reminders`
   - Frecuencia: una vez al día (Daily)

8. **Probar**: Andá a https://j5csvuzl.infinityfree.com y probá con:
   - Admin: admin@nexus.com / password
   - Staff: staff@nexus.com / password
   - Client: client@nexus.com / password

## Tareas Programadas (Cron)

El sistema incluye un comando programado que envía recordatorios de reserva cada día a las 8:00 AM.

Para activarlo, agrega esta línea al crontab del servidor:

```
* * * * * cd /ruta/del/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

Puedes probar el comando manualmente con:

```
php artisan reservations:send-reminders
```

## Estrategia de Commits (Historial Requerido)
Para cumplir con los requisitos del proyecto (mínimo 10 commits lógicos), se ha estructurado el desarrollo de la siguiente manera:

1. `feat: init laravel project with breeze, database sqlite and core models (Rooms, Reservations)`
2. `feat: implement Rooms CRUD and soft deletes logic`
3. `feat: implement Reservations logic with overlap validation`
4. `feat: add AdminMiddleware and role-based access control`
5. `ui: integrate professional design for dashboard and views`
6. `feat: install and configure barryvdh/laravel-dompdf`
7. `feat: generate PDF receipt for confirmed reservations`
8. `feat: configure Mailtrap and send PDF receipt via Email on creation`
9. `feat: implement task scheduling command for daily reminders`
10. `docs: update README, add ERD and final project polish`

---
*Desarrollado para el proyecto final de Arquitectura y Automatización.*
