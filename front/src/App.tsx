import React from 'react';
import { ToastContainer } from 'react-toastify';
import Router from './router/router';
import { pages, RoleName } from './router/routes';
import Footer from './components/footer/footer';
import Loader from './components/loader/loader';
import NavigationBar from './components/NavigationBar/NavigationBar';
import {ActualRole as RoleClass} from './utils/roleUtil';
import 'react-toastify/dist/ReactToastify.css';

/**
 * Composant affichant le cadre de l'application
 */
export default class App extends React.Component {
    constructor(props: {}){
        super(props)
        this.state = {}
    }

    role : RoleName | null = null;

    componentDidMount(){
        this.getRole()
    }

    async getRole(){
        this.role = await RoleClass.get();
        this.forceUpdate()
    }

    /**
     * Genere les liens pour la navbar en fonction du nom du role actuel
     */
    generateRoleLinks() {
        /**
         * Retrouve les pages accessibles par tous les rôles,
         * ou le rôle actuel et la page n'est pas Hidden.
         * @author Charles Lavoie
         */
        const filteredPages = pages.filter(page => (
            (
                // Si page.roles == ["*"].
                (Array.isArray(page.roles) &&
                page.roles[0] === "*") ||
                // OU Si page.roles contient le rôle de l'utilisateur.
                (this.role && page.roles?.includes(this.role))
            ) &&
            // ET si la page doit être affichée.
            page.position !== "Hidden"
        ));

        //Retourne les pages filtrées dans la navbar
        return <NavigationBar links={filteredPages}/>;
    }

    render(){
        return (
            <>
                <div data-testid="app" className="App">
                    <div className='content'>
                        {this.generateRoleLinks()}
                        {this.role && <Router/>}
                        {!this.role && <Loader/>}
                    </div>
                    <Footer/>
                </div>
                <ToastContainer />
            </>
        );
    }
}