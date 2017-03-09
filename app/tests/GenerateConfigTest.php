<?php

class GenerateConfigTest extends \UnitTestCase {
    public function testGetServerConfigTemplate(){
        $config = GenerateConfig::getServerConfigTemplate(Virtualhost::findFirst());
        $this->assertNotNull($config);
    }

    public function testGetVHStypeProperties(){
        $sTypeProperties = GenerateConfig::getVHStypeproperties(Virtualhost::findFirst());
        $this->assertNotNull($sTypeProperties);
    }

    public function testGetVHProperties(){
        $properties = GenerateConfig::getVHProperties(Virtualhost::findFirst());
        $this->assertNotNull($properties);
    }
}