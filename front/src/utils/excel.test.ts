import { Column, jsonArrayToAOA } from "./excel";
import { AOATester } from "./utils";

const jsonArrayMock = [
    {id:"1",name:"Test1"},
    {id:"2",name:"Test2"},
]
const badJsonArrayMock = [
    {ide:"1",name:"Test1"},
    {ide:"2",name:"Test2"},
]

const columnArrayMock : Column[] = [
    {key:"id",title:"Id"},
    {key:"name",title:"Name"}
]
const badColumnArrayMock : Column[] = [
    {key:"id",title:"Id"},
    {key:"nam",title:"Name"}
]

const goodAOA : any[][]= [
    ["Id","Name"],
    ["1","Test1"],
    ["2","Test2"]
]

/**
 * @author Mathieu Sévégny
 */
test('Entrées correctes tableau de tableaux', async () => {
    const aoa = jsonArrayToAOA(jsonArrayMock,columnArrayMock)
    expect(AOATester(aoa,goodAOA)).toBeTruthy()
});
/**
 * @author Mathieu Sévégny
 */
test('Json invalide tableau de tableaux', async () => {
    const aoa = jsonArrayToAOA(badJsonArrayMock,columnArrayMock)
    expect(AOATester(aoa,goodAOA)).toBeFalsy()
    //Vérifie le premier élément de la deuxième rangé (doit être undefined)
    expect(aoa[1][0] === undefined).toBeTruthy()
});
/**
 * @author Mathieu Sévégny
 */
test('Colonnes invalides tableau de tableaux', async () => {
    const aoa = jsonArrayToAOA(jsonArrayMock,badColumnArrayMock)
    expect(AOATester(aoa,goodAOA)).toBeFalsy()
    //Vérifie le deuxième élément de la deuxième rangé (doit être undefined)
    expect(aoa[1][1] === undefined).toBeTruthy()
});
/**
 * @author Mathieu Sévégny
 */
test('Entrées toutes incorrectes tableau de tableaux', async () => {
    const aoa = jsonArrayToAOA(badJsonArrayMock,badColumnArrayMock)
    expect(AOATester(aoa,goodAOA)).toBeFalsy()
    //Vérifie les éléments de la deuxième rangé (doivent être undefined)
    expect(aoa[1][0] === undefined).toBeTruthy()
    expect(aoa[1][1] === undefined).toBeTruthy()
});

  
  