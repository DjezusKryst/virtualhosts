<?php
use Ajax\semantic\html\modules\checkbox\HtmlCheckbox;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\html\elements\HtmlInput;
use Ajax\Semantic;
use Ajax\semantic\html\elements\HtmlIcon;
use Phalcon\Db;
use Phalcon\Db\Adapter\Pdo;
class VirtualHostsController extends ControllerBase
{
	public function indexAction($message=NULL)
	{
		$this->secondaryMenu($this->controller,$this->action);
		$this->tools($this->controller,$this->action);
		
		if ($message=="uploadOK"){
			$message=new \Ajax\semantic\html\collections\HtmlMessage("message","La nouvelle configuration est dès à présent utilisée. <br />Vous pouvez visualiser son contenu dans l'onglet \"Récapitulatif\" dans la configuration de l'hôte virtuel.");
			$message->addHeader("Configuration mise à jour");
			$message->setColor("green");
			
			$this->view->setVar("message", $message);
		}
		
		if ($message=="uploadFailed"){
			$message=new \Ajax\semantic\html\collections\HtmlMessage("message","L'une des causes peut-être :");
			$message->addHeader("Erreur lors de l'envoi");
			$message->addList(array("Vous n'avez pas envoyé un fichier de configuration valide, seul les fichiers de configuration *.txt sont acceptés.","Le fichier est trop gros.","Nous n'avons pas les droits d'écritures du dossier upload."),false);
			$message->setColor("red");
				
			$this->view->setVar("message", $message);
		}
		$this->jquery->compile($this->view);
	}
	
	public function configAction(){
		$this->secondaryMenu($this->controller,$this->action);
		$this->tools($this->controller,$this->action);
		
		$semantic=$this->semantic;
		
		$virtualHosts = Virtualhost::findFirst();
		$server=$virtualHosts->getServer();
		$host=$server->getHost();
		
		if($host->getIpv6() == ""){$IPv6 = "Aucune attribuée";}else{$IPv6 = $host->getIpv6();}
		
		$this->view->setVar("virtualHost", $virtualHosts);
		
		
		$check=new HtmlIcon("","large green checkmark");
		
		if ($server->getName() != NULL && $host->getName() != NULL && $host->getIPv4() != NULL || $IPv6 != NULL){
			$check="Config. 0K "; 
			$check.=new HtmlIcon("", "large green checkmark");
		}else{
			$check="Err. config ";
			$check.=new HtmlIcon("", "large red bug");
		}
		
		$title=$semantic->htmlHeader("header1",2);
		$title->asTitle("Informations générales","Permet de vérifier l'état actuel de machine");
		$this->view->setVar("title1", $title);
		

		$title2=$semantic->htmlHeader("header2",2);
		$title2->asTitle("Fichier de configuration","Fichier Apache actuellement utilisé sur l'hôte virtuel");
		//$semantic->htmlIcon("editIcon","edit")->getOnClick("VirtualHosts/editApache","#modification2");
		
		$semantic->htmlIcon("editIcon", "edit")->onClick("$('.settings').trigger('click')")->onClick("$('#modifier').trigger('click')");
		//->onClick("$('#modifier').trigger('click')");
		$this->view->setVar("title2", $title2);
		
		$table=$semantic->htmlTable('infos',5,3);
		$table->setHeaderValues(["","Valeur","Description"]);
		$table->setValues([["Etat global : ",$check,"<i>Vérifie si la machine dispose d'une configuration suffisante</i>"],
				["Serveur",$server->getName(),"<i>Nom du serveur sur lequel est hebergé la machine</i>"],
				["Machine",$host->getName(),"<i>Nom de l'hôte hebergeant l'hôte virtuel</i>"],
				["Adresse IPv4",$host->getIpv4(),"<i>Adresse IPv4 affectée à l'hôte virtuel</i>"],
				["Adresse IPv4",$IPv6,"<i>Adresse IPv6 affectée à l'hôte virtuel</i>"],
				
		]);
		$table->setDefinition();
		
		/*$table=$this->semantic->htmlTable("infos",2,4);
		$table->setHeaderValues(["Machine","Serveur","Adresse IPv4","Adresse IPv6"]);
		$table->setValues([$host->getName(),$server->getName(),$host->getIpv4(),$IPv6]);
			*/
		$semantic->htmlButton("modifier","Modifier")->getOnClick("VirtualHosts/editApache","#modification")->setPositive();
		
		$buttons=$this->semantic->htmlButtonGroups("importOrExport",array("Importer","Exporter"));
		$buttons->insertOr(0,"ou");		
		$buttons->getElement(0)->getOnClick("VirtualHosts/readConfig/".$virtualHosts->getId()."","#uploadExport");
		$buttons->getElement(2)->getOnClick("VirtualHosts/exportConfig/".$virtualHosts->getId()."","#uploadExport");
		
		
		$this->jquery->exec("Prism.highlightAll();",true);
		$this->jquery->compile($this->view);
	}
	
