import APIResult from "../../types/apiResult";
import { APIRequest } from "../apiUtils";
import TeamInfo from "../../types/sign-up/team-info";
import { ITeamsMember } from "../../types/TeamsList/ITeamsMember";
import { ITeam } from "../../types/TeamsList/ITeam";
import { ICategories } from "../../types/TeamsList/ICategories";
import { ISurvey } from "../../types/ISurvey";

/**
 * API pour la page gestion des équipes
 * @author Tristan Lafontaine, Carlos Cordeiro
 */
export default class TeamsListService {
  
  /**
   * Obtient les équipes et les membres
   * @returns ITeamsList[]
   */
  public static async tryGetTeamsMembers(): Promise<
    APIResult<ITeamsMember[]>
  > {
    const response: APIResult<ITeamsMember[]> = await APIRequest(
      "gestion-equipes",
      "GET",
      true
    );
    return response;
  }

  /**
   * Tente d'obtenir les équipes avec les membres regroupés
   * @returns ITeam[]
   */
  public static async tryGetTeamsMembersConcats(): Promise<APIResult<ITeam[]>> {
    const response: APIResult<ITeam[]> = await APIRequest(
      "gestion-equipes/teams-infos",
      "GET",
      true
    );
    return response;
  }

  /**
   * Tente d'obtenir les catégories
   * @returns ICategories[]
   */
  public static async tryGetCategories(): Promise<APIResult<ICategories[]>> {
    const response: APIResult<ICategories[]> = await APIRequest(
      "gestion-equipes/categories",
      "GET",
      true
    );

    return response;
  }

  /**
 * Tente d'obtenir les types d'évaluations
 * @returns ISurvey[]
 */
  public static async tryGetSurvey(): Promise<APIResult<ISurvey[]>> {
    const response: APIResult<ISurvey[]> = await APIRequest(
      "gestion-equipes/survey",
      "GET",
      true
    );

    return response;
  }

  /**
   * Obtient l'équipe avec les membres dans une seul colonne.
   * @param id L'id de l'équipe recherchée.
   * @returns TeamInfo
   */
  public static async tryGetTeamWithMembers(id: number): Promise<APIResult<{ team: TeamInfo }>> {
    const response: APIResult<{ team: TeamInfo }> = await APIRequest(
      `gestion-equipes/team-info/${id}`,
      "GET",
      true
    );
    return response;
  }

  /**
   * Obtient les membres d'une équipe via l'ID de celle-ci
   * @param teamId L'id de l'équipe recherchée.
   * @returns ITeam[]
   */
  public static async getMembersByTeamId(teamId: number): Promise<APIResult<ITeam[]>> {
    const response: APIResult<ITeam[]> = await APIRequest(
        `gestion-equipes/teams/${teamId}/members`,
        "GET",
        true
    );
    return response;
  }

  /**
   * Permet de créer un membre d'équipe
   * @param member ITeamMember
   * @returns string
   */
  public static async postTeamsMembers(member: ITeamsMember): Promise<APIResult<string>> {
    const body = {
      member,
    };
    const response: APIResult<string> = await APIRequest(
      "gestion-equipes/teams-members",
      "POST",
      true,
      body
    );
    return response;
  }

  /**
   * Permet de mettre à jour les informations des équipes
   * @param team ITeamsTeamsInfos
   * @returns string
   */
  public static async patchTeamsInfos(team: ITeam): Promise<APIResult<string>> {
    const body = {
      team,
    };
    const response: APIResult<string> = await APIRequest(
      "gestion-equipes/teams-infos",
      "PATCH",
      true,
      body
    );
    return response;
  }

  /**
   * Permet de mettre à jour les informations des membres
   * @param member ITeamMember
   * @returns string
   */
  public static async patchTeamsMembers(member: ITeamsMember): Promise<APIResult<string>> {
    const body = {
      member,
    };
    const response: APIResult<string> = await APIRequest(
      "gestion-equipes/teams-members",
      "PATCH",
      true,
      body
    );
    return response;
  }

  /**
   * Permet de mettre à jour les numéros des équipes
   * @param team ITeamsTeamsInfos[]
   * @returns string
   */
  public static async patchTeamsNumbers(team: { team_id: number, team_number: string }[]): Promise<APIResult<string>> {
    const body = {
      team,
    };
    const response: APIResult<string> = await APIRequest(
      "gestion-equipes/teams-numbers",
      "PATCH",
      true,
      body
    );
    return response;
  }

  /**
   * Permet de supprimer les membres d'une équipe à partir de leur ID
   * @param team number[]
   * @returns string
   */
  public static async deletesTeamsMembers(team: number[]): Promise<APIResult<string>> {
    try {
        const body = { team };
        const response: APIResult<string> = await APIRequest(
            "gestion-equipes/teams-members",
            "DELETE",
            true,
            body
        );
        return response;
    } catch (error) {
        return { data: undefined, error: "Une erreur est survenue lors de la suppression des membres." };
    }
  }

  /**
   * Permet de supprimer les équipes à partir de leur ID
   * @param team number[]
   * @returns string
   */
  public static async deletesTeamsInfos( team: number[] ): Promise<APIResult<string>> {
    const body = { team };
    const response: APIResult<string> = await APIRequest(
      "gestion-equipes/teams-infos",
      "DELETE",
      true,
      body
    );
    return response;
  }
}
