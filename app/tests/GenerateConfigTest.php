<?php

class GenerateConfigTest extends \UnitTestCase {
    public function testGetServerConfigTemplate(){
        $config = GenerateConfig::getServerConfigTemplate(Virtualhost::findFirst());
        $this->assertNotNull($config);
    }
    public function testGetVirtualHostTemplate(){
        $config = GenerateConfig::getVirtualHostTemplate(Virtualhost::findFirst());
        $this->assertNotNull($config);
    }
}