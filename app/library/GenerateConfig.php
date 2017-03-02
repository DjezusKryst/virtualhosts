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
	
	public function {
	
	}
}