/// <reference types="cypress">
const url = 'http://localhost:3000/connexion';

describe("exposat",()=>{
    beforeEach(()=>{
        cy.visit(url)
    })
    it('se connecter',()=>{
        cy.get('input[name="nomutilisateur"]').type('test@letesteur.test')
        cy.get('input[name="motdepasse"]').type('test1234')
        cy.get('button[type="submit"]').click()
        expect(cy.url()).to.not.equal(url)
    })
    it('email invalide',()=>{
        cy.get('input[name="nomutilisateur"]').type('testletesteur.test')
        cy.contains("L'adresse courriel est invalide").should('exist')
    })
    it('email vide',()=>{
        cy.get('button[type="submit"]').click()
        cy.contains("L'adresse courriel est requis").should('exist')
    })
    it('mot de passe vide',()=>{
        cy.get('button[type="submit"]').click()
        cy.contains("Le mot de passe est requis").should('exist')
    })
    it('bouton mdp oublier',()=>{
        cy.get('a[type="button"]').contains("Mot de passe oublié").click()
        cy.pause()
        cy.url().should('eq', 'http://localhost:3000/mot-de-passe-oublie')
    })
})