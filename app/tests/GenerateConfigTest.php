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
}
