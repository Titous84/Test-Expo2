//Test de la passe d'inscription d'une équipe
// Permet de tester le formulaire, tester l'ajout d'un membre et tester la soumission du formualaire
const url = "http://localhost:3000/inscription"
const categories = ['Gestion', 'Humain','Intervention sociale','Monde et culture','Écocitoyenneté', 'Sciences de la vie', 'Sciences physiques','Technologies appliquées','Projet TES','Projet Spécial']
var category = categories[Math.floor(Math.random() * categories.length)];
describe('Inscription Expo SAT', () => {
    beforeEach(() =>{
        cy.visit(url)
    })
    it('Informations sur l\'équipe', () => {
        
        cy.get('[id="mui-1"]').type("Titre du stand").should('have.value', 'Titre du stand')

        cy.get('[id="mui-2"]').type("Description du stand").should('have.value', 'Description du stand')

        cy.get('[id="simple-select"]').click()

        cy.get('[data-value="'+category+'"]').click()

        cy.get('[id="mui-3"]').type("Prénom Nom").should('have.value', 'Prénom Nom')

        cy.get('[id="mui-4"]').type("test@cegepvicto.ca").should('have.value', 'test@cegepvicto.ca')

        cy.get('[id="mui-5"]').type("TestMemberFirst").should('have.value', 'TestMemberFirst')

        cy.get('[id="mui-6"]').type("TestMemberLast").should('have.value', 'TestMemberLast')

        cy.get('[id="mui-7"]').type("test.test@gmail.com").should('have.value', 'test.test@gmail.com')

        cy.get('[id="mui-8"]').type("TestMemberSecond").should('have.value', 'TestMemberSecond')

        cy.get('[id="mui-9"]').type("TestMemberSecond").should('have.value', 'TestMemberSecond')

        cy.get('[id="mui-10"]').type("test.test@live.ca").should('have.value', 'test.test@live.ca')

        cy.get('button[type="button"]').contains('Ajouter un membre').click()

        cy.get('[id="mui-11"]').type("TestMemberThird").should('have.value', 'TestMemberThird')

        cy.get('[id="mui-12"]').type("TestMemberThird").should('have.value', 'TestMemberThird')

        cy.get('[id="mui-13"]').type("testthird@gmail.com").should('have.value', 'testthird@gmail.com')

        cy.get('button[type="submit"]').contains('Soumettre').click()
    })
  })