import React, { useEffect } from 'react';
import { Route, Routes, useLocation } from "react-router-dom"
import NotFoundPage from '../pages/404/NotFoundPage';
import { createRandomKey } from '../utils/utils';
import { pages, RoleName } from './routes';
import ForbiddenPage from '../pages/403/ForbiddenPage';
import { ActualRole } from '../utils/roleUtil';

/**
 * Classe gérant le routage de l'application.\
 * Prend l'URL et affiche la bonne page.
 */
class RouterManager extends React.Component {
    role : RoleName | null = null;

    componentDidMount() {
        this.getRole()
    }

    async getRole() {
        this.role = await ActualRole.get();
        this.forceUpdate()
    }

    /**
     * Génère les routes de l'application à l'aide de la liste de routes du fichier `routes.ts`
     * @returns Les routes principales de l'application
     * @author Charles Lavoie
     */
    renderRoutes(){
        return pages.map(page => {
            //cherche le rôle actuel si présent dans la liste
            //des rôles permis
            let contientRoleActuel = page.roles?.includes(this.role!!);
            let contientTous = Array.isArray(page.roles) &&
                page.roles[0] === "*";
            
            if (contientRoleActuel || contientTous){
                //retourne la bonne page si rôle accessible
                return <Route path={page.path} key={createRandomKey()} element={<page.element/>} />
            }
            else {
                //retourne la page 403 pour un rôle non valide pour la page
                return <Route path={page.path} key={createRandomKey()} element={<ForbiddenPage/>} />
            }
        })
    }

    render(){
        return (
            <Routes key={createRandomKey()}>
                {this.role && this.renderRoutes()}
                <Route path="*" element={<NotFoundPage />} key={createRandomKey()}/>
            </Routes>
        );
    }
}


export default function Router(){
    let location = useLocation();

    useEffect(()=>{
        let currentPage = location.pathname;
        

        for (const page of pages) {
            let pagePath = page.path;
            if (pagePath.includes("/:")) {
                currentPage = "/"+currentPage.split("/")[1]
                pagePath = "/"+pagePath.split("/")[1]
                if (pagePath === currentPage){
                    document.title = "ExpoSAT | " + page.name;
                    break;
                }
            }
            if (currentPage === page.path){
                document.title = "ExpoSAT | " + page.name;
                break;
            }
        }
        
    },[location])

    return <RouterManager />
}