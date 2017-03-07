<?php

class GenerateConfigTest extends \UnitTestCase {
    public function testGetServerConfigTemplate(){
        $config = GenerateConfig::getServerConfigTemplate(Virtualhost::findFirst());
        $this->assertNotNull($config);
    }
    public function testGetVHStypeProperties(){
        $properties = GenerateConfig::getVHStypeproperties(Virtualhost::findFirst());
        $this->assertNotNull($properties);
    }
}