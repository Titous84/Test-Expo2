import { Checkbox, FormControl, InputLabel, MenuItem, Select, Typography, TextField } from "@mui/material";
import { DataGrid, GridColDef } from "@mui/x-data-grid";
import { ICategories } from "../../types/TeamsList/ICategories";
import Judge from "../../types/judge";
import JudgeTableToolbar from "./JudgeTableToolbar";
import JudgeUpdate from "../../types/judgeUpdate";
import TeamsListService from "../../api/TeamsList/TeamsListService";
import UserService from "../../api/users/userService";
import { TEXTS } from "../../lang/fr";
import { useState, useEffect } from "react";
import { AlertColor } from "@mui/material";
import TemporarySnackbar from "../../components/TemporarySnackbar/TemporarySnackbar";

type SnackbarMessageType = AlertColor;

/**
 * @file Page d'affichage et de modification pour les juges actifs.
 * @author Thomas-Gabriel Paquin
 * @author Étienne Nadeau
 */
export default function JudgesListPage() {
    const [listJudge, setListJudge] = useState<Judge[]>([]);
    const [categories, setCategories] = useState<ICategories[]>([]);
    const [selectedJudgesIds, setSelectedJudgesIds] = useState<number[]>([]);
    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [isSnackbarOpen, setIsSnackbarOpen] = useState<boolean>(false);
    const [snackbarMessage, setSnackbarMessage] = useState<string>("");
    const [snackbarMessageType, setSnackbarMessageType] =
        useState<SnackbarMessageType>("error");
    const [selectedUserId, setSelectedUserId] = useState<number | null>(null);

    /**
     * @author Étienne Nadeau
     * Après l'exécution du constructeur, 
     * cette fonction va s'exécuter afin d'aller chercher les juges 
     * et les catégories.
     */
    useEffect(() => {
        getCategories();
        getJudges();
    }, []);

    /**
     * @author Thomas-Gabriel Paquin
     * @author Étienne Nadeau
     * Permet d'aller obtenir dans l'api les juges qui ont le status actif.
     */
    const getJudges = async () => {
        setIsLoading(true);
        try {
            const judgesResponse = await UserService.getAllJudges(false);
            const allJudges = judgesResponse.data || [];
            setListJudge(allJudges);

            // Filtrer initialement les juges actifs
            setIsLoading(false);
        } catch (error: any) {
            setSnackbarMessage(error.message);
            setSnackbarMessageType("error");
            setIsSnackbarOpen(true);
            setIsLoading(false);
        }
    };

    /**
     * Mets à jour un juge dans la bd avec les informations reçues.
     * @author Thomas-Gabriel Paquin
     * @author Étienne Nadeau
     * @param judge Le juge qui sera mis à jour.
     */
    const patchJudge = async (judge: JudgeUpdate) => {
        try {
            const response = await UserService.patchJudgeInfos(judge);
            if (response?.error) {
                throw new Error(response.error);
            }
            getJudges();
        } catch (error: any) {
            setSnackbarMessage(error.message);
            setSnackbarMessageType("error");
            setIsSnackbarOpen(true);
        }
    };

    /**
     * Fait une recherche de toutes les catégories présentes dans la base de données
     * @author Thomas-Gabriel Paquin
     * @author Étienne Nadeau
     */
    const getCategories = async () => {
        try {
            const response = await TeamsListService.tryGetCategories();
            if (response?.error) {
                throw new Error(response.error);
            } else if (response?.data) {
                setCategories(response.data);
            }
        } catch (error: any) {
            setSnackbarMessage(error.message);
            setSnackbarMessageType("error");
            setIsSnackbarOpen(true);
        }
    };

    /**
     * Met à jour les données des states vers les nouvelles données
     * @author Thomas-Gabriel Paquin
     * @author Étienne Nadeau
     * @param userId L'id du user associé au juge à modifier
     * @param displayData Les données qui sont modifier dans le juge
     */
    const editJudge = async (userId: number, displayData: Judge) => {

        const judge: JudgeUpdate = {
            id: userId,
            firstName: displayData.firstName,
            lastName: displayData.lastName,
            categoryId: categories.find((element) => element.name === displayData.category)?.id ?? 0,
            email: displayData.email,
            activated: displayData.activated,
            blacklisted: displayData.blacklisted,
        };
        await patchJudge(judge);
    };

    /**
     * @author Étienne Nadeau
     * Méthode permettant de supprimer les juges sélectionnés dans le tableau.
     */
    const deleteSelectedJudges = () => {
        if (selectedJudgesIds.length > 0) {
            /* 
            * Appel de la méthode de suppression pour chaque juge sélectionné
            * Utilisation de Promise.all pour attendre que toutes les suppressions soient terminées
            * Inspirer de: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise/all
            */
            Promise.all(selectedJudgesIds.map((judgeIds) => UserService.delete_user(judgeIds)))
                .then(() => {
                    setListJudge((currentJudges) => {
                        return currentJudges.filter(
                            (judge) => !selectedJudgesIds.includes(judge.id)
                        );
                    });
                    // Après la suppression, refetch et re-filter les juges
                    getJudges();
                    setSnackbarMessage("Les juges sélectionnés ont été supprimés avec succès."); // Message corrigé
                    setSnackbarMessageType("success");
                    setIsSnackbarOpen(true);
                })
                .catch((error: Error) => {
                    setSnackbarMessage(error.message);
                    setSnackbarMessageType("error");
                    setIsSnackbarOpen(true);
                });
        }
        else {
            setSnackbarMessage("Aucun juge n'a été sélectionné pour la suppression.");
            setSnackbarMessageType("warning");
            setIsSnackbarOpen(true);
        }
    };

    /**
     * @author Étienne Nadeau
     * Définition de l'affichage des colonnes utilisées dans le tableau de gestion des juges.
     * Inspirer de: https://mui.com/x/react-data-grid/column-definition/
     */
    const columns: GridColDef[] = [
        {
            field: "firstName",
            headerName: "Prénom",
            width: 150,
            editable: true,
            renderEditCell: (params) => {
                return (
                    <FormControl fullWidth>
                        <TextField
                            id="text-firstName"
                            value={params.value}
                            onChange={(event) => {
                                // Mettre à jour la valeur du prénom dans le DataGrid
                                params.api.setEditCellValue({
                                    id: params.id,
                                    field: 'firstName',
                                    value: event.target.value,
                                });
                            }}
                            /*
                            * Il faut cliquer en dehors du champ pour que la modification soit prise en compte
                            * Inspirer de: https://www.w3schools.com/jsref/event_onblur.asp
                            */
                            onBlur={() => {

                                editJudge(params.row.id, { ...params.row, firstName: params.row.firstName });
                                setSnackbarMessage("Le prénom a été modifié.");
                                setSnackbarMessageType("success");
                                setIsSnackbarOpen(true);
                            }
                            }
                        />
                    </FormControl>
                );
            },
        },
        {
            field: "lastName", headerName: "Nom", width: 150, editable: true,
            renderEditCell: (params) => {
                return (
                    <FormControl fullWidth>
                        <TextField
                            id="text-lastName"
                            value={params.value}
                            onChange={(event) => {
                                // Mettre à jour la valeur du nom dans le DataGrid
                                params.api.setEditCellValue({
                                    id: params.id,
                                    field: 'lastName',
                                    value: event.target.value,
                                });
                            }}
                            /*
                            * Il faut cliquer en dehors du champ pour que la modification soit prise en compte
                            * Inspirer de: https://www.w3schools.com/jsref/event_onblur.asp
                            */                            
                            onBlur={() => {
                                editJudge(params.row.id, { ...params.row, lastName: params.row.lastName });
                                setSnackbarMessage("Le nom a été modifié.");
                                setSnackbarMessageType("success");
                                setIsSnackbarOpen(true);

                            }}
                        />
                    </FormControl>
                );
            },
        },
        {
            field: "category", headerName: "Catégorie", width: 150, editable: true,
            renderEditCell: (params) => (
                <FormControl fullWidth>
                    <InputLabel id="label-category">Catégorie</InputLabel>
                    <Select
                        labelId="label-category"
                        id="select-category"
                        value={params.value}
                        onChange={(event) => {
                            // Mettre à jour la valeur de la catégorie dans le DataGrid
                            params.api.setEditCellValue({
                                id: params.id,
                                field: 'category',
                                value: event.target.value
                            });
                        }}
                        /*
                        * Il faut cliquer en dehors du champ pour que la modification soit prise en compte
                        * Inspirer de: https://www.w3schools.com/jsref/event_onblur.asp
                        */
                        onBlur={() => {

                            editJudge(params.row.id, { ...params.row, category: params.value });
                            setSnackbarMessage("La catégorie a été modifiée.");
                            setSnackbarMessageType("success");
                            setIsSnackbarOpen(true);

                        }}
                    >
                        {categories.map((category) => (
                            <MenuItem key={category.id} value={category.name}>{category.name}</MenuItem>
                        ))}
                    </Select>
                </FormControl>

            )
        },
        {
            field: "email", headerName: "Courriel", width: 200, editable: true, renderEditCell: (params) => {
                return (
                    <FormControl fullWidth>
                        <TextField
                            id="text-email"
                            value={params.value}
                            onChange={(event) => {
                                // Mettre à jour la valeur de l'e-mail dans le DataGrid
                                params.api.setEditCellValue({
                                    id: params.id,
                                    field: 'email',
                                    value: event.target.value,
                                });
                            }}
                        /*
                        * Il faut cliquer en dehors du champ pour que la modification soit prise en compte
                        * Inspirer de: https://www.w3schools.com/jsref/event_onblur.asp
                        */
                       onBlur={() => {
                           editJudge(params.row.id, { ...params.row, email: params.row.email });
                           setSnackbarMessage("L'e-mail a été modifié.");
                           setSnackbarMessageType("success");
                           setIsSnackbarOpen(true);
                       }}
                        />
                    </FormControl>
                );
            },
        },
        {
            field: "activated",
            headerName: "Activé",
            editable: true,
            width: 120,
            renderEditCell: (params) => (
                <Checkbox
                    checked={params.value}
                    onChange={(event) => {
                        // Mettre à jour la valeur de l'activation dans le DataGrid
                        params.api.setEditCellValue({
                            id: params.id,
                            field: "activated",
                            value: event.target.checked,
                        });
                    }}
                    /*
                    * Il faut cliquer en dehors du champ pour que la modification soit prise en compte
                    * Inspirer de: https://www.w3schools.com/jsref/event_onblur.asp
                    */                    
                    onBlur={() => {
                        editJudge(params.row.id, {
                            ...params.row,
                            activated: params.row.activated ? 1 : 0,
                        });
                        setSnackbarMessage(`L'état "Activé" a été modifié.`);
                        setSnackbarMessageType("success");
                        setIsSnackbarOpen(true);
                    }}
                />
            ),
            valueFormatter: (params) => (params ? "Oui" : "Non"),
        },
        {
            field: "blacklisted",
            headerName: "Liste noire",
            width: 120,
            editable: true,
            renderEditCell: (params) => (
                <Checkbox
                    checked={params.value}
                    onChange={(event) => {
                        // Mettre à jour la valeur de la liste noire dans le DataGrid
                        params.api.setEditCellValue({
                            id: params.id,
                            field: "blacklisted",
                            value: event.target.checked,
                        });
                    }}
                    /*
                    * Il faut cliquer en dehors du champ pour que la modification soit prise en compte
                    * Inspirer de: https://www.w3schools.com/jsref/event_onblur.asp
                    */
                    onBlur={() => {

                        editJudge(params.row.id, {
                            ...params.row,
                            blacklisted: params.row.blacklisted ? 1 : 0,
                        });
                        setSnackbarMessage(`L'état "Liste noire" a été modifié.`);
                        setSnackbarMessageType("success");
                        setIsSnackbarOpen(true);
                    }}
                />
            ),
            valueFormatter: (params) => (params ? "Oui" : "Non"),
        },
    ];

    /**
     * @author Thomas-Gabriel Paquin
     * @author Étienne Nadeau
     * @returns Retourne un tableau contenant les juges actifs et l'option de les modifier.
     */
    return (
        <div
            data-testid="judge-list"
            style={{
                alignContent: "center",
                display: "flex",
                flexDirection: "column",
                alignItems: "center",
            }}
        >
            {/* Snackbar caché par défaut qui affiche les messages */}
            <TemporarySnackbar
                parentIsSnackbarOpen={isSnackbarOpen} // Partager à l'enfant la valeur de la variable d'état pour qu'il sache si le snackbar doit être affiché.
                parentSetIsSnackbarOpen={setIsSnackbarOpen} // Passer une référence de la méthode de changement de la variable d'état pour que l'enfant puisse la déclencher.
                message={snackbarMessage} // Passer un message à afficher dans le snackbar.
                snackbarMessageType={snackbarMessageType} // Passer le type de message pour changer la couleur du snackbar.
            />

            <Typography variant="h4" sx={{ mt: 4, mb: 2 }}>
                {TEXTS.judgeList.title}
            </Typography>

            <DataGrid
                columns={columns}
                rows={listJudge} // Liste des juges
                checkboxSelection
                disableRowSelectionOnClick
                getRowId={(row) => row.id}
                loading={isLoading}

                sx={{ width: "100%", minHeight: 400 }}
                slots={{
                    toolbar: (props) => (
                        <JudgeTableToolbar
                            {...props}
                            selectedJudges={listJudge.filter(judge => selectedJudgesIds.includes(judge.id))} // Passer les juges sélectionnés
                            deleteSelectedJudge={deleteSelectedJudges}
                            setSelectedUserId={setSelectedUserId} // Passer la méthode de suppression des juges sélectionnés
                        />
                    ),
                }}
                onRowSelectionModelChange={(newSelection) => {
                    setSelectedJudgesIds(newSelection as number[]);
                }} // Met à jour les juges sélectionnés
            />
        </div>
    );
}