import * as FileSaver from "file-saver";
import ExcelJS from "exceljs";

const defaultSheetName = "Données";
const fileType = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8";
const fileExtension = ".xlsx";

/**
 * Interface représentant une colonne à exporter
 */
export interface Column {
    /**
     * Clé du JSON.
     */
    key: string;
    /**
     * Titre à afficher dans l'Excel pour le nom de la colonne.
     */
    title: string;
}

/**
 * Convertit un tableau de JSON en tableau de tableaux.
 * @param data Tableau d'objets
 * @param columns Colonnes à inclure
 */
export function jsonArrayToAOA<T>(data: T[], columns: Column[]): any[][] {
    let arrayOfArray: any[][] = [];

    // Crée un tableau contenant tous les titres
    const titles = columns.map(column => column.title);
    arrayOfArray.push(titles);

    // Ajoute les données
    data.forEach((item: any) => {
        arrayOfArray.push(columns.map(column => item[column.key] || ""));
    });

    return arrayOfArray;
}

/**
 * Exporte des données en fichier Excel (.xlsx)
 * @param data Données à exporter
 * @param columns Colonnes à exporter
 * @param fileName Nom du fichier à générer
 * @param sheetName Nom de la feuille à générer (facultatif)
 */
export async function exportToExcel<T>(data: T[], columns: Column[], fileName: string, sheetName?: string): Promise<void> {
    const arrayOfArray = jsonArrayToAOA(data, columns);

    // Création d'un nouveau "workbook" et d'une feuille
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet(sheetName || defaultSheetName);

    // Ajout des données dans la feuille
    arrayOfArray.forEach((row) => {
        worksheet.addRow(row);
    });

    // Générer le fichier en tant que Blob
    const buffer = await workbook.xlsx.writeBuffer();
    const fileData = new Blob([buffer], { type: fileType });

    // Télécharger le fichier
    FileSaver.saveAs(fileData, fileName + fileExtension);
}