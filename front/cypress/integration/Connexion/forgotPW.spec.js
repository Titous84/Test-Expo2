/// <reference types="cypress">
const url = 'http://localhost:3000/mot-de-passe-oublie';

describe("exposat",()=>{
    beforeEach(()=>{
        cy.visit(url)
    })
    it('email invalide',()=>{
        cy.get('input[name="Adresse courriel"]').type('testletesteur.test')
        cy.contains("L'adresse courriel est invalide").should('exist')
    })
    it('email vide',()=>{
        cy.get('button[type="submit"]').click()
        cy.contains("L'adresse courriel est requis").should('exist')
    })
})