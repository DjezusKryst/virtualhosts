<?php

use Ajax\semantic\html\elements\HtmlButton;
use Ajax\Semantic;
use Phalcon\Forms\Element\Submit;
use Ajax\service\JArray;

class ManagePortefeuilleController extends ControllerBase
{

    public function indexAction()
    {
    	$this->secondaryMenu($this->controller,$this->action);
    	$this->tools($this->controller,$this->action);
    	$semantic=$this->semantic;

        $users=User::find();

        $table=$this->semantic->htmlTable("pf",0,3);
        foreach ($users as $user)
        {
            if($user->getIdPortefeuille() == 1){


            $i= $user->getId();

            $count = Count(Virtualhost::find("idUser =" . $i));
            /*foreach($userVH as $VH){
                $count++;
            }*/

            $Idrole= $user->getIdrole();
            $roleUser=Role::findFirst("id='$Idrole'");
            $p= $semantic->htmlLabel("",$roleUser->getName(),"user")->setColor("green");

            $table->addRow([$user->getFirstname()." ".$user->getName(),
                $p,
                    "Nombre d'hôtes : " . $count . "  " .
                $semantic->htmlButton("getServers" . $i,"Modifier","small green basic")->asIcon("edit")->getOnClick("Serveur/hosts/$i","#divUser"),
                ]);
            }
        }
        $this->jquery->compile($this->view);
    }
}
    
