/**
 * Chaînes de caractères inclues dans le site.
 * Author: Mathieu Sévégny, Tristan Lafontaine et Jean-Philippe Bourassa
 */
export const TEXTS = {
    judgeList:{
        defaultNamePage:"Liste des juges",
        title:"Gestion des juges",
        first_name:"Nom de famille",
        last_name:"Prénom",
        email:"Email",
        category:"Catégorie",
        error:"Une erreur est survenue lors de la récupération des juges. Veuillez réessayer plus tard.",
        cancelDelete:"Annuler",
        confirmDelete:"Supprimer"
    },
    informations:{
        title:"Informations",
        error:"Une erreur est survenue lors de la récupération des informations. Veuillez réessayer plus tard.",
        buttons:{
            quitEdition:"Quitter l'édition des blocs d'informations",
            saveBlock: "Sauvegarder le bloc d'informations",
            modifyGeneral:"Aller à l'édition des blocs d'informations",
            modifyBlock:"Modifier le bloc d'informations",
            delete:"Supprimer le bloc d'informations",
            cancel:"Annuler les changements",
            add:"Ajouter un bloc d'information",
            show:"Cliquer pour que le bloc soit affiché",
            hide:"Cliquer pour que le bloc ne soit plus affiché"
        },
        confirm:{
            delete:"Êtes-vous sûr de vouloir supprimer ce bloc?",
            cancel:"Êtes-vous sûr de vouloir abandonner les modifications?"
        }
    },
    homepage:{
        titleH1:"Expo SAT",
        signupButton:"Je veux m'inscrire",
        informationsButton:"Informations",
    },
    generic:{
        goToHome:"Aller à l'accueil",
    },
    signin:{
        title:"Connexion Expo Sat",
        invalide:"Le courriel ou le mot de passe est invalide",
        email:{
            label:"Adresse courriel",
            error:{
                required:"L'adresse courriel est requis",
                email:"L'adresse courriel est invalide",
                maximum:"Le maximum est de 255 caractères",
            }
        },
        password:{
            label:"Mot de passe",
            required:"Le mot de passe est requis",
            title:"Récupération de mot de passe",
            btnforgot:"Mot de passe oublié"
        },
        btnconn:"Connexion",
        href:"/mot-de-passe-oublie",
        btnemail:"Envoyer un email"
    },
    administratorsListPage:{
        title:"Gestion des administrateurs"
    },
    ajoutAdmin:{
        title:"Ajouter un administrateur à Expo Sat",
        invalide:"L'un des champs est vide",
        invalideEmail:"L'email n'est pas au  format test@test.com",
        email:{
            label:"Adresse courriel",
            error:{
                required:"L'adresse courriel est requis",
                email:"L'adresse courriel doit être au format example@email.example",
                maximum:"Le maximum est de 255 caractères"
            }
        },
        nom:{
            label:"Nom",
            error:{
                required:"Le nom est requis",
                maximum:"Le maximum est de 50 caractères",
            }
        },
        prenom:{
            label:"Prénom",
            error:{
                required:"Le nom est requis",
                maximum:"Le maximum est de 50 caractères"
            }
        },
        nomUtilisateur:{
            label:"Utilisateur",
            error:{
                required:"Le nom est requis",
                maximum:"Le maximum est de 50 caractères"
            }
        },
        btnconn:"Soumettre",
        btnemail:"Envoyer un email",
        btnretour:"Retour"
    },
    modifyPassword:{
        title:"Change le mot de passe d'un usager",
        invalide:"Le nouveau mot de passe et la vérification de votre nouveau mot de passe ne sont pas les mêmes",
        email:{
            label:"Courriel",
            error:{
                required:"Le courriel est requis",
                maximum:"Le maximum est de 50 caractères",
                email:"L'adresse courriel est invalide",
            }

        },
        newPassword:{
            label:"Nouveau mot de passe",
            error:{
                required:"Le nouveau mot de passe est requis",
                maximum:"Le maximum est de 50 caractères",
            }
        },
        verifyPassword:{
            label:"Confirmez votre mot de passe",
            error:{
                required:"La vérification de votre nouveau mot de passe est requise",
                maximum:"Le maximum est de 50 caractères"
            }
        },
    },
    api:{
        errors:{
            communicationFailed:"La communication avec le serveur a échoué."
        }
    },
    ratingform:{
        title:"Formulaire d'évaluation",
        AddButton:"Ajouter une section",
        CancelButton:"Annuler",
        errors:{
            formNotFound:"Aucun formulaire n'a été trouvé!",
            sectionNotFound:"Aucune section n'a été trouvée!",
            questionNotFound:"Aucune question n'a été trouvée!",
            charLimit:"Le nombre maximal de caractère a été atteint!"
        }
    },
    assignRoles:{
        title:"Assignation des rôles",
        roles:{
            Admin:"Admin",
            Judge:"Juge",
            Correct:"Correcteur",
            Parti:"Participants"
        },
        error1:"Il y a eu un probleme",
    },
    signup:{
        title:"Inscription Expo SAT",
        email:{
            label:"Adresse courriel",
            error:{
                required:"L'adresse courriel est requis",
                invalide:"Le courriel est invalide. Le format doit être le suivant : @exemple.ca ",
                maximum:"Le maximum est de 255 caractères",
                empty:"Le champ du courriel est vide.",
            },
            errorContactPerson:{
                invalide:"Le courriel est invalide. Le format doit être le suivant : @cegepvicto.ca "
            }
        },
        numeroDa:{
            label:"Numéro DA",
            error:{
                required:"Le numéro DA est requis",
                maximum:"Le maximum pour le numéro de DA est de 10 caractères",
                invalid:"Le numéro DA est invalide"
            }
        },
        pictureConsent:{
            label:"Je consens à être pris en photo et j'autorise le comité à les utiliser pour faire de la publicité future (site web, médias sociaux, journaux, etc.)",
            yes:"Oui",
            no:"Non"
        },
        buttons:{
            addMember:"Ajouter un membre",
            removeMember:"Retirer un membre"
        },
        firstName:{
            label:"Prénom",
            error:{
                required:"Le prénom est requis",
                maximum:"Le maximum est de 50 caractères",
                empty:"Le champ du prénom est vide.",
            }
        },
        lastName:{
            label:"Nom de famille",
            error:{
                required:"Le nom de famille est requis",
                maximum:"Le maximum est de 50 caractères",
                empty:"Le champ du nom de famille est vide.",
            }
        },
        textMember:{
            title:"Membres de l'équipe",
            text:"- Attention à l'orthographe des noms -"
        },

        information:{
            title:"Informations sur l'équipe",
            titleStand:{
                label:"Titre du projet",
                error:{
                    required:"Le titre du projet est requis",
                    maximum:"Le maximum est de 30 caractères",
                },
                text:"Votre titre doit être accrocheur et original pour attirer l'attention des visiteurs. Faites preuve de créativité !",
            },
            descriptionStand:{
                label:"Description du projet",
                error:{
                    required:"La description du projet est requis",
                    maximum:"Le maximum est de 255 caractères",
                },
                text:"La description de votre projet doit être attrayante, précise et claire. Elle sera utilisée dans le dépliant remis aux visiteurs lors de leur arrivée à l'exposition.",
            },
            category:{
                label:"Catégorie",
                error:{
                    required:"Choisissez une catégorie"
                },
                defaultText:"Veuillez choisir une option",
                help:"Reférez-vous au Guide de l'exposant pour savoir dans quelle catégorie vous inscrire."
            },
            schoolYear:{
                label:"Année d'étude",
                checkboxOne:"1re année",
                checkboxTwo:"2e année et +",
                error:{
                    required:"Sélectionner une année d'étude"
                },
                help:"Dans le cas où les années diffèrent, le plus ancien l'emporte (2e-3e année)."
            },
            contactPerson:{
                label1:"Identification ",
                label2:"de l'",
                label3:"du ",
                label4:"enseignant(e) ressource",
                firtsLastName:{
                    label:"Prénom et nom",
                    error:{
                        required:"Le prénom et le nom est requis",
                        maximum:"Le maximum est de 50 caratères"
                    }
                },
                email:{
                    label:"Adresse courriel",
                    error:{
                        required:"L'adresse courriel est requis",
                        maximum:"Le maximum est de 255 caratères'"
                    }
                },
            },
            buttonContactPerson:{
                add:"Ajouter une ressource",
                remove:"Retirer une ressource"
            }
        }
    },
    survey:{
        isSearchingSurvey:"Recherche de vos formulaires d'évaluations...",
        surveyNotFound:"Aucun formulaire d'évaluation n'a pu être trouvé avec le jeton fourni.",
        noSurveySectionFound:"Les sections du formulaire d'évaluation n'ont pas pu être trouvées.",
        noQuestionFound:"Aucune question trouvée dans cette section.",
        backToEvaluationMenu:"Retourner au menu des évaluations",
        textButtonNextSection:"Prochaine section",
        textButtonPreviousSection:"Section précédente",
        textCompleteSurvey:"Soumettre définitivement les formulaires d'évaluations",
        textCompleteSurveySection:"Terminer la section",
        textReturnToPrincipalMenu:"Retourner au menu principal",
        textNamePageForm:"Effectuer l'évaluation de ",
        resetForm:"Réinitialiser",
        defaultNamePage:"Effectuer une évaluation",
        surveyCompletedConfirmation:"Vous avez bien remis vos formulaires d'évaluation !",
        textButtonDoSurvey:"Remplir le formulaire",
        chooseHour:"Choississez l'heure",
        SubmitEvals:"Soumettre les assignations",
        commentaire: "Commentaire",
    },
    evaluationGrid:{
        title:"Gestion des grilles d'évaluation",
        buttonCreate:"Créer un nouveau modèle de grille d'évaluation",
        buttonEdit:"Modifier le modèle",
        buttonDelete:"Supprimer le modèle",
    },
    evaluationGridForm:{
        title : "Modèle de grille d'évaluation",
        description : "Les modèles de grilles seront utilisés pour générer les questionnaires d'évaluations des projets destinés aux juges lors de l'Expo SAT.",
        name:{
            label:"Nom du modèle",
            error:{
                required:"Le nom du modèle est requis",
                maximum:"Le maximum est de 255 caractères",
            }
        },
        survey:{
            label:"Concours",
            error:{
                required:"Le concours est requis",
            }
        },
        sectionName:{
            label:"Nom de la section",
            error:{
                required:"Le nom de la section est requis",
                maximum:"Le maximum est de 255 caractères",
            }
        },
        criteriaName:{
            label:"Nom du critère",
            error:{
                required:"Le nom du critère est requis",
                maximum:"Le maximum est de 255 caractères",
            }
        },
        criteriaMaxValue:{
            label:"Poids du critère",
            error:{
                maximum:"La pondération doit se trouver entre 1 est de 100",
            }
        },
        criteriaIncrement:{
            label:"Incrément du critère",
            error:{
                maximum:"Le maximum est de 100",
            }
        },
        buttonAddSection:"Ajouter une section",
        buttonDeleteSection:"Supprimer la section",
        buttonAddCriteria:"Ajouter un critère",
        buttonDeleteCriteria:"Supprimer le critère",

        buttonSave:"Sauvegarder",
        buttonCreate:"Créer la grille",
        buttonCancel:"Annuler"
    },

    signUpSuccess: {
        title: "Inscription réussie",
        content: "Votre équipe a bien été inscrite avec les informations suivantes :",
        teamTitle: "Titre : ",
        teamDescription: "Description : ",
        teamCategory: "Catégorie : ",
        teamYear: "Année : ",
        teamMembers: "Membres : ",
        memberName: "Nom : ",
        memberNumeroDA: "Numéro DA : "
    },
    signUpJudgeSuccess: {
        title: "Inscription réussie",
        content: "Le juge a bien été inscrit avec les informations suivantes :",
        judgeName: "Nom : ",
        judgeEmail: "Courriel : ",
        judgeCategory: "Catégorie : "
        
      
    },
    signUpJudge:{
        title:"Inscription Juge",
        pwd:{
            label:"Mot de passe",
            error:{
                required:"Le mot de passe est requis",
                maximum:"Le maximum est de 255 caractères",
            }
        },
        pwdconfirm:{
            label:"Confirmation du mot de passe",
            error:{
                required:"La confirmation est requise",
                match:"Les mots de passe ne concordent pas",
            }
        }
    },
    developers:{
        title:"Liste des développeurs"
    },
    notFound:{
        message404:"La page que vous recherchez n'a pas été trouvée",
        home:"Retourner à l'accueil"
    },
    forbidden:{
        message403:"Vous n'avez pas accès à cette page",
        home:"Retourner à l'accueil"
    },
    notAvailable:{
        message:"La fonctionnalité n'est pas disponible",
        admin:"Retourner à la page d'administration"
    },
    teamsList:{
        label:"Gestion des équipes",
        labelToggleTeams:"Afficher les équipes",
        labelTogglMembers:"Afficher les membres",
        buttonGeneratorNumberStand:"Générer les numéros des équipes"
    },
    logOut:{
        retour:"Déconnexion - Retour à la page d'accueil"
    },
    footer:{
        linkPageOfficiel:"Page officielle de l'exposition Sciences Arts et Technologies",
        linkCegepVicto:"© Cégep de Victoriaville",
        listeDev:"Liste des développeurs"
    },
    vote:{
        instruction:"Veuillez choisir l'équipe pour laquelle vous désirez voter.",
        vote:"Voter",
        alreadyVote:"Merci d'avoir voté.",
    },
    /**
     * @author Charles Lavoie
     */
    admin:{
        pageTitle:"Administration",
        equipes:{
            title:"Équipes",
            /**
             * Équipes
             */
            layout1:{
                layoutName:"Équipes",
                link1:"Gestion des équipes"
            }
        },
        gestion:{
            title:"Gestion",
            /**
             * Gestion des juges
             */
            layout1:{
                layoutName:"Gestion des juges",
                link1:"Gestion des juges",
                link2:"Liste noire des juges",
                link3:"Inscription des juges",
                link4:"Ajout de juges pour une équipe"
            },
            /**
             * Gestion des emails
             */
            layout3:{
                layoutName:"Gestion des emails",
                link1:"Email non-vérifié",
                link2:"Envoi des évaluations aux juges"
            },
            /**
             * Gestion des évaluations
             */
            layout4:{
                layoutName:"Gestion des évaluations",
                link1:"Gestion des grilles d'évaluations"
            },
            /**
             * Rôles
             */
            layout6:{
                layoutName:"Rôles",
                link1:"Assignation des rôles"
            }
        },
        resultats:{
            title:"Résultats",
            /**
             * Résultats
             */
            layout1:{
                layoutName:"Résultats",
                link1:"Liste des résultats",
                link2:"Évaluations",
                link3:"Évaluations DD (Élèves)"
            }
        },
        correcteur:{
            title:"Correction d'équipe",
            /**
             * Correcteur
             */
            layout1:{
                layoutName:"Correction d'équipe",
                link1:"Aperçu",
                link2:"Tout corriger"
            }
        },
        administrateurs:{
            title:"Administrateurs",
            /**
             * Administrateurs
             */
            layout1:{
                layoutName:"Administrateurs",
                link1:"Ajout Administrateur",
                link2:"Modifier son mot de passe",
            }
        },
    },
    
    correction: {
        title:"Correction équipe",
    }
}