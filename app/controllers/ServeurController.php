<?php
use Phalcon\Mvc\View;
use Ajax\semantic\html\collections\form\HtmlFormTextarea;
use Ajax\semantic\html\modules\checkbox\HtmlCheckbox;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\html\elements\HtmlInput;
use Ajax\Semantic;
use Ajax\semantic\html\elements\HtmlIcon;
use Phalcon\Db;
use Phalcon\Db\Adapter\Pdo;
use Ajax\service\JArray;
class ServeurController extends ControllerBase{

	
	public function indexAction(){
	
	}

	
	public function diversAction(){
		$this->loadMenus();
		$dd=$this->semantic->htmlDropdown("dd");
		$dd->asSelect("vh")->asSearch("vh");
		$virtualhosts=Virtualhost::find();
		$dd->fromDatabaseObjects($virtualhosts, function($vh){
			return $vh->getName();
		});
		
		
			$table=$this->semantic->htmlTable("table", 0, 2);
			$table->setHeaderValues(["id","Nom"]);
			$sTypes=Stype::find();
			foreach ($sTypes as $sType){
				$table->addRow([$sType->getId(),$sType->getName()]);
			}
	}
	
	public function hostsAction($user=NULL){
		$this->loadMenus();
		$hosts=Host::find();
		
		
		$semantic=$this->semantic;
		
		$title=$semantic->htmlHeader("header1",2);
		$title->asTitle("Liste des machines","Séléctionner une machine pour visualiser la liste des serveurs installés");
		$this->view->setVar("title1", $title);
		
		
		$list=$this->semantic->htmlList("lst-hosts");
		
	
				foreach ($hosts as $host){
					$item=$list->addItem(["icon"=>"add","header"=>$host->getName(),"description"=>$host->getIpv4()]);
					$item->addToProperty("data-ajax", $host->getId());
				}
				$list->setHorizontal();
				
				$list->setSelection();

		$this->jquery->getOnClick("#lst-hosts .item","Serveur/servers","#servers",["attr"=>"data-ajax"]);
		$this->jquery->compile($this->view);
		
	}
	
	public function serversAction($idHost=NULL){
		
		$this->session->set("host", Host::findFirst($idHost));
		$virtualhosts=Virtualhost::find("$idHost = ".$idHost);
		$servers=Server::find("idHost=".$idHost);
		$list=$this->semantic->htmlList("lst-hosts");
		
		
		$semantic=$this->semantic;
		
		
		
		$table=$semantic->htmlTable('table4',0,7);
		$table->setHeaderValues([" ","Nom du Serveur","Configuration","Afficher virtualhost(s)","Supprimer","Nombre Virtualhost(s)"]);
		$i=0;
				
		foreach ($servers as $server){
			
			$btnConfig = $semantic->htmlButton("btnConfig-".$i,"Configurer","small green basic")->asIcon("edit")->getOnClick("Serveur/virtual/".$server->getId(),"#divAction");
			$id = $server->getId();
			
			
			
			
			$nbrvirtual = count(Virtualhost::find("idServer = ".$id));
			
			
			if ($nbrvirtual == 0 )
			{
				$btnDelete = $semantic->htmlButton("btnDelete-".$i,"Supprimer","small red")->asIcon("remove")->getOnClick("Serveur/vDelete/".$server->getId(),"#divAction");
				$table->addRow([" ",$server->getName(),
						$server->getConfig(),$btnConfig,$btnDelete,$nbrvirtual]);
				
				
			}
		
			else {
				$btngrey = $semantic->htmlButton("btnDelete-".$i,"Supprimer","small grey")->asIcon("remove");
				
						$table->addRow([" ",$server->getName(),
						$server->getConfig(),$btnConfig,$btngrey,$nbrvirtual]);
				
			}
			
				
			$table->setDefinition();
			$i++;
				
		}
		
		
	
		echo $table;
		
		$test=$semantic->htmlButton("ajouter","Ajouter un serveur","black")->getOnClick("Serveur/vUpdate","#divAction")->setNegative();
		echo $test;
		$this->jquery->exec("$('#lst-hosts .item').removeClass('active');",true);
		$this->jquery->exec("$('[data-ajax=".$idHost."]').addClass('active');",true);
		$list->setInverted()->setDivided()->setRelaxed();
	
		
		
		echo $this->jquery->compile($this->view);
	}
	
	/* ajout serveur */
	
