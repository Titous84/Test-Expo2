#ExpoSAT script de compilation et paquete l'API et le projet React.

function Build-ExpoSAT{
    #Si le dossier build existe, supprime le contenu
    if (Test-Path -Path ./build) {
        Get-ChildItem ./build/* -Recurse | Remove-Item -Recurse -Force
    } else{
        #Création du dossier build
        New-Item -Path ./ -Name "build" -ItemType "directory"
    }

    Set-Location ./front

    #Compilation du projet React
    npm run build

    #Si le fichier index.html n'est pas présent dans le dossier build du projet React -> Erreur de compilation
    if (!(Test-Path -Path ./dist/index.html -PathType leaf)) {
        Write-Host "Une erreur s'est produite durant la compilation du frontend."
        Return
    }
    Set-Location ../
	
	#Copier le .htaccess & le Web.config
	Copy-Item -Path ".htaccess" -Destination "./build/"
    Copy-Item -Path "Web.config" -Destination "./build/"

    #Copier API
    Copy-Item -Path "./backend/api" -Destination "./build/" -Recurse

    #Copier React
    Copy-Item -Path "./front/dist/*" -Destination "./build/" -Recurse
}
Build-ExpoSAT
