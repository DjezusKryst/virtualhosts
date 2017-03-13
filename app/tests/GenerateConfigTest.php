<?php

class GenerateConfigTest extends \UnitTestCase {

    public static function connectDb(){
        $db = new Phalcon\Db\Adapter\Pdo\Mysql(array(
            'host' => 'localhost',
            'username' => 'root',
            'password' => '',
            'dbname' => 'virtualhosts',
        ));

        $db->connect();
        return $db;
    }
    public static function setupBeforeClass(){
        $db = GenerateConfigTest::connectDb();
        $sql = "INSERT INTO virtualhost(name,config,idServer,idUser) VALUES('test_name','test_config',1,1);";
        $result_set = $db->query($sql);
    }

    public static function teardownAfterClass(){
        $db = GenerateConfigTest::connectDb();
        $sql = "DELETE FROM `virtualhost` WHERE name = 'test_name';";
        $result_set = $db->query($sql);
    }

    public function testGetServerConfigTemplate(){
        $config = GenerateConfig::getServerConfigTemplate(Virtualhost::findFirst());
        //$done = str_replace('{{name}}',Virtualhost::findFirst()->getName(),$config);
        $this->assertNotNull($config);
    }

    public function testGetVHStypeProperties(){
        $sTypeProperties = GenerateConfig::getVHStypeproperties(Virtualhost::findFirst());
        var_dump($sTypeProperties);
        $this->assertNotNull($sTypeProperties);
    }

    public function testGetVHProperties(){
        $properties = GenerateConfig::getVHProperties(Virtualhost::findFirst());
        $this->assertNotNull($properties);
    }


}