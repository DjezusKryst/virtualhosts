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

    public static function getVHStypeproperties($virtualHost){
        $properties = $virtualHost->getServer()->getStype()->getStypeproperties();
        return $properties;
    }
}