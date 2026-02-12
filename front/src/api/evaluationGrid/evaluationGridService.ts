import APIResult from "../../types/apiResult";
import { APIRequest } from "../apiUtils";
import { IEvaluationGrid } from "../../types/evaluationGrid/IEvaluationGrid";

/**
 * Classe contenant les appels d'API en lien avec la grille d'évaluation.
 */
export default class EvaluationGridService{
    /**
     * Cherche les grilles d'évaluation.
     * @author Raphaël Boisvert
     * @returns Le retour de l'API contenant les informations de la grille d'évaluation.
     */
    public static async getEvaluationGrid() : Promise<APIResult<IEvaluationGrid[]>>{
        const response : APIResult<IEvaluationGrid[]> = await APIRequest("evaluationGrid","GET",true)

        return response;
    }

    // Chercher une grille d'évaluation par son id
    public static async getEvaluationGridById(id:number) : Promise<APIResult<IEvaluationGrid>>{
        const response : APIResult<IEvaluationGrid> = await APIRequest(`evaluationGrid/${id}`,"GET",true)

        return response;
    }

    // Créer une nouvelle grille d'évaluation
    public static async insertEvaluationGrid(evaluationGrid:IEvaluationGrid){

        const body = {
            "name":evaluationGrid.name,
            "rating_section":evaluationGrid.sections,
        }
        
        const response : APIResult<number> = await APIRequest("evaluationGrid","POST",true,body)

        return response;
    }

    // Modifier une grille d'évaluation
    public static async updateEvaluationGrid(evaluationGrid:IEvaluationGrid){
        const body = {
            "name":evaluationGrid.name,
            "id":evaluationGrid.id,
            "rating_section":evaluationGrid.sections,
        }

        const response : APIResult<boolean> = await APIRequest(`evaluationGrid`,"PATCH",true,body)

        return response;
    }

    // Supprimer une grille d'évaluation
    public static async deleteEvaluationGrid(id:number){
        const response : APIResult<boolean> = await APIRequest(`evaluationGrid/${id}`,"DELETE",true)

        return response;
    }
}
