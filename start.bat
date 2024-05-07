@echo off
if not DEFINED IS_MINIMIZED set IS_MINIMIZED=1 && start "" /min "%~dpnx0" %* && exit
title MO Petitions Server
start /min /b php -dextension=pdo_sqlite -dextension=mbstring -q -S localhost:8000
start http://localhost:8000
exit