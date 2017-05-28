<?php
use Ajax\semantic\html\base\constants\State;
use Ajax\semantic\components\validation\Rule;


class AccueilController extends ControllerBase
{


    public function connectAction()
    {
        if($this->session->has("user")){
            $username = $this->session->get("user");
            return $this->dispatcher->forward(
                [
                    "controller" => "Tmp",
                    "action" => "index",
                ]
            );
        }else{
            $this->secondaryMenu($this->controller, $this->action);
            $this->tools($this->controller, $this->action);

            $semantic = $this->semantic;
            $semantic->setLanguage("fr");
            $form = $semantic->htmlForm("frm2");
            $form->addErrorMessage();
            $form->addHeader("Connexion", 3);
            $form->addInput("email", "Adresse e-mail","","admin@vhosts.net")->addRule("empty", "Veuillez remplir le champ adresse...")->addRule(["empty", "{name} est obligatoire"])->getField()->labeledToCorner("asterisk", "right");
            $form->addInput("password", "Mot de passe","password","0000","Mot de passe")->addRule("empty", "Veuillez remplir le champ mot de passe ...")->addRule(["empty", "{name} est obligatoire"])->getField()->labeledToCorner("asterisk", "right");
            $icon = $semantic->htmlIcon("", "checkmark");

            $form->addButton("submit", "Valider", "ui green button");
            $form->addButton("cancel", "Annuler", "ui red button")->postFormOnClick("Accueil/connect", "frmDelete", "#tab");
            $form->submitOnClick("submit", "Accueil/login", "#content-container");
            $this->jquery->compile($this->view);
        }
    }

    private function _registerSession($user)
    {
        $this->session->set(
            "auth",
            [
                "id" => $user->id,
                "name" => $user->name,
            ]
        );
    }

    public function loginAction()
    {
        $this->secondaryMenu($this->controller, $this->action);
        $this->tools($this->controller, $this->action);

        if ($this->request->isPost()) {
            // récupére les données dans le formulaire
            $mail = $this->request->getPost("email");
            $pwd = $this->request->getPost("password");

            //Cherche l'utilisateur dans la BDD
            $user = User::findFirst(
                [
                    "email = :email: AND password = :password: ",
                    "bind" => [
                        "email" => $mail,
                        "password" => $pwd,
                    ]
                ]
            );

            if ($user !== false) {
                //$this->_registerSession($user);
                $this->session->set("user",$user);

                //Envois à la page d'acceuil si la connexion est réussis !
                return $this->dispatcher->forward(
                    [
                        "controller" => "Tmp",
                        "action" => "index",
                    ]
                );
            }else{
                $this->flash->error(
                    "Mauvais mot de passe ou Email ...."
                );
            }
        }
        $this->jquery->compile($this->view);
    }

}