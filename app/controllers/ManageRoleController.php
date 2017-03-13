<?php

use Ajax\semantic\html\elements\HtmlButton;
use Ajax\Semantic;
use Phalcon\Forms\Element\Submit;

class ManageRoleController extends ControllerBase
{

    public function indexAction()
    {
    	$this->secondaryMenu($this->controller,$this->action);
    	$this->tools($this->controller,$this->action);
    	$semantic=$this->semantic;
    	$semantic->htmlButton("addButton","Ajouter un rôle","button green")->getOnClick("ManageRole/addRole","#divRole");   	 
    	$roles=Role::find();
    	$users=User::find();
    
		$table=$this->semantic->htmlTable("dd",0,3);
		$table->setHeaderValues(["Rôles","Nombre d'utilisateurs",""]);
		$nbrUser = 0;
		foreach ($roles as $Role)
		{
			foreach ($users as $User){
				if ($Role->getId() == $User->getIdrole()){
					$nbrUser = $nbrUser +1;	
				}}
				if ($nbrUser == 0)
				{$p="";}
				else if ($nbrUser == 1)
				{$p= $semantic->htmlLabel("",$nbrUser . " Utilisateur","user")->setColor("green");}
				else
				{$p= $semantic->htmlLabel("",$nbrUser . " Utilisateurs","user")->setColor("green");};
			$table->addRow([$i=$Role->getName(),
			$p,
							$semantic->htmlButton("editButton".$i."","Modifier","small green basic")->asIcon("edit")->getOnClick("ManageRole/editRole/$i","#divRole").									
							$semantic->htmlButton("deleteButton".$i."","Supprimer","small red")->asIcon("remove")->getOnClick("ManageRole/deleteRole/$i","#divRole")]);
			$nbrUser = 0;
		}
		$this->jquery->compile($this->view);
    }
    
    public function editRoleAction($a=NULL){		
    		$semantic=$this->semantic;	
    		
			$roleEdit=Role::findFirst(["name='$a'"]);
				
			$form=$semantic->htmlForm("frmEdit");
			$form->setValidationParams(["on"=>"blur","inline"=>true]);
			$form->addInput("id","","hidden",$roleEdit->getId());
			$form->addInput("name","Nom","text",$a)->addRule(["empty","Ce champ est obligatoire"]);
			
			$form->addButton("submit","Modifier le rôle")->asSubmit()->setColor("green");
			$form->submitOn("click","submit","ManageRole/majRole","#result");
			$form->addErrorMessage();
			$this->jquery->compile($this->view);
    }

    public function majRoleAction(){   
    	$nom=$_POST["name"];
    	$id=$_POST["id"];
    	
    	$roleEdit=Role::findFirst(["id=$id"]);

    	$roleEdit->setName($nom);
    	$roleEdit->update();
    		
    	$this->flash->message("success","Le rôle a bien été modifié.");
    	$this->jquery->get($this->controller,"#refresh");
    	 
    	echo $this->jquery->compile();
    	//$this->jquery->compile($this->view);
    }
    
    public function addRoleAction(){
	    	$semantic=$this->semantic;
	    		    	
	    	$form=$semantic->htmlForm("frmAdd");
	    	$form->setValidationParams(["on"=>"blur","inline"=>true]);
	    	$form->addInput("nameRole","Nom","text")->addRule(["empty","Ce champ est obligatoire"]);
	    	
	    	$form->addButton("submit","Ajouter le rôle","button green")->asSubmit();
	    	$form->submitOn("click","submit","ManageRole/newRole","#result");
	    	$form->addErrorMessage();
	    	$this->jquery->compile($this->view);
    }
    
    public function newRoleAction(){
	    	$nom=$_POST["nameRole"];

	    	
	    	$newRole = new Role();
	    	$newRole->setName($nom);
	    	$newRole->create();
	    	
	    	$this->flash->message("success","Le rôle a bien été ajouté.");
	    	$this->jquery->get($this->controller,"#refresh");
	    	
	    	echo $this->jquery->compile();
	    	//$this->jquery->compile($this->view);
    }
    
    public function deleteRoleAction($a=NULL){
    		$semantic=$this->semantic;
    	
	    	$role=Role::findFirst("name='$a'");
	    	
	    	$form=$semantic->htmlForm("frmDelete");
	    	$form->setValidationParams(["on"=>"blur","inline"=>true]);
	    	$form->addHeader("Voulez-vous vraiment supprimer le rôle : ". $role->getName()."?",3);
	    	$form->addInput("id",NULL,"hidden",$role->getId());
	    	$form->addInput("name","Entrez le nom du rôle pour confirmer la suppression","text")->addRule(["empty","Ce champ est obligatoire"]);
	    		    	
	    	$this->view->setVars(["element"=>$role]);
	    	
	    	$form->addButton("submit","Supprimer le rôle")->asSubmit()->setColor("red");
	    	$form->submitOn("click","submit","manageRole/confirmDelete","#result");
	    	$form->addErrorMessage();
	    	$this->jquery->compile($this->view);
    }
    
    public function confirmDeleteAction(){
    	$deleteRole = Role::findFirst($_POST['id']);
    	$idrole=$deleteRole->getId();
    	
    	$roleRecup = Role::findFirst(["id != '$idrole'",]);
    	$idroleRecup=$roleRecup->getId();

    	if($deleteRole->getName() == $_POST['name']){
    		
    		$users=User::find(["idrole = '$idrole'",]);
    		
    		foreach ($users as $userEdit)
    		{
    			$userEdit->setIdrole($idroleRecup);
    			$userEdit->update();
    		}
    		
    		$deleteRole->delete();
    		
    		$this->flash->message("error","Le rôle a bien été supprimé.");
    		$this->jquery->get($this->controller,"#refresh");
    		
    		echo $this->jquery->compile();
    		//$this->jquery->compile($this->view);
    	}
    }

}

