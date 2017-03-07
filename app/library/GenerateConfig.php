<?php
/**
 * Classe de génération de fichier de configuration à partir de templates
 * @author djezuskryst
 * @version 1.0
 */
class GenerateConfig{
    public static function getServerConfigTemplate($virtualHost){
        $configTemplate = $virtualHost->getServer()->getStype()->getConfigTemplate();
        return $configTemplate;
    }


    public static function getServerVirtualHosts($idServer){
        // Récupérer l'objet serveur
        $server = Server::findFirst($idServer);

        // Prendre que les VH ayant l'id du server
        $server->getVirtualhost();
    }

	public static function getVirtualHostProperties($IdVirtualHost){
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