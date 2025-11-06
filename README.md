# IS1_2025_Los5Fantasticos_PanaderiaBarrios

## Descripción del Proyecto
El presente proyecto trata sobre la Creación de un Sistema de Gestión para la Panadería Barrios S.A.C., que incluye el diseño e implementación de una base de datos y el desarrollo de una aplicación web bajo el patrón MVC (Modelo-Vista-Controlador).  

El sistema busca optimizar los procesos internos de la panadería, como la gestión de productos y ventas, integrando una interfaz moderna y un entorno colaborativo de desarrollo.

---
- Lenguaje: PHP/ CSS/ HTML
---

## Equipo de Desarrollo
Los 5 Fantásticos
- Integrante 1: Kevin Ernesto Calle Gonzales 2020-119036
- Integrante 2: Guillermo Junior Mogrovejo Hernandez 2019-119049  
- Integrante 3: Alberto Barrios Rivera 2022-119028
- Integrante 4: Noemi Esther Chura Mamani 2023-119011
- Integrante 5: Katherin Neysha Quispe Turpo 2023-119057

## Estrategia de Ramas
El proyecto utiliza una estrategia básica de control de versiones:
- main: Contiene las versiones estables y liberadas.  
- develop: Rama de integración donde se agregan las nuevas funcionalidades antes de pasar a producción.

## Instalación y Configuración Inicial
1.  Primero descarguen git y abran git bash que estará en el escritorio, una vez instalado git, ponen en gitbash:
   git config --global user.name "Nombre Completo"
luego ponen el comando git config --global user.email "correo@unjbg.edu.pe" pongan su nombre completo y su correo a lo que están asociados a Github
Finalmente git config --list donde podrán ver si su nombre y correo se guardaron correctamente.
2. Clonar el repositorio: Pongan el siguiente comando y verán que abra creado una carpeta con ese nombre en su computadora(Busquenlo, no recuerdo donde se guardaba por default)
   git clone https://github.com/gmogrovejoh/IS1_2025_Los5Fantasticos_PanaderiaBarrios.git
   Aquí creo que era poner git bash en la carpeta una vez localizada.
3. Ponen el siguiente comando: cd IS1_2025_Los5Fantasticos_PanaderiaBarrios
4. Descarguen composer aquí https://getcomposer.org/ (Por default dejen que se descargue en xaamp si ven que está ahí no lo muevan, si no tienen
xaamp descarguenlo antes de descargar composer).
5. Luego de descargarlo cierren gitbash y vuelvanlo a abrir y pongan en gitbash: composer install, si les salen mensaje para poner yes o no
vean el doc de la práctica09 que realicé, en una captura sale lo que marqué(es necesario solo si les sale).
6.Crean el archivo .env en la raíz de su carpeta de proyecto local. Este archivo contiene el usuario, contraseña, y nombre de la BD local de su propia máquina. Como está en el .gitignore, nunca se subirá al repositorio. (no recuerdo si esto se hacia por default o lo tenian que hacer ustedes)
7.Antes de que puedan ejecutar el código, la base de datos debe existir en su servidor local (MySQL).
Deben cargar el esquema de la base de datos que está en la carpeta db/ a su propio servidor de base de datos local.
8.Finalmente, deben verificar que la aplicación funciona correctamente.
Para eso ejecuten elPHP MVC en su servidor local con XAAMP.
9. Finalmente si realizaron un cambio en el repositorio local, como modificar un archivo abran gitbash en la carpeta clonada, obviamente ahí tiene que haber realizado los cambios
y al abrir pongan los comandos:
git checkout develop (como se nos indicó en la práctica debemos de realizar los cambios aquí por ahora).
git add .
git commit -m "" Para ponerle nombre al cambio que realizaron especificando brevemente lo que hicieron
git push -u origin develop (para realizar el cambio en develop)
Finalmente entren al repositorio Github para ver si los cambios que realizaron estén en el repositorio
