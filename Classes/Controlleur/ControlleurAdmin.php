<?php
namespace Controlleur;

use Auth\DBRestaurant;
use Auth\DBCritique;
use Auth\DBAuth;
use form\Form;
use form\type\Link;
use form\type\Select;
use form\type\Submit;
use form\type\Hidden;
use form\type\Text;

class ControlleurAdmin extends Controlleur
{
   
    public function view()
    {
        
            
            $utilisateurs = DBAuth::getUserByRole(2); // 2 = visiteur enregistré
            $critiques = array();
            foreach($utilisateurs as $utilisateur){
                $id = $utilisateur['id'];
                $userCritiques = DBCritique::getCritiqueByUser($id);
                $critiques = array_merge($critiques, $userCritiques);
            }
            
            error_log("Les Critiques ".print_r($critiques, true));
            error_log("Les Utilisateurs ".print_r($utilisateurs, true));
            if(!isset($_SESSION['id_role'])){
                $this->redirect("ControlleurLogin", "view");
            }elseif(isset($_SESSION['id_role']) && $_SESSION['id_role'] == 1){
                $this->render("admin.php", [
                    "critiques" => $critiques,
                    "utilisateurs" => $utilisateurs,
                    "formDeconnexion" => $this->getFormDeconnexion(),
            ]);
        }else{
            $this->redirect("ControlleurLogin", "view");
        }
    }
        

    public function submit()
    {
        $auth = new DBAuth();
        $auth->logout();
        $this->redirect("ControlleurHome", "view");
    }

    public function getFormDeconnexion()
    {
        $form = new Form("/?controller=ControlleurHome&action=submit", Form::GET, "home_form");
        $form->setController("ControlleurHome", "submit");
        $form->addInput(new Submit("Deconnexion", true, "", ""));
        return $form;
    }

    public function getFormDeleteAdmin($id){ 
        $forms = new Form("/?controller=ControlleurAdmin&action=submitDelete", Form::POST, "admin_form");
        $forms->setController("ControlleurAdmin", "submitDelete");
        $forms->addInput(new Hidden($id,true, "user_id", "user_id")); 
        $forms->addInput(new Submit("Supprimer", true, "", "", ""));
        
        return $forms;
    }
    
    public function getFormLink($idUser){
        $form = new Form("/?controller=ControlleurAdmin&action=view", Form::GET, "get_form");
        $form->addInput(new Link("/?controller=ControlleurCritique&action=view&id={$idUser}", "Visualiser"));
        return $form;
    }

    public function submitDelete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
            $id = intval($_POST['user_id']); 
            $this->deleteUserFromDB($id);
            header("Location: /?controller=ControlleurAdmin&action=usersList"); 
            exit();
        } else {
            echo "Requête invalide.";
        }
    }

    private function deleteUserFromDB($id) {
        $db = Database::getInstance()->getConnection();
        
        try {
            $queryAvis = $db->prepare("DELETE FROM avis WHERE user_id = :id");
            $queryAvis->bindParam(":id", $id, PDO::PARAM_INT);
            $queryAvis->execute();
            $db->commit();
            echo "Les avis de cette utilisateur est supprimés avec succès."; 
        } catch (Exception $e) {
            $db->rollBack();
            echo "Erreur : " . $e->getMessage();
        }
    }
    
  

}