	public function editApacheAction($idVirtualhost=NULL){
		$idVirtualhost=2;
		$semantic=$this->semantic;
		
		$properties=Property::find();
		$virtualHostProperties=Virtualhostproperty::find(
				[
						"idVirtualhost = {$idVirtualhost}",
						"order"=>"idProperty ASC",
				]
				);
		
		
		$table=$semantic->htmlTable("s-infos",0,6);
		$table->setHeaderValues(["","Nom","Description","Valeur actuelle","Nouvelle valeur"]);

		foreach ($virtualHostProperties as $virtualHostProperty){
			$property=$virtualHostProperty->getProperty();
		
			$value=$virtualHostProperty->getValue();
			$input=new HtmlInput("value[]","text",$value,"Nouvelle valeur");
			$input->setProperty("data-changed", "label$i");
			$table->addRow([$semantic->htmlLabel("label$i","État"),
					$property->getName(), $property->getDescription(),
					$value,($input)
					.(new HtmlInput("id[]","hidden",$property->getId())),
					
			]);	
			$i=$i+1;
		}
	
		$footer=$table->getFooter()->setFullWidth();
		$footer->mergeCol(0,1);
		$bt=HtmlButton::labeled("submit","Valider","settings");
		$bt->setFloated("right")->setColor('blue');
		$bt->postFormOnClick("VirtualHosts/updateConfig", "frmConfig","#info");
		$footer->getCell(0,1)->setValue([$bt]);
		$semantic->htmlInput("idvh","hidden",$idVirtualhost);
		
		$table->setSortable(2);
		

		$this->jquery->change("[data-changed]","$('#'+$(this).attr('data-changed')).html('Modifié');");
		$this->jquery->compile($this->view);
	}
	
	public function updateConfigAction(){
		$this->jquery->exec("$('#info').show();",true);

		echo "Mise à jour des propriétés effectuées !";

		$i = 0;	
		$idVH=$_POST["idvh"];
		foreach($_POST["id"] as $property){	
			$property=Virtualhostproperty::findFirst("idVirtualhost=$idVH AND idProperty=$property");
			$property->setValue($_POST["value"][$i]);
			$property->save();
			$i=$i+1;			
		}
		echo $this->jquery->compile();
	}
	
	public function readConfigAction($idVirtualHost=NULL){
		
		$this->secondaryMenu($this->controller,$this->action);
		$this->tools($this->controller,$this->action);

		$vh=Virtualhost::findFirst("id=$idVirtualHost");
		$this->view->setVar("vh", $vh);
		
		$target_dir = APP_PATH."/uploads/";
		if (!file_exists($target_dir)){
			mkdir($target_dir,077,true);
		}
		
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
		
		// Check if file already exists
	/*	if (file_exists($target_file)) {
			$state2="Désolé, le fichier existe déjà.";
			$uploadOk = 0;
			$this->view->setVar("state2", $state2);
		}*/
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
			$uploadOk = 0;
			$this->view->setVar("state2", $state2);
		}
		// Allow certain file formats
		if($fileType != "txt" ) {
					$state3="Seul les fichiers textes sont acceptés.";
					$uploadOk = 0;
					$this->view->setVar("state3", $state3);
				}
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
					$state4="Aucun fichier n'a pas été envoyé.";
					$this->view->setVar("state4", $state4);
					// if everything is ok, try to upload file
				} else {
					if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {						
						$fichier=fopen($target_file, "r") or die("Impossible de lire le fichier");
						$resultat = fread($fichier, filesize($target_file));
						$db=mysqli_connect("localhost","root","","virtualhosts");
						
						$idVH=$vh->getId();
						$_resultat=str_replace("'", "\'", $resultat);
						$db->query("UPDATE virtualhost SET config ='$_resultat' WHERE id='$idVH'");
						fclose($fichier);						
						$state4="Le fichier ". basename( $_FILES["fileToUpload"]["name"]). " a bien été envoyé.<a href='/VirtualHosts/VirtualHosts/'>Cliquez-ici pour revenir à la configuration</a>";
						
						//$this->response->redirect("VirtualHosts/index/uploadOK");
						
						$this->view->setVar("state4", $state4);
					} else {
						$state4="Désolé, il y a eu une erreur lors de l'envoi.";
						$this->view->setVar("state4", $state4);
					}
				}
	$this->jquery->compile($this->view);
	}
		
	public function exportConfigAction($idVirtualHost=NULL){
		$semantic=$this->semantic;		
		
		// Récupérer la config du VH actuel
		$virtualHost = Virtualhost::findFirst("id=$idVirtualHost");
		$config = $virtualHost->getConfig();
		$id = $virtualHost->getId();
		
		// Créer le dossier temporaire
		$target_dir = APP_PATH."/uploads/tmp";
		if (!file_exists($target_dir)){
			mkdir($target_dir,077,true);
		}
		
		// Créer le pointeur
		$fp = fopen("$target_dir/configVH_$id.htaccess","w");
		
		// Ecrire dans le fichier
		fwrite($fp, "$config");
		
		// Fermer le fichier
		fclose($fp);
		
		$semantic->htmlButton("telecharger","Télécharger")->asLink("./downloadConfig/$id");
		$this->jquery->compile($this->view);
	}
	
	public function downloadConfigAction($id=NULL){
	$target_dir = APP_PATH."/uploads/tmp";
	$file =  $target_dir . "/configVH_$id.htaccess";
	
	if (file_exists($file)) {
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename="'.basename($file).'"');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($file));
	    readfile($file);
	    exit;
	}
	}
}