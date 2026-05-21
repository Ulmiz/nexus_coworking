# 🎬 GUÍA RÁPIDA PARA VIDEO DEMO

## 📝 Script del Video (3-5 minutos)

### [0:00-0:30] Introducción
```
"Hola, este es Nexus Coworking, un sistema profesional de gestión de 
espacios de coworking, reservas de salas y automatización de procesos.

El proyecto implementa patrones de arquitectura empresarial como:
- Service Layer para lógica de negocio
- Policies para autorización
- FormRequests para validación
- Enums para tipos seguros
"
```

### [0:30-1:30] Arquitectura del Sistema

Mostrar en VS Code la estructura:
```
app/
├── Services/       ← Lógica de negocio centralizada
├── Policies/       ← Control de autorización
├── Enums/         ← Roles tipados
├── Http/
│   ├── Controllers/ ← Limpios y legibles
│   └── Requests/    ← Validación separada
└── Models/         ← Con métodos helper
```

**Qué decir:**
- "La lógica de negocio está en Services, no en Controllers"
- "Las Policies controlan quién puede hacer qué"
- "FormRequests hacen validación automática"

### [1:30-2:30] DEMOSTRACIÓN EN VIVO

#### Paso 1: Mostrar Base de Datos (1 min)
```bash
# Terminal: Mostrar las tablas
php artisan tinker
>>> DB::table('rooms')->get()
>>> DB::table('users')->get()
```

**Mostrar:**
- 3 salas (Sala Ejecutiva A, Estudio Creativo, Cabina Individual)
- 3 usuarios (admin, staff, client)

#### Paso 2: Login y Flujo de Reserva (1 min)
```
1. Navegar a http://localhost:8000/login
2. Ingresar: client@nexus.com / password
3. Ir a /reservations/create
4. Crear una reserva:
   - Sala: "Sala Ejecutiva A"
   - Inicio: 2025-05-21 14:00
   - Fin: 2025-05-21 16:00
5. Presionar "Crear Reserva"
```

**Qué explicar:**
- "El sistema valida automáticamente con FormRequest"
- "Calcula el precio (horas × precio/hora)"
- "Verifica que no haya sobreposición"

#### Paso 3: PDF Generado (30 seg)
```
En /reservations:
- Click en el PDF de la reserva creada
- Mostrar el PDF descargado con:
  - Logo de Nexus
  - Detalles de la sala
  - Horario
  - Precio total
```

**Qué explicar:**
- "Se genera un PDF profesional con barryvdh/laravel-dompdf"
- "Se adjunta automáticamente al email"

#### Paso 4: Email en Mailtrap (1 min)
```
1. Abrir Mailtrap en navegador: https://mailtrap.io
2. Ver el email llegado:
   - De: system@localhost
   - Para: client@nexus.com
   - Asunto: "Confirmación de Reserva"
3. Mostrar el PDF adjunto
```

**Qué explicar:**
- "EmailService maneja el envío"
- "Se ejecuta automáticamente al crear reserva"
- "Incluye el PDF adjunto"

#### Paso 5: Task Scheduling (Cron Job) (1 min)
```bash
# Terminal: Ejecutar comando manual
php artisan reservations:send-reminders

# Mostrar output:
# ✓ Recordatorio enviado a: client@nexus.com
# ✓ Enviados exitosamente: 1
# ✓ Total reservas para mañana: 1
```

**Qué explicar:**
- "Este comando se ejecuta automáticamente cada día a las 08:00"
- "Lo configuramos en routes/console.php"
- "Envía recordatorios a todos los clientes con reservas próximas"

#### Paso 6: Admin Dashboard (30 seg)
```
1. Logout de client
2. Login como: admin@nexus.com / password
3. Ir a /admin
4. Mostrar:
   - Estadísticas (total usuarios, salas, reservas)
   - Calendario de hoy
   - Próximas reservas
```

**Qué explicar:**
- "El Admin ve estadísticas y todas las reservas"
- "Puede gestionar salas y usuarios"
- "AdminMiddleware protege estas rutas"

#### Paso 7: Políticas de Autorización (30 seg)
```bash
# VS Code: Mostrar app/Policies/ReservationPolicy.php

# Explicar:
"Un cliente solo puede ver sus propias reservas.
Si intenta acceder a una reserva de otro cliente,
la Política lo bloquea con error 403."
```

### [2:30-3:00] Resumen de Tecnología

```
✅ Autenticación: Laravel Breeze
✅ Base de datos: SQLite con Migrations
✅ Soft Deletes: Historial completo
✅ Validación: FormRequests
✅ PDF: barryvdh/laravel-dompdf
✅ Email: Mailtrap/SMTP
✅ Task Scheduling: Laravel Scheduler
✅ Arquitectura: Service Layer + Policies
✅ Git: Commits limpios y descriptivos
```

---

## 💻 COMANDOS ÚTILES PARA EL VIDEO

```bash
# Levantarserver de desarrollo
php artisan serve

# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Ejecutar migrations
php artisan migrate --seed

# Ver git log
git log --oneline -10

# Ejecutar comando de recordatorios
php artisan reservations:send-reminders

# Acceder a la base de datos
php artisan tinker
```

---

## 🎥 PUNTOS CLAVE A MENCIONAR

1. **"Separación de Responsabilidades"**
   - Controllers = orquestación
   - Services = lógica
   - Policies = autorización
   - Models = datos + comportamiento

2. **"Validación Profesional"**
   - Validación automática con FormRequest
   - Mensajes de error en español
   - Reglas complejas (overlaps, foreign keys)

3. **"Seguridad"**
   - Policies para autorización
   - Gate::authorize() en métodos
   - Un cliente NO puede ver reservas de otros

4. **"Automatización"**
   - Task Scheduling cada día 08:00
   - Envío de recordatorios automático
   - Logging de todas las operaciones

5. **"Documentación"**
   - Comentarios PHPDoc en cada clase
   - ARCHITECTURE.md explicando decisiones
   - Código legible y mantenible

---

## 📊 DURACIÓN ESTIMADA

- Introducción: 30 seg
- Arquitectura: 1 min
- Demostración: 1.5-2 min
- Resumen: 30 seg
- **TOTAL: 3-4 minutos** ✅

---

## 🚨 POSIBLES PROBLEMAS Y SOLUCIONES

### El email no llega
```bash
# Verificar que Mailtrap esté configurado en .env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxxxx
MAIL_PASSWORD=xxxxx
```

### El PDF no se genera
```bash
# Verificar que dompdf esté instalado
composer show | grep dompdf
```

### El Cron no ejecuta
```bash
# En local, ejecutar manualmente:
php artisan reservations:send-reminders

# En producción, Linux ejecuta automáticamente via crontab
```

### La reserva se sobrepone
```php
// ReservationService->isRoomAvailable() verifica:
- Que la sala esté libre
- Que no haya reservas canceladas
- Que no haya overlaps
```

---

## 📋 CHECKLIST ANTES DE GRABAR

- [ ] Servidor levantado: `php artisan serve`
- [ ] Base de datos limpia: `php artisan migrate:fresh --seed`
- [ ] Mailtrap configurado y verificado
- [ ] VS Code con fuente legible
- [ ] Navegador abierto a http://localhost:8000
- [ ] Terminal visible con logs
- [ ] Git log visible mostrando commits
- [ ] Audio claro y sin ruido de fondo
- [ ] Conexión a internet estable

---

