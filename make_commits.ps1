git add resources/views/rooms/
git commit -m "ui: integrate professional design for Rooms dashboard and create views"

git add resources/views/layouts/navigation.blade.php
git commit -m "ui: update navigation layout for new modules"

git add app/Http/Controllers/ReservationController.php resources/views/reservations/
git commit -m "feat: implement Reservations logic with overlap validation and UI"

git add composer.json composer.lock
git commit -m "feat: install and configure barryvdh/laravel-dompdf"

git add resources/views/pdf/
git commit -m "feat: generate PDF receipt for confirmed reservations"

git add app/Mail/ resources/views/emails/
git commit -m "feat: configure Mailtrap and send PDF receipt via Email on creation"

git add app/Console/ routes/console.php
git commit -m "feat: implement task scheduling command for daily reminders"

git add database/seeders/DatabaseSeeder.php
git commit -m "feat: update DatabaseSeeder with dummy data for final review"

git add .
git commit -m "docs: final project polish and README structure"
