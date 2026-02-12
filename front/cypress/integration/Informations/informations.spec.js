/// <reference types="cypress">
const url = 'http://localhost:3000/connexion';
const infoURL = 'http://localhost:3000/informations';

/**
 * @author Mathieu Sévégny
 */
describe("exposat",()=>{
    beforeEach(()=>{
        cy.visit(infoURL)
    })
    it("vérifie l'accès",()=>{ 
        setAdminLocalStorage()

        cy.get('span[id="openEditor"]').click();

        cy.get('span[id="closeEditor"]').should('exist')
    })
})
function setAdminLocalStorage(){
    window.localStorage.setItem("token", "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9wcm9kLmV4cG9zYXQuY29tIiwiYXVkIjoiaHR0cDpcL1wvcHJvZC5leHBvc2F0LmNvbSIsImp0aSI6IjEiLCJpYXQiOjE2NTA4OTUwNDEsInJvbGVfaWQiOjAsIm5iZiI6MTY1MDg5NTEwMSwiZXhwIjoxNjUwODk4NjQxfQ.NMJoFjrxptixMuJNWSeSGZcybmMUdBWmbmhcvjPf4ec");
}