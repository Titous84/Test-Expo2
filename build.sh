#!/bin/bash
#ExpoSAT script de compilation et paquete l'API et le projet React.

#Si le dossier build existe, supprime le contenu
[ -d "./build" ] && rm -rf ./build
#Création du dossier build
mkdir build

cd ./front
#Compilation du projet React
sudo npm run build

#Si le fichier index.html n'est pas présent dans le dossier build du projet React -> Erreur de compilation
[ ! -f "./dist/index.html" ] && echo "Une erreur s'est produite durant la compilation du frontend." && exit 0

cd ..

#Copier .htaccess & Web.config
cp ./.htaccess ./build/.htaccess

#Copier API
cp -R ./backend/api ./build/api 

#Copier React
cp -R ./front/dist/* ./build/