	public function vUpdateAction(){
		$this->secondaryMenu($this->controller,$this->action);
		$this->tools($this->controller,$this->action);
		 
		$host = Host::find();
		
		$stypes = Stype::find();
		$itemsStypes = JArray::modelArray($stypes,"getId","getName");

		$hosts = Host::find();
		$itemshost = JArray::modelArray($hosts,"getId","getName");
		
		//$this->session->set("host", $host->getName());
		
		$semantic=$this->semantic;
		
		$btnCancel = $semantic->htmlButton("btnCancel","Annuler","red");
		$btnCancel->getOnClick("Serveur/index","#index");
		
	
		$btnCancel = $semantic->htmlButton("btnCancel","Annuler","red");
		$btnCancel->getOnClick("Servers","#divAction");
	
		
		$form=$semantic->htmlForm("frmUpdate");
		$form->addInput("name")->getField()->labeledToCorner("asterisk","right");
		
		
		$input2=$semantic->htmlInput("Configuration...");
		$form->addInput("config")->getField()->labeledToCorner("asterisk","right");
			
		
	
		$form->addDropdown("stype",$itemsStypes,"Type Serveurs : * ","Selectionner un type de serveur ...",false);
		$form->addDropdown("host",$itemshost,"Host : *","Selectionner host ...",false);
	
	
		$form->addButton("submit", "Valider","ui green button")->postFormOnClick("Serveur/vAddSubmit", "frmUpdate","#divAction");
		$form->addButton("cancel", "Annuler","ui red button")->postFormOnClick("Serveur/hosts", "frmDelete","#tab");
		
		$host=$this->session->get("host");
		
		$this->view->setVar("serverName",$host->getName());
		
		$this->jquery->compile($this->view);
		
		
	}
	

	public function vAddSubmitAction(){
		
		if(!empty($_POST['name'] && $_POST['config'] && $_POST['stype'] && $_POST['host'])){
			$Server = new Server();
	
			$idhost = Host::findFirst("id = '".$_POST['host']."'");
			$idstype = Stype::findFirst("id = '".$_POST['stype']."'");
			
			$Server->setIdStype($idstype->getId());
			$Server->setIdHost($idhost->getId());
			$Server->save(
					$this->request->getPost(),
					[
							"name",
							"config",
							
					
					]
					);
	
			
			$this->jquery->get("Serveur/hosts/","#tab");
			$this->flash->message("success", "Le serveur a été inseré avec succès");
			//$this->jquery->get("Serveur","#refresh");	
			 
		}else{
			$this->flash->message("error", "Veuillez remplir tous les champs");
			
		}
		

		
		echo $this->jquery->compile();
		
	}
	
	
	
	/* supprimer serveurs */
	public function vDeleteAction($id){
		$this->secondaryMenu($this->controller,$this->action);
		$this->tools($this->controller,$this->action);
		 
		$Server = Server::findFirst($id);
		 
		$semantic=$this->semantic;
		
		
		$btnCancel = $semantic->htmlButton("btnCancel","Annuler","red");
		$btnCancel->getOnClick("TypeServers","#divAction");
		
		$form=$semantic->htmlForm("frmDelete");
		 
		$form->addHeader("Voulez-vous vraiment supprimer le serveur : ". $Server->getName()."?",3);
		$form->addInput("id",NULL,"hidden",$Server->getId());
		$form->addInput("name","Nom","text",NULL,"Confirmer le nom du type de serveur");
		
		$form->addButton("submit", "Supprimer","ui green button")->postFormOnClick("Serveur/confirmDelete", "frmDelete","#divAction");
		
		
		
		$form->addButton("cancel", "Annuler","ui red button")->postFormOnClick("Serveur/hosts", "frmDelete","#tab");

		
		$this->view->setVars(["element"=>$Server]);
		
		$this->jquery->compile($this->view);
		
	}
	public function confirmDeleteAction(){
		$Server= Server::findFirst($_POST['id']);
		 
		if($Server->getName() == $_POST['name']){
			$Server->delete();
	
			
			$this->jquery->get("Serveur/hosts/","#tab");
			
			$this->flash->message("success","Le serveur a été supprimé avec succès");
	
		}else{
	
			
				$this->jquery->get("Serveur/hosts/","#tab");
		}
		 
		echo $this->jquery->compile();
		
	}
	
	
	
	
	/* virtualhost */
	
	
	
	/* virtualhost du serveur */
	
