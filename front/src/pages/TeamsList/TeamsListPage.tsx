import { Box, ToggleButton, ToggleButtonGroup, Typography } from '@mui/material';
import IPage from "../../types/IPage";
import { TEXTS } from '../../lang/fr';
import AllTeamsMembersTable from '../../components/TeamsListPage/AllTeamsMembersTable/AllTeamsMembersTable';
import TeamsTable from '../../components/TeamsListPage/TeamsTables/TeamsTable';

/**
 * Variables d'état du composant React: TeamsListPage.
 * 
 * @property {ITeams[]} teams - Liste des équipes et leurs membres.
 * @property {ICategories[]} categories - Liste des catégories d'équipes.
 * @property {ISurvey[]} survey - Liste des types d'évaluation.
 * 
 * @author Carlos Cordeiro
 */
interface TeamsListPageState {
    teamsTableFormat: "teams" | "members";
}

/**
 * Page de gestion des équipes.
 * Affiche toutes les équipes et leurs membres.
 */
export default class TeamsListPage extends IPage<{}, TeamsListPageState> {
    constructor(props: {}) {
        super(props)

        this.state = {
            teamsTableFormat: "teams"
        }
    }

    public render() {
        return (
            <Box sx={{ mb: 4 }}>
                {/* Titre du contenu */}
                <Typography variant="h4" sx={{ mt:4, mb:2 }}>{TEXTS.teamsList.label}</Typography>

                {/* Bouton bascule pour le format du tableau */}
                {/* (soit le tableau des équipes, soit le tableau des membres) */}
                <ToggleButtonGroup
                    exclusive
                    color="primary"
                    value={this.state.teamsTableFormat}
                    onChange={(event, newValue) => {
                        if (newValue !== null) {
                            this.setState({ teamsTableFormat: newValue });
                        }
                    }}
                    sx={{ mb: 2 }}
                >
                    <ToggleButton value="teams">Vue des équipes</ToggleButton>
                    <ToggleButton value="members">Vue des membres</ToggleButton>
                </ToggleButtonGroup>

                {/* Le tableau affiché */}
                {this.state.teamsTableFormat === "teams" ? (
                    <TeamsTable />
                ) : (
                    <AllTeamsMembersTable />
                )}
            </Box>
        )
    }
}