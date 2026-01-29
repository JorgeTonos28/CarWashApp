# CarWash App

Sistema de gestión para car wash construido con Laravel 10. Permite administrar servicios, tickets de lavado, cobros, inventario y pagos a lavadores desde un solo panel.

## ¿Qué hace el sistema?

El sistema está pensado para operaciones diarias de un car wash:

- **Tickets y facturación**: creación de tickets, cobros, cancelaciones, pendientes y vista de impresión con QR. 
- **Servicios y vehículos**: catálogo de servicios con precios por tipo de vehículo, gestión de tipos de vehículos y vehículos registrados.
- **Caja chica**: registro de gastos, fondos diarios y resumen de movimientos.
- **Pagos a lavadores**: comisión, propinas, movimientos y pagos masivos o individuales.
- **Inventario**: entradas y salidas de productos y bebidas con control de stock.
- **Descuentos**: activación/desactivación de descuentos.
- **Cuentas bancarias**: registro de cuentas para pagos por transferencia.
- **Dashboard**: indicadores diarios por rango de fechas (ingresos, egresos, caja, comisiones, pendientes).
- **Apariencia**: nombre del negocio, logos, favicon y QR para facturas.
- **Ajustes generales**: stock mínimo por defecto y habilitación de acceso móvil.
- **Usuarios y roles**: administración de usuarios con roles `admin` y `cajero`.

## Requisitos

- PHP **8.1+**
- Composer
- Node.js + npm
- Base de datos (MySQL/MariaDB)

## Instalación

1. Clonar el repositorio y entrar al proyecto.
2. Instalar dependencias backend:
   ```bash
   composer install
   ```
3. Copiar el archivo de entorno y configurar credenciales de base de datos:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Ejecutar migraciones y seeders:
   ```bash
   php artisan migrate --seed
   ```
5. Instalar dependencias frontend y compilar assets:
   ```bash
   npm install
   npm run build
   ```
6. Crear el enlace a storage para mostrar logos y favicon:
   ```bash
   php artisan storage:link
   ```
7. Levantar el servidor:
   ```bash
   php artisan serve
   ```

## Datos de acceso por defecto

Los seeders crean usuarios iniciales:

| Rol | Usuario | Contraseña |
| --- | --- | --- |
| Admin | `admin1@example.com` | `password` |
| Admin | `admin2@example.com` | `password` |
| Cajero | `cajero@example.com` | `password` |

## Notas importantes

- Para que **logo** y **favicon** se visualicen correctamente, es obligatorio ejecutar:
  ```bash
  php artisan storage:link
  ```
- El nombre del negocio se configura en **Apariencia** y se utiliza en el título del navegador y en la impresión de tickets.

## Scripts útiles

- Ejecutar tests:
  ```bash
  php artisan test
  ```
- Recompilar assets en modo desarrollo:
  ```bash
  npm run dev
  ```