	public function virtualAction($idServer=NULL,$idhost=NULL){
	
		$virtualhosts=Virtualhost::find("idServer=".$idServer."");
		$this->session->set("virtualhost", Virtualhost::findFirst($idServer));
		
		
		
		if($virtualhosts->count() == 0 ){
			$semantic=$this->semantic;
			
			$ajoutervirtual=$semantic->htmlButton("ajoutervirtual","Ajouter un virtualhost","black")->getOnClick("Serveur/vUpdatevirtual","#divAction")->setNegative();
			
		}
		
		else {
			
			$list=$this->semantic->htmlList("virtual");
			
			$semantic=$this->semantic;
			
		
			/*
			 * 
			 * 
			 * 
			 */
			$table=$semantic->htmlTable('table4',0,7);
			$table->setHeaderValues([" ","Nom du Virtualhosts","Configuration","Modifier Virtualhost(s)","Renommer","Supprimer"]);
			$i=0;
			
			
			
			foreach ($virtualhosts as $virtualhost){
				$btnConfigvirtual= $semantic->htmlButton("btnConfigvirtual-".$i,"Configurer","small green basic")->asIcon("edit")->getOnClick("Serveur/vChangevirtual/".$virtualhost->getId(),"#divAction");
					
				$ajoutervirtual=$semantic->htmlButton("ajoutervirtual","Ajouter un virtualhost","black")->getOnClick("Serveur/vUpdatevirtual","#divAction")->setNegative();
					
				//$nbrvirtual = count(Virtualhost::find("idServer = ".$id));
				
				$btnmodif = $semantic->htmlButton("btnmodif-".$i,"Configurer","small blue basic")->asIcon("edit")->getOnClick("virtualHosts/config/".$virtualhost->getId(),"#content-container");
					
				
				$btnDelete = $semantic->htmlButton("btnDeleteVirtual-".$i,"Supprimer","small red")->asIcon("remove")->getOnClick("Serveur/vDeletevirtual/".$virtualhost->getId(),"#divAction");
					
				$table->addRow([" ",$virtualhost->getName(),
						$virtualhost->getConfig(),$btnmodif,$btnConfigvirtual,$btnDelete]);
			
				$table->setDefinition();
				$i++;
		
			
			}
			$semantic=$this->semantic;
			
			$title=$semantic->htmlHeader("header1",2);
			
			
			
			$title->asTitle("Liste des Virtualhost(s) pour le serveur : ","Séléctionner un virtualhost pour le supprimer et/ou le modifier");
			$this->view->setVar("title1", $title);
			
			
			echo $table;
			echo "<br/> <br/>";
			$this->jquery->exec("$('#divAction .item').removeClass('active');",true);
			$this->jquery->exec("$('[data-ajax=".$idhost."]').addClass('active');",true);
			$list->setInverted()->setDivided()->setRelaxed();
			
		}
	
		echo $this->jquery->compile($this->view);
	}
	

	/* supprimer virtualhost */
	public function vDeletevirtualAction($id){
		$this->secondaryMenu($this->controller,$this->action);
		$this->tools($this->controller,$this->action);
			
		$Virtualhost = Virtualhost::findFirst($id);
			
		$semantic=$this->semantic;
	
	
		$btnCancel = $semantic->htmlButton("btnCancel","Annuler","red");
		$btnCancel->getOnClick("TypeServers","#divAction");
	
		$form=$semantic->htmlForm("frmDelete");
			
		$form->addHeader("Voulez-vous vraiment supprimer le virtualhost : ". $Virtualhost->getName()."?",3);
		$form->addInput("id",NULL,"hidden",$Virtualhost->getId());
		$form->addInput("name","Nom","text",NULL,"Confirmer le nom du virtualhost");
	
		$form->addButton("submit", "Supprimer","ui green button")->postFormOnClick("Serveur/confirmDeletevirtual", "frmDelete","#divAction");


		$form->addButton("cancel", "Annuler","ui red button")->postFormOnClick("Serveur/hosts", "frmDelete","#tab");
		
		
		$this->view->setVars(["element"=>$Virtualhost]);
	
		$this->jquery->compile($this->view);
	
	}
	public function confirmDeletevirtualAction(){
		$Virtualhost= Virtualhost::findFirst($_POST['id']);
			
		if($Virtualhost->getName() == $_POST['name']){
			$Virtualhost->delete();
	
			$this->flash->message("success","Le virtualhost a été supprimé avec succès");
			$this->jquery->get("Serveur/hosts/","#tab");
			
	
		}else{
	
			$this->flash->message("error","Le virtualhost n'a pas été supprimé : le nom ne correspond pas ! ");
			$this->jquery->get("Serveur/index","#test");
		}
			
		echo $this->jquery->compile();
	}
	
	
	/* ajout les virtualhost du serveur */
	public function vUpdatevirtualAction(){
		$this->secondaryMenu($this->controller,$this->action);
		$this->tools($this->controller,$this->action);
			
		$stypes = Stype::find();
		$servers = Server::find();
		
		$semantic=$this->semantic;
		
		$title=$semantic->htmlHeader("header1",2);
		$title->asTitle("Ajout du nouveau virtualhost :","Créer un nouveau virtualhost avec son nom et sa configuration");
		$this->view->setVar("title1", $title);
		
	
		
		 	
		$semantic=$this->semantic;
		
		$btnCancel = $semantic->htmlButton("btnCancel","Annuler","red");
		$btnCancel->getOnClick("Serveur/index","#index");
		
		
		$btnCancel = $semantic->htmlButton("btnCancel","Annuler","red");
		$btnCancel->getOnClick("Servers","#divAction");
		
		
		$form=$semantic->htmlForm("frmUpdate");
		$form->addInput("name")->getField()->labeledToCorner("asterisk","right");
		
		
		$input2=$semantic->htmlInput("Configuration...");
		$form->addInput("config")->getField()->labeledToCorner("asterisk","right");
		
		$items=Ajax\service\JArray::modelArray($servers,function($c){return $c->getId();},function($c){return $c->getName();});
		
		$form->addDropdown("server",$items,"Nom du serveur : * ","Selectionner un  serveur ...",false);
		
		
			
		$form->addButton("submit", "Valider","ui green button")->postFormOnClick("Serveur/vAddSubmitvirtual", "frmUpdate","#divAction");
		$form->addButton("cancel", "Annuler","ui red button")->postFormOnClick("Serveur/hosts", "frmDelete","#tab");
		
			
		$this->jquery->compile($this->view);
		
		
	}
	public function vAddSubmitvirtualAction(){
		if(!empty($_POST['name'] && $_POST['config'] && $_POST['server'])){
			$Virtualhost = new Virtualhost();
	
			$idserver = Server::findFirst($_POST['server']);
			
			$Virtualhost->setIdServer($idserver->getId());
			
			$Virtualhost->save(
					$this->request->getPost(),
					[
							"name",
							"config",
							"server"
								
					]
					);
	
		
			$this->jquery->get("Serveur/hosts/","#tab");
			
			$this->flash->message("success", "Le serveur a été inseré avec succès");
			
			
		}else{
			$this->flash->message("error", "Veuillez remplir tous les champs");
				
		}
	
	
	
		echo $this->jquery->compile();
	
	}
	
