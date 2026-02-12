import NavigationBar from './NavigationBar';
import { Path } from '../../router/routes';
import HomePage from "../../pages/Home/HomePage";
import NotFoundPage from "../../pages/404/NotFoundPage";
import {NavigationBarProps} from './NavigationBar';

const MOCK_LINK_LIST : Path[] = [
    {path:"/",name:"Accueil",element:HomePage,position:"Left",roles:["*"]},
    {path:"/informations",name:"Informations",element:NotFoundPage,position:"Left",roles:["*"]},
]

/**
 * @author Charles Lavoie
 */
test('Links returns not null array', async () => {
    const props : NavigationBarProps = {links:MOCK_LINK_LIST};
    const devList = new NavigationBar(props);

    const generate = devList.generateLinks();
    expect(generate.length >= 1).toBeTruthy()
});