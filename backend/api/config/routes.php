
<?php
use Slim\Routing\RouteCollectorProxy;
use App\Actions\DefaultAction;
// Obtenir un token pour avoir accès à l'API
use App\Actions\Token\GetTokenAction;
// Informations sur l'événement
use App\Actions\Informations\GetInformationsAction;
use App\Actions\Informations\CreateInformationBlockAction;
use App\Actions\Informations\UpdateInformationBlockAction;
use App\Actions\Informations\DeleteInformationBlockAction;
use App\Actions\Informations\UpdateOrderInformationBlockAction;
// Utilisateurs
use App\Actions\Users\GetUser;
use App\Actions\Users\GetUserAllRoleAction;
use App\Actions\Users\GetUserRoleAction;
use App\Actions\Users\ModifyUserRoleAction;
use App\Actions\Users\PasswordForgotenAction;
use App\Actions\Users\ValidateEmail;
use App\Actions\Users\GetActiveUsersAction;
use App\Actions\Users\AddUserAction;
use App\Actions\Users\ChangePasswordAction;
// Administrateurs
use App\Actions\Administrators\GetAllAdministratorsAction;
use App\Actions\Administrators\DeleteAdministratorsByIdsAction;
use App\Actions\Administrators\PostAdministratorAction;

// ?
use App\Actions\SignUpTeamAction\PostSignUp;
use App\Actions\SignUpCategoryAction\SignUpCategory;
// Juges
use App\Actions\Judge\GetJudgesAction;
use App\Actions\Judge\PostJudgeUserAction;
use App\Actions\Judge\PostJudgeActivateAction;
use App\Actions\Judge\DeleteJudgeAction;
use App\Actions\Judge\SendEmailAction;
use App\Actions\Judge\PatchJudgeAction;
// ?
use App\Actions\Stand\InsertTimeStandAction;
use App\Actions\Stand\ConflictStandAction;
use App\Actions\Stand\GetStandSurveyAction;
// ?
use App\Actions\JudgeStand\GetStandAction;
use App\Actions\JudgeStand\GetJudgeStandAction;
use App\Actions\JudgeStand\GetScoreExclusionsAction;
use App\Actions\JudgeStand\GetTimeSlotsAction;
use App\Actions\JudgeStand\DeleteTimeSlotActions;
use App\Actions\JudgeStand\PostEvaluationAction;
use App\Actions\JudgeStand\PatchGlobalScoreAction;
use App\Actions\JudgeStand\AddTimeSlotActions;
use App\Actions\JudgeStand\SaveTimeSlotsActions;
use App\Actions\JudgeStand\PatchEvaluationAction;
use App\Actions\JudgeStand\DeleteEvaluationAction;
// Grilles d'évaluation
use App\Actions\Survey\GetAllSurveyJudgeAction;
use App\Actions\Survey\Question\Result\SetQuestionResultAction;
use App\Actions\Survey\GetSurveyScoreAction;
// Équipes et membres (Liste)
use App\Actions\TeamsList\GetTeamsMembers;
use App\Actions\TeamsList\GetTeamsInfos;
use App\Actions\TeamsList\GetTeamInfoById;
use App\Actions\TeamsList\AddTeamMember;
use App\Actions\TeamsList\UpdateTeamsInfos;
use App\Actions\TeamsList\UpdateTeamsMembers;
use App\Actions\TeamsList\DeleteTeamsMembers;
use App\Actions\TeamsList\UpdateTeamsNumbers;
use App\Actions\TeamsList\DeleteTeamsInfos;
use App\Actions\TeamsList\GetCategories;
// Sondages (Surveys)
use App\Actions\Survey\CloseSurveyAction;
use App\Actions\Survey\SendAllSurveyJudgeAction;
use App\Actions\Survey\SendAllSurveyJudgeIndividuallyAction;
use App\Actions\Survey\SetCommentResultAction;