	/* modifier le virtualhost*/
	
	public function vChangevirtualAction($idvirtualhost){
		$this->secondaryMenu($this->controller,$this->action);
		$this->tools($this->controller,$this->action);
		
		$semantic=$this->semantic;
				 
		$virtualhosts = Virtualhost::findFirst($idvirtualhost);
	
		$title=$semantic->htmlHeader("header1",2);
		$title->asTitle("Modification du virtualhost","La Modification sera apporté au virtualhost :");
		$this->view->setVar("title1", $title);
		
		$hosts = Host::find();		
		$itemhost = JArray::modelArray($hosts,"getId","getName");
		
		$servers = Server::find();
		$itemservers = JArray::modelArray($servers,"getId","getName");
					 
	
		
		$btnCancel = $semantic->htmlButton("btnCancel","Annuler","red");
		$btnCancel->getOnClick($this->controller."/index","#index");
		 
		$form=$semantic->htmlForm("frmUpdate");
		$form->addInput("id",NULL,"hidden",$virtualhosts->getId());

		$form->addInput("name","Changer de Nom :")->setValue($virtualhosts->getName());	
		$form->addInput("config","Changer sa configuration :")->setValue($virtualhosts->getConfig());
		
		$form->addDropdown("host",$itemhost,"Nom du nouveau host :  ","Nouveau host...",false);

		$form->addDropdown("server",$itemservers,"Nom du serveur :  ","Selectionner un nom de serveur...",false);
		
		
		$form->addButton("submit", "Valider","ui positive button")->postFormOnClick($this->controller."/Ajouter", "frmUpdate","#divAction");
	
		$form->addButton("cancel", "Annuler","ui red button")->postFormOnClick("Serveur/hosts", "frmDelete","#tab");
		
		$this->jquery->compile($this->view);
		
	}
	
	
	/* modification du virtualhost */
	
	public function AjouterAction(){
		
		
		$idVH=$_POST["id"];
		$virtualhost= Virtualhost::findFirst("id='$idVH'");
		$server= Server::findFirst("name = '".$_POST['server']."'");
		
		$host= Host::findFirst("name = '".$_POST['host']."'");
		
			$virtualhost->setName($_POST["name"]);
			$virtualhost->setConfig($_POST["config"]);
			$server->setIdHost($host->getId());
			
			$virtualhost->setIdServer($server->getId());
		
			$virtualhost->save();
		echo $this->jquery->compile();
	
	}
	
	
}
