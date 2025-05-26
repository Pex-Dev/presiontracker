<div style="text-align: center;">
    <a href="https://presiontracker.brayandev.com/">
        <img src="https://i.imgur.com/lmpeT00.png" width="400px" alt="PresiónTracker">
    </a>
</div>

# Sobre PresiónTracker

PresiónTracker es una aplicación web diseñada para registrar y monitorear la presión arterial de manera sencilla y organizada. Permite ingresar datos diarios, generar reportes y visualizar tendencias con gráficos, facilitando el control de la salud en cualquier momento y desde cualquier dispositivo.

### Características:


- Registro de presión sistólica y diastólica con fecha..
- Campos opcionales para pulso, temperatura y notas adicionales.
- Filtros por rango de fechas para un análisis detallado.
- Cálculo automático del promedio de presión en un período seleccionado.
- Gráficos dinámicos para visualizar tendencias fácilmente.

## Tecnologías utilizadas:

- [Laravel](https://laravel.com/): Framework PHP utilizado para el desarrollo completo del backend y frontend. Laravel facilita la creación de aplicaciones web rápidas y seguras, y en este proyecto se utilizó para manejar la lógica de negocio, las rutas, la base de datos y más.

- [Livewire](https://laravel-livewire.com/): Biblioteca que permite crear interfaces dinámicas y reactivas sin necesidad de escribir JavaScript adicional. Livewire se utilizó para mejorar la experiencia de usuario al interactuar con los datos de manera instantánea sin recargar la página.

- [Dompdf](https://github.com/dompdf/dompdf): Librería PHP utilizada para generar archivos PDF a partir de contenido HTML. Dompdf permite a los usuarios exportar su historial de presión arterial en formato PDF para un fácil seguimiento.

- [Livewire Alert](https://github.com/jantinnerezo/livewire-alert):  Paquete para mostrar alertas en tiempo real dentro de las aplicaciones Livewire. Se usó para gestionar y mostrar mensajes de éxito, error o advertencia de manera sencilla y dinámica, mejorando la interacción con el usuario.

- [Tailwind CSS](https://tailwindcss.com/) Framework de CSS utilizado para diseñar una interfaz limpia, moderna y completamente responsiva. Tailwind CSS facilita la personalización de estilos sin tener que escribir una gran cantidad de CSS personalizado.

- [Chart Js](https://www.chartjs.org/): Librería JavaScript utilizada para crear gráficos interactivos y visualizaciones de datos. En este proyecto, Chart.js se utilizó para mostrar de manera visual las tendencias de la presión arterial, ayudando a los usuarios a identificar rápidamente picos o caídas en su salud.

## Requisitos

- Docker instalado
- En Windows, es recomendable usar WSL 2 para ejecutar Docker y Sail correctamente.
- Composer instalado (solo se necesita para ejecutar composer install una vez al comienzo)

## Instalación

**Clonar el repositorio:**
```bash
git clone https://github.com/Pex-Dev/presiontracker.git
cd presiontracker
```
**Copiar archivo env**
```bash
cp .env.example .env
```
**Editar archivo .env**

    Abre .env y ajusta estas variables según tu entorno local

**Instalar dependencias de php**
```bash
composer install
```
**Levantar los contenedores Docker con Sail**
```bash
./vendor/bin/sail up -d
```
**Ejecutar migraciones y seeders**
```bash
./vendor/bin/sail artisan migrate --seed
```
**Instalar dependencias de Node.js**
```bash
./vendor/bin/sail npm install
```
**Compilar assets (JS, CSS, etc)**
```bash
./vendor/bin/sail npm run dev
```