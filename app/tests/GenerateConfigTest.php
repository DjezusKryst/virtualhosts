<?php

class GenerateConfigTest extends \UnitTestCase {
    public function testGetServerConfigTemplate(){
        $config = GenerateConfig::getServerConfigTemplate(Virtualhost::findFirst());
        $this->assertNotNull($config);
    }
}