// Grilles d'évaluation (Evaluation Grid)
use App\Actions\EvaluationGrid\GetEvaluationGridAction;
use App\Actions\EvaluationGrid\GetEvaluationGridByIdAction;
use App\Actions\EvaluationGrid\CreateEvaluationGridAction;
use App\Actions\EvaluationGrid\UpdateEvaluationGridAction;
use App\Actions\EvaluationGrid\DeleteEvaluationGridAction;



// Résultats (Resultat)
use App\Actions\Resultat\GetResultatAction;
use App\Actions\Resultat\GetSendAction;
use App\Actions\Resultat\DeleteResultJudge;
// Codes de vérification (Verification Code)
use App\Actions\VerificationCode\VerificationCodeGenerateAction;
use App\Actions\VerificationCode\VerificationCodeValidAction;
use App\Actions\VerificationCode\VerificationCodeDeleteAction;


$app->options('/{routes:.+}', function ($request, $response) {
    return $response;
});

$app->group('/api', function (RouteCollectorProxy $group) {
    
    //Page par défaut
	$group->get("/", DefaultAction::class);
    
	//Page permettant d'obtenir un token
	$group->post("/token", GetTokenAction::class);
    
	$group->group('/signup', function (RouteCollectorProxy $signUpGroup) {
		//Page permettant d'ajouter une équipe
		$signUpGroup->post("/", PostSignUp::class);

		//Page permettant d'obtenir les catégories
		$signUpGroup->get("/category", SignUpCategory::class);
	});

	$group->group('/user', function (RouteCollectorProxy $userGroup) {
		//Permet de valider de courriel
		$userGroup->get("/validate-email/{token}", ValidateEmail::class);

		//Page permettant d'ajouter un user 
		$userGroup->post("/addUser", AddUserAction::class);

		//Page permettant d'ajouter un user 
		$userGroup->post("/password-email", PasswordForgotenAction::class);

		//Page permettant d'obtenir une liste d'usagers actifs
		$userGroup->get("/get-active", GetActiveUsersAction::class);

		//Page permettant d'obtenir une liste d'usagers actifs
		$userGroup->post("/change-role", ModifyUserRoleAction::class);

		//Page permettant d'obtenir le role d'un usager
		$userGroup->get("/role", GetUserRoleAction::class);

		//Page permettant d'obtenir le role d'un usager
		$userGroup->get("/all-role", GetUserAllRoleAction::class);
		
		//page permettant de changer le mot de passe d'un usager
		$userGroup->patch("/change-pwd", ChangePasswordAction::class);
	});

	$group->group('/judge', function (RouteCollectorProxy $judgeGroup) {

		//Permet d'obtenir la liste des juges et des juges blacklister qui sont actifs.
		$judgeGroup->get("/all/{blacklisted}", GetJudgesAction::class);

		//Permet de valider le courriel du juge
		$judgeGroup->get("/{token}", GetUser::class);

		//Permet d'inscrire la partie utilisateur du juge
		$judgeGroup->post("/user", PostJudgeUserAction::class);

		//Permet d'inscrire la partie juge du juge
		$judgeGroup->post("/judge", PostJudgeActivateAction::class);

		//Permet d'envoyer les emails d'inscription aux juges
		$judgeGroup->post("/email", SendEmailAction::class);

		//Permet de mettre à jour les informations des juges.
		$judgeGroup->patch("/update-judge", PatchJudgeAction::class);

		$judgeGroup->delete("/{id}", DeleteJudgeAction::class);
	});

    /**
     * Routes en lien avec les administrateurs sur le site web.
     * @author Antoine Ouellette
     */
    $group->group('/administrators', function (RouteCollectorProxy $administratorsGroup) {
        // Permet d'obtenir la liste de tous les administrateurs.
        $administratorsGroup->get("/all", GetAllAdministratorsAction::class);

        // Permet de créer un nouvel administrateur.
        $administratorsGroup->post("", PostAdministratorAction::class);

        // Permet de modifier un administrateur.
        // $administratorsGroup->put("", DeleteAdministratorAction::class);

        // Permet de supprimer une liste d'administrateurs par leurs ids.
        $administratorsGroup->delete("", DeleteAdministratorsByIdsAction::class);
    });


	$group->group("/stand", function (RouteCollectorProxy $standGroup) {
		//Permet d'ajouter un temps d'evaluation a un stand
		$standGroup->post("/insert-time", InsertTimeStandAction::class);

		//Permet de savoir si il y a des conflits entre un stand et un juge (retourne true si aucun n'est trouver)
		$standGroup->post("/conflits", ConflictStandAction::class);

		//Permet d'avoir une liste des questions de survey
		$standGroup->get("/get-survey", GetStandSurveyAction::class);
	});

	$group->group('/evaluation', function (RouteCollectorProxy $evaluationGroup) {

		//Permet d'obtenir le score d'un survey
		$evaluationGroup->get("/score/{surveyId}", GetSurveyScoreAction::class);

		//Permet d'obtenir tous les surveys pour un juge
		$evaluationGroup->get("/judge/{uuid}", GetAllSurveyJudgeAction::class);

		//Permet de fermer définitivement un survey
		$evaluationGroup->get("/close/{surveyId}", CloseSurveyAction::class);

		//Permet d'envoyer les courriels à tous les juges
		$evaluationGroup->get("/send", SendAllSurveyJudgeAction::class);

		//Permet d'envoyer un courriel à un seul juge
		$evaluationGroup->post("/sendIndividually", SendAllSurveyJudgeIndividuallyAction::class);

		//Permet d'obtenir le commentaire dans la bdd
		$evaluationGroup->post("/comment", SetCommentResultAction::class);

		//Section dédié aux questions
		$evaluationGroup->group("/question", function (RouteCollectorProxy $surveyQuestion) {

			//Section dedié aux résultats des questions
			$surveyQuestion->group("/result", function (RouteCollectorProxy $surveyQuestionResult) {

				//Permet d'obtenir le résultat d'une question dans la bdd
				$surveyQuestionResult->post("", SetQuestionResultAction::class);
			});
		});

		// Route pour mettre à jour l'état d'exclusion d'une note global d'un juge dans la calcul de la note finale de l'équipe
		$evaluationGroup->patch('/update-score-exclusion/{judge_id}', PatchGlobalScoreAction::class);

		// Route pour obtenir l'état actuel d'exclusion des scores pour initialiser les checkboxes
		$evaluationGroup->get('/get-global_score_removed', GetScoreExclusionsAction::class);
	});

	$group->group('/informations', function (RouteCollectorProxy $infosGroup) {
		//Permet de valider de courriel
		$infosGroup->get("", GetInformationsAction::class);
		$infosGroup->post("", CreateInformationBlockAction::class);
		$infosGroup->patch("", UpdateInformationBlockAction::class);
		$infosGroup->patch("/order", UpdateOrderInformationBlockAction::class);
		$infosGroup->delete("/{id}", DeleteInformationBlockAction::class);
	});

	//permet d'obtenir la liste des resultats
	$group->get("/resultat", GetResultatAction::class);
	//permet d'envoyer un mail contenant le resultat de l'equipe a la personne ressource
	$group->post("/envoi-resultat", GetSendAction::class);
	//permet de supprimer le résultat d'une évaluation associé d'un juge
	$group->delete("/supprimer-resultat", DeleteResultJudge::class);

	$group->group('/juge-stand', function (RouteCollectorProxy $infosGroup) {
		//permet d'obtenir la liste des stands
		$infosGroup->get("/stand", GetStandAction::class);
		//permet d'obtenir la liste des juges
		$infosGroup->get("/juge", GetJudgeStandAction::class);
		
		//permet de push une evaluation dans la bd 
		$infosGroup->post("/evaluation", PostEvaluationAction::class);
		// Permet d'ajouter une nouvelle plage horaire
		$infosGroup->post("/add-time-slot", AddTimeSlotActions::class);
		
		//permet d'enregistrer les modifications d'une évaluation dans la bd
		$infosGroup->patch("/evaluation", PatchEvaluationAction::class);
		//permet de supprimer une évaluation de la bd
		$infosGroup->delete("/evaluation/{id}", DeleteEvaluationAction::class);
		// permet de supprimer une plage horaire
		$infosGroup->delete("/delete-time-slot", DeleteTimeSlotActions::class);

		//permet d'obtenir les plages horaires
		$infosGroup->get("/get-time-slots", GetTimeSlotsAction::class);
		//permet d'enregistrer les plages horaires
		$infosGroup->put("/update-time-slots", SaveTimeSlotsActions::class);


	});

	$group->group('/gestion-equipes', function (RouteCollectorProxy $teamsListGroup) {

		//Permet d'obtenir les membres des équipes
		$teamsListGroup->get("", GetTeamsMembers::class);

		//Permet d'obtenir les équipes
		$teamsListGroup->get("/teams-infos", GetTeamsInfos::class);

		//Permet d'obtenir les informations d'une équipe selon son id
		$teamsListGroup->get("/team-info/{id:[0-9]+}", GetTeamInfoById::class);
		
		//Permet de recevoir le nom et l'id des catégories
		$teamsListGroup->get("/categories", GetCategories::class);


		

		//Permet d'ajouter un membre à une équipe
		$teamsListGroup->post("/teams-members", AddTeamMember::class);

		//Permet de mettre à jour les équipes
		$teamsListGroup->patch("/teams-infos", UpdateTeamsInfos::class);

		//Permet de mettre à jour les membres des équipes
		$teamsListGroup->patch("/teams-members", UpdateTeamsMembers::class);

		//Permet de supprimer les membres des équipes
		$teamsListGroup->delete("/teams-members", DeleteTeamsMembers::class);

		//Permet de supprimer les équipes
		$teamsListGroup->delete("/teams-infos", DeleteTeamsInfos::class);

		//Permet de mettre à jour les numéros de stand
		$teamsListGroup->patch('/teams-numbers', UpdateTeamsNumbers::class);
	});

	
	$group->group('/evaluationGrid', function (RouteCollectorProxy $evaluationGridGroup) {
		// [GET] Permet d'obtenir les grilles d'évaluation
		$evaluationGridGroup->get("", GetEvaluationGridAction::class);
		// [GET] Permet d'obtenir les grilles d'évaluation selon l'id
		$evaluationGridGroup->get("/{id}", GetEvaluationGridByIdAction::class);
		// [POST] Permet d'ajouter une grille d'évaluation
		$evaluationGridGroup->post("", CreateEvaluationGridAction::class);
		// [PATCH] Permet de modifier une grille d'évaluation
		$evaluationGridGroup->patch("", UpdateEvaluationGridAction::class);
		// [DELETE] Permet de supprimer une grille d'évaluation
		$evaluationGridGroup->delete("/{id}", DeleteEvaluationGridAction::class);
	});
	######################################################## FORMULAIRE ÉVALUATIONS ###########################################################################
	/**
	 * Section d'opération CRUD pour les codes de vérification des mots de passe oublié
	 * @author TMaxime Demers Boucher
	 */
	$group->group('/verificationCode', function (RouteCollectorProxy $verificationCode) {
		// [POST] Permet de géné le code de vérification
		$verificationCode->post("/generate", VerificationCodeGenerateAction::class);
		// [GET] Permet d'obtenir de vérifier si le temps activation est en mesure
		$verificationCode->get("/validate/{code}", VerificationCodeValidAction::class);
		// [DELETE] Permet de supprimer un code de validation
		$verificationCode->delete("/delete",VerificationCodeDeleteAction::class);
	});
});