<?php
class GenerateConfigTest extends \UnitTestCase {
	public function testGetServeur(){
		$virtualHost = Virtualhost::findFirst();
		$server = $virtualHost->getServer();
		$this->assertNotNull($server);
	}
	
	public function testGetVirtualHostProperties(){
		$virtualHost = Virtualhost::findFirst();
		$virtualHostId=$virtualHost->getId();
		$virtualHostProperties=Virtualhostproperty::find(
				[
						"idVirtualhost = {$virtualHostId}",
						"order"=>"idProperty ASC",
						]
				);
		
		$this->assertTrue(sizeof($virtualHostProperties)>0);
	}
	
	public function testGetServerStype(){
		// Récupérer l'objet serveur
		$server = Server::findFirst();
	
		// Récupérer l'id du stype
		$idStype = $server->getIdStype($server);
	
		// Récupérer l'objet stype correspondant au serveur obtenu précédemment
		$stype = Stype::findFist($idStype);
	
		// Retourner le résultat
		$this->assertNotNull($stype);
	}
}
