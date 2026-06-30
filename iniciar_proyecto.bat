@echo off
color 0A
echo ========================================================
echo       Iniciando Tienda de Repuestos - Servidor Web
echo ========================================================
echo.
echo  El servidor esta corriendo correctamente con el enrutador.
echo  Por favor, abre tu navegador y entra a la siguiente direccion:
echo.
echo       http://localhost:8000
echo.
echo  (Para detener el servidor, simplemente cierra esta ventana)
echo ========================================================
echo.
php -S localhost:8000 -t public public/router.php
pause
