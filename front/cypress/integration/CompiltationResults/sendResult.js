/// <reference types="cypress">
const url = Cypress.env('BASE_URL') + 'resultat';

describe("exposat",()=>{
    beforeEach(()=>{
        cy.visit(url)
    })
    
    it('envoyer resultat',()=>{
        cy.contains('Envoyez').click()
        cy.on('window:alert', (str) => {
            expect(str).to.equal(`Resultat de l'équipe envoyé`)
          })
    })
    
})