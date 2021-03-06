<?php

use Ajax\semantic\html\elements\HtmlButtonGroups;
use Ajax\semantic\html\elements\HtmlButton;
class TmpController extends ControllerBase{
	public function indexAction(){
		$this->loadMenus();
		$semantic=$this->semantic;
		$title=$semantic->htmlHeader("header1",2);
		
		$title->asTitle("Liste des services Licornien","Une licorne se chargera de vous servir selon vos besoins.");
		$this->view->setVar("title1", $title);
		
		$machines = Host::find();
		$count = 0;
		foreach ($machines as $machine){
			$count += 1;
		}
		
		$server = Server::find();
		$count2 = 0;
		foreach ($server as $servers){
			$count2 += 1;
		}
		
		$virtualhostt = Virtualhost::find();
		$count3 = 0;
		foreach ($virtualhostt as $virtualhostts){
			$count3 += 1;
		}
		
		$grid=$semantic->htmlGrid("grid");
		$grid->setStretched()->setCelled(true);
		$grid->addRow(2)->setValues([$this->createBts("vincent",["Administration d'un serveur "=>"/serveur/hosts"],""),"Il y a actuellement $count machines avec $count2 serveur(s) et $count3 virtualhost(s). "]);
		$grid->addRow(2)->setValues([$this->createBts("yann",["Configuration d'un utilisateur(s)"=>"Config/index"],""),""]);
		$grid->addRow(2)->setValues([$this->createBts("thomas",["Configuration d'un virtualhost "=>"VirtualHosts/config"],""),"Vous pouvez modifier la configuration d'un virtualhost avec toute ses propriétés."]);
		$grid->addRow(2)->setValues([$this->createBts("romain",["Information sur le compte" =>"InfoCompte/ModifInfo"],""),"Vous pouvez Modifier les paramètre du compte administrateur."]);
		$grid->addRow(1)->setValues([$this->createBts("ed",[" Gest. rôles "=>"ManageRole/index","  Gest. utilisateurs"=>"ManageUsers/index"],"")]);
		$grid->addRow(1)->setValues([$this->createBts("anthony",["  S'enregistrer"=>"Sign/Signin","Liste hosts & virtualhost "=>" Listhostvirtual/listhv  ","Liste vh/server "=>"ListVirtualhostParServ/listServer"],"")]);
		$grid->addRow(1)->setValues([$this->createBts("aboudou",["Gest. types servers"=>"TypeServers/index","Gest. types propriétés"=>"TypeProperty/index","Gest. propriétés"=>"Property/index"],"")]);





		$grid->addRow(2)->setValues([$this->createBts("thomas",["Acces a mon portefeuille "=>"ManagePortefeuille"],""),"Accédez à votre portefeuille."]);

		$this->jquery->getOnClick(".clickable", "","#content-container",["attr"=>"data-ajax"]);
		$this->jquery->compile($this->view);
	}

	private function createBts($name,$actions,$color=""){
		$bts=new HtmlButtonGroups("bg-".$name);
		foreach ($actions as $k=>$action){
				$bt=new HtmlButton($k."-".$action);
				$bt->setValue($k);
				$bt->setProperty("data-ajax", $action);
				$bt->addToProperty("class", "clickable");
				$bt->setColor($color);
				$bts->addElement($bt);
		}

		return $bts;

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
		$list=$this->semantic->htmlList("lst-hosts");
		foreach ($hosts as $host){
			$item=$list->addItem(["icon"=>"add","header"=>$host->getName(),"description"=>$host->getIpv4()]);
			$item->addToProperty("data-ajax", $host->getId());
		}
		$list->setHorizontal()->setSelection();
		$this->jquery->getOnClick("#lst-hosts .item","Tmp/servers","#servers",["attr"=>"data-ajax"]);
		$this->jquery->compile($this->view);

	}

	public function serversAction($idHost=NULL){
		$servers=Server::find("idHost=".$idHost);
		$list=$this->semantic->htmlList("lst-hosts");
		foreach ($servers as $server){
			$item=$list->addItem(["icon"=>"delete","header"=>$server->getName()]);
		}
		$list->setInverted()->setDivided()->setRelaxed();
		echo $list->compile();
	}


	public function hostAction(){
		$this->loadMenus();
		$host=Host::findFirst();
		echo $host->getName();
		echo $host->getUser()->getLogin();
		$servers=$host->getServers();
		echo "<ul>";
		foreach ($servers as $server){
			echo "<li>".$server->getName()."</li>";
		}
		echo "</ul>";
	}
}

