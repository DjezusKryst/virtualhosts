<?php
/**
 * Classe de génération de fichier de configuration à partir de templates
 * @author djezuskryst
 * @version 1.0
 */
class GenerateConfig{
	/**
	 * Retourne le serveur correspondant à un VirtualHost
	 * @return Server
	 */
	public function getVirtualHostServer($IdVirtualHost){
		// Récupérer le VH passé en paramètres
		$virtualHost = Virtualhost::findFirst($IdVirtualHost);
		
		// Récupérer le serveur correspondant
		$server = $virtualHost->getServer();	
		
		// Retourner le serveur récupéré
		return $server;
	}
	
	public function getServerStype($idServer){
		// Récupérer l'objet serveur
		$server = Server::findFirst($idServer);
		
		// Récupérer l'id du stype
		$idStype = $server->getIdStype();
		
		// Récupérer l'objet stype correspondant au serveur obtenu précédemment
		$stype = Stype::findFist($idStype);
		
		// Retourner le résultat
		return $stype;		
	}
	
	public function getVirtualHostProperties($IdVirtualHost){		
		// Récupérer les properties associées au VH
		$virtualHostProperties=Virtualhostproperty::find(
				[
						"idVirtualhost = {$IdVirtualHost}",
						"order"=>"idProperty ASC",
						]
				);
		
		return $virtualHostProperties;
	}
}