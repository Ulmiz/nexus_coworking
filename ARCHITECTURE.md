# 📋 GUÍA DE ARQUITECTURA MEJORADA - Nexus Coworking

## 🏗️ Estructura Refactorizada

Tu aplicación ahora sigue una arquitectura profesional con separación clara de responsabilidades:

```
app/
├── Enums/
│   └── RoleEnum.php              ← Roles del sistema (ADMIN, STAFF, CLIENT)
│
├── Services/
│   ├── ReservationService.php    ← Lógica de cálculos y validación
│   ├── PDFService.php            ← Generación de documentos
│   └── EmailService.php          ← Envío de notificaciones
│
├── Http/
│   ├── Controllers/              ← Orquestación de requests
│   │   ├── ReservationController.php
│   │   ├── RoomController.php
│   │   ├── UserController.php
│   │   └── AdminController.php
│   │
│   ├── Requests/                 ← Validación centralizada
│   │   ├── Reservation/
│   │   │   ├── StoreReservationRequest.php
│   │   │   └── UpdateReservationRequest.php
│   │   └── Room/
│   │       ├── StoreRoomRequest.php
│   │       └── UpdateRoomRequest.php
│   │
│   └── Middleware/
│       └── AdminMiddleware.php   ← Protección de rutas
│
├── Models/
│   ├── User.php                  ← Con métodos helper: isAdmin(), canReservate()
│   ├── Room.php                  ← Con métodos: isAvailable(), getTodayOccupancyPercentage()
│   └── Reservation.php           ← Con métodos: canBeCancelled(), isUpcoming()
│
├── Policies/                     ← AUTORIZACIÓN (quién puede qué)
│   ├── ReservationPolicy.php
│   ├── RoomPolicy.php
│   └── UserPolicy.php
│
├── Providers/
│   └── AppServiceProvider.php    ← Registra Policies y Gates
│
└── Console/
    └── Commands/
        └── SendReservationReminders.php ← Tarea automática diaria
```

---

## 🎯 CONCEPTOS CLAVE

### 1️⃣ **Enums** - Constantes Tipadas
```php
// ANTES (propenso a typos):
if ($user->role === 'admin') { }

// AHORA (seguro):
if ($user->role === RoleEnum::ADMIN->value) { }
```

### 2️⃣ **FormRequests** - Validación Centralizada
```php
// ANTES: Validación en el controlador (código desordenado)
$request->validate([...]);

// AHORA: Clase dedicada (reutilizable)
class StoreReservationRequest extends FormRequest {
    public function rules() { }
    public function messages() { }
}
```

### 3️⃣ **Services** - Lógica de Negocio
```php
// Antes: Todo mezclado en el controlador
ReservationController::store() {
    // Validación ← MÁS CLARO
    // Cálculo de precio ← MÁS LIMPIO
    // Generación de PDF ← MÁS TESTEABLE
    // Envío de email
}

// Ahora: Cada cosa en su lugar
class ReservationService {
    public function calculatePrice() { }
    public function isRoomAvailable() { }
    public function getDaySchedule() { }
}
```

### 4️⃣ **Policies** - Autorización
```php
// Verificar autorización ANTES de ejecutar acción
Gate::authorize('update', $reservation); // ← Usa Policy

// En la Policy:
public function update(User $user, Reservation $reservation): bool {
    return $user->isAdmin() || $user->id === $reservation->user_id;
}
```

### 5️⃣ **Model Methods** - Comportamiento
```php
// ANTES:
if ($reservation->end_time->isFuture()) { }

// AHORA (más legible):
if ($reservation->canBeCancelled()) { }
if ($reservation->isUpcoming()) { }
if ($reservation->isInProgress()) { }
```

---

## 📊 FLUJO DE UNA SOLICITUD

### Crear Reserva (PUT /reservations)

