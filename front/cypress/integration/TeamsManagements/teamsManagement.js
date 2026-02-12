const URL = 'http://localhost:3000/gestion-equipes';

/**
 * @author Tristan Lafontaine
 */
describe("Gestion des equipes",()=>{
    beforeEach(()=>{
        cy.visit(URL)
        setAdminLocalStorage()
    })
    it("vérifie l'accès",()=>{ 
        cy.get('[type="checkbox"]').check({force:true})
        cy.get('button[type="button"]').contains('Générer les numéros des stands').click()

        cy.get('button[data-testid="Rechercher-iconButton"]').click()
        cy.get('[id="mui-39"]').type(311).should('have.value', 311)
        cy.get('button[class="MuiButtonBase-root MuiIconButton-root MuiIconButton-sizeMedium tss-52j81d-MUIDataTableSearch-clearIcon css-78trlr-MuiButtonBase-root-MuiIconButton-root"]').click()
        cy.get('button[data-testid="Voir les colonnes-iconButton"]').click()
        cy.get('input[value="team_id"]').click()
        cy.get('input[value="team_number"]').click()
        cy.get('input[value="contact_person_email"]').click()
        cy.get('input[value="year"]').click()
        cy.get('input[value="category"]').click()
        cy.get('input[value="members"]').click()
        cy.get('input[value="contact_person_name"]').click()
        cy.get('input[value="survey"]').click()
        cy.get('input[value="teams_activated"]').click()
        cy.get('button[aria-label="Close"]').click()
        cy.get('button[aria-label="Modifier"]').click({multiple: true})
        cy.get('input[id="title"]').clear().type('Test').should('have.value','Test')
        cy.get('button[aria-label="Cancel"]').click({ multiple: true })
        cy.get('button[aria-label="Modifier"]').click({multiple: true})
        cy.get('input[id="title"]').clear().type('Test').should('have.value','Test')
        cy.get('button[aria-label="Valider"]').click({ multiple: true })
    })
})
function setAdminLocalStorage(){
    window.localStorage.setItem("token", "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6MzAwMCIsImF1ZCI6Imh0dHA6XC9cL2xvY2FsaG9zdDozMDAwIiwianRpIjoiMSIsImlhdCI6MTY1MDkxNTA0Nywicm9sZV9pZCI6MCwibmJmIjoxNjUwOTE1MTA3LCJleHAiOjE2NTA5MTg2NDd9.GbuosAGXOFUgy49gekJX4pbrobjGKSyV2o9baGQhJJ4");
}