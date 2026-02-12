import { ContentPaste as ContentPasteIcon, Groups as GroupsIcon, ManageAccounts as ManageAccountsIcon, Percent as PercentIcon, Person as PersonIcon, Schedule as ScheduleIcon } from '@mui/icons-material';
import { blue, green, grey, orange, red } from "@mui/material/colors";
import { OverridableComponent } from "@mui/material/OverridableComponent";
import { SvgIconTypeMap } from "@mui/material/SvgIcon/SvgIcon";
import EvaluationGridsListPage from '../../pages/EvaluationGridsList/EvaluationGridsListPage';
import EvaluationsResultsListPage from "../../pages/EvaluationsResultsList/EvaluationsResultsListPage";
import JudgesListPage from "../../pages/JudgesList/JudgesListPage";
import JudgesSchedulesPage from "../../pages/JudgesSchedules/JudgesSchedulesPage";
import TeamsListPage from "../../pages/TeamsList/TeamsListPage";
import AdministratorsListPage from "../../pages/AdministratorsList/AdministratorsListPage";

/**
 * Un onglet dans la barre latérale de navigation de la page d'administration.
 * 
 * @property {string} id - Un nom unique pour identifier l'onglet. Sert d'id pour pouvoir récupérer un onglet parmi ceux dans ADMINISTRATION_MAIN_PAGE_TABS.
 * @property {string} primaryText - Le texte principal affiché dans l'onglet. C'est comme le titre.
 * @property {string} secondaryText - Le texte secondaire affiché dans l'onglet. C'est comme la description.
 * @property {OverridableComponent<SvgIconTypeMap<{}, "svg">> & { muiName: string; }} icon - Une icône MUI.
 * @property {string} iconColor - La couleur de l'icône. Peut être une chaîne de caractères retrouvée dans les couleurs MUI.
 * @property {React.ComponentType<any>} componentToDisplayInContentZone - Le composant React de la page à afficher dans la zône du contenu lorsque cet onglet est sélectionné.
 */
export interface AdministrationMainPageTab {
    id: string;
    primaryText: string;
    secondaryText: string;
    icon: OverridableComponent<SvgIconTypeMap<{}, "svg">> & { muiName: string; }; // Quand on hover les icônes MUI avec la souris dans VSCode, c'est ça le type de variable.
    iconColor: string; // Les couleurs MUI sont en fait des chaînes de caractères ex: red[500] retourne "#f44336".
    componentToDisplayInContentZone: React.ComponentType<any>;
}

/**
 * Liste des onglets de la barre latérale de navigation dans la page d'administration.
 * @type {AdministrationMainPageTab[]} Un tableau d'objets représentant un onglet de la barre latérale.
 */
export const ADMINISTRATION_MAIN_PAGE_TABS: AdministrationMainPageTab[] = [
    {
        id: "equipes",
        primaryText: "Équipes",
        secondaryText: "Liste des équipes et membres (les participants)",
        componentToDisplayInContentZone: TeamsListPage,
        icon: GroupsIcon,
        iconColor: red[500]
    },
    {
        id: "juges",
        primaryText: "Juges",
        secondaryText: "Création, modification, suppression, liste noire et liste des juges",
        componentToDisplayInContentZone: JudgesListPage,
        icon: PersonIcon,
        iconColor: orange[500]
    },
    {
        id: "horaires-juges",
        primaryText: "Horaires des juges",
        secondaryText: "Assignation des juges aux équipes",
        componentToDisplayInContentZone: JudgesSchedulesPage,
        icon: ScheduleIcon,
        iconColor: green[500]
    },
    {
        id: "resultats",
        primaryText: "Résultats",
        secondaryText: "Résultats donnés par les juges",
        componentToDisplayInContentZone: EvaluationsResultsListPage,
        icon: PercentIcon,
        iconColor: blue[500]
    },
    {
        id: "modeles-grilles-evaluation",
        primaryText: "Modèles de grilles d'évaluation",
        secondaryText: "Créer, modifier et supprimer des modèles de grilles d'évaluation",
        componentToDisplayInContentZone: EvaluationGridsListPage,
        icon: ContentPasteIcon,
        iconColor: grey[500]
    },
    {
        id: "administrateurs",
        primaryText: "Administrateurs",
        secondaryText: "Ajouter et supprimer des administrateurs et modifier leur mot de passe",
        componentToDisplayInContentZone: AdministratorsListPage,
        icon: ManageAccountsIcon,
        iconColor: grey[500]
    }
];