```
USER CLICKS "CREAR RESERVA"
    ↓
[1] Browser envía POST /reservations
    ↓
[2] ReservationController@store recibe la solicitud
    ↓
[3] StoreReservationRequest valida automáticamente
    - Si falla validación → vuelve a la vista
    ↓
[4] ReservationController inyecta los Services
    ↓
[5] ReservationService::calculatePrice()
    - Calcula el precio total
    ↓
[6] ReservationService::isRoomAvailable()
    - Verifica que la sala esté libre
    ↓
[7] Reservation::create() guarda en BD
    ↓
[8] PDFService::generateReservationReceipt()
    - Crea el PDF
    ↓
[9] EmailService::sendReservationConfirmation()
    - Envía email a Mailtrap
    ↓
[10] Redirecciona a reservations.show con éxito ✅
```

---

## 🔐 FLUJO DE AUTORIZACIÓN

### Editar Reserva (PATCH /reservations/{id})

```
USER CLICKS "EDITAR"
    ↓
[1] ReservationController@edit
    ↓
[2] Gate::authorize('update', $reservation)
    ↓
[3] ReservationPolicy@update() verifica:
    - ¿Es admin? → SÍ → permitir ✅
    - ¿Es dueño de la reserva? → SÍ → permitir ✅
    - ¿Aún no ha terminado? → SÍ → permitir ✅
    - Si NO → abort(403) ❌
    ↓
[4] Si pasa → muestra formulario de edición
```

---

## 📱 MÉTODOS HELPER EN MODELS

### User.php
```php
$user->isAdmin()           // true/false
$user->isStaff()          // true/false
$user->isClient()         // true/false
$user->canReservate()     // Puede hacer reservas
$user->reservations()     // Relación con reservas
```

### Room.php
```php
$room->isAvailable($start, $end)  // ¿Disponible en ese horario?
$room->getDaySchedule($date)      // Reservas del día
$room->getTodayOccupancyPercentage() // % ocupación
$room->activeReservations()        // Solo no canceladas
$room->confirmedReservations()     // Solo confirmadas
```

### Reservation.php
```php
$reservation->canBeCancelled()     // ¿Se puede cancelar?
$reservation->canBeEdited()        // ¿Se puede editar?
$reservation->getDurationInHours() // Duración en horas
$reservation->isToday()            // ¿Es para hoy?
$reservation->isInProgress()       // ¿Está en progreso ahora?
$reservation->isUpcoming()         // ¿Es próxima (< 24h)?
$reservation->getStatusLabel()     // Etiqueta legible
```

---

## 🤖 TASK SCHEDULING (Cron Job)

### Configuración
📁 `routes/console.php`:
```php
Schedule::command('reservations:send-reminders')->dailyAt('08:00');
```

### Ejecución Manual
```bash
# Ejecuta el comando una vez (útil para testing)
php artisan reservations:send-reminders

# En producción, Laravel ejecuta automáticamente cada día a las 08:00
```

### Qué Hace
1. Busca reservas para MAÑANA
2. Filtra solo las "confirmadas"
3. Para cada reserva:
   - Genera PDF del comprobante
   - Envía email a cliente
   - Registra en logs (app/storage/logs/)

---

## ✅ MEJORAS IMPLEMENTADAS

| Antes | Después |
|-------|---------|
| Validaciones en Controller | FormRequest Classes ✅ |
| Lógica mezclada | Services separados ✅ |
| Sin autorización | Policies + Gates ✅ |
| Roles como strings | RoleEnum tipado ✅ |
| Models vacíos | Métodos helper ✅ |
| Sin CRUD completo | show(), edit(), update() ✅ |
| Sin logging | Logs en Services ✅ |
| Código duplicado | Reutilizable ✅ |

---

## 🚀 PRÓXIMOS PASOS

1. **Crear vistas para edit/show** (si no existen)
2. **Documentación en Blade** con ejemplos
3. **Tests unitarios** para Services
4. **API REST** si es necesario

---

## 📚 REFERENCIAS LARAVEL

- **Policies**: https://laravel.com/docs/authorization
- **FormRequests**: https://laravel.com/docs/validation#form-request-validation
- **Services**: Patrón de diseño (no es built-in)
- **Gates**: https://laravel.com/docs/authorization#gates

