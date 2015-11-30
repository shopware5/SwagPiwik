<?php
/**
 * Piwik - Open source web analytics
 * @link http://www.shopware.de
 * @package Plugins
 * @subpackage Frontend
 * @copyright Copyright (c) 2012, shopware AG
 * @version 1.0.2
 * @author shopware AG (s.kloepper)
 */
class Shopware_Plugins_Frontend_SwagPiwik_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
	/**
	 * standard install method - subscribe an event
	 * define destination of piwik-installation
	 * an define the site-id
	 * @return bool
	 */
	public function install()
	{		
		$this->subscribeEvent('Enlight_Controller_Action_PostDispatch', 'onPostDispatch');
		
		$form = $this->Form();
		$form->setElement('text', 'p_url', array('label'=>'Pfad zu Piwik (mit Slash am Ende)','value'=>'www.meinshop.de/piwik/','scope'=> \Shopware\Models\Config\Element::SCOPE_SHOP));
		$form->setElement('text', 'p_ID', array('label'=>'Seiten-ID Piwik','value'=>'1','scope'=> \Shopware\Models\Config\Element::SCOPE_SHOP));
		$form->save();
		
	 	return true;
	}
	
	/**
	 * Returns the version of this plugin
	 *
	 * @return string
	 */
	public function getVersion()
	{
        $info = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .'plugin.json'), true);

        if ($info) {
            return $info['currentVersion'];
        } else {
            throw new Exception('The plugin has an invalid version file.');
        }

    }
	
	/**
	 * Define template and variables
	 * @param Enlight_Event_EventArgs $args
	 */
	public function onPostDispatch(Enlight_Event_EventArgs $args)
	{
		$request = $args->getSubject()->Request();
		$response = $args->getSubject()->Response();
		
		$view = $args->getSubject()->View();
		$config = Shopware()->Plugins()->Frontend()->SwagPiwik()->Config();
        if (!$request->isDispatched() || $response->isException() || $request->getModuleName() != 'frontend' || !$view->hasTemplate()) {
             return;
         }
		$view->SwagPiwik = $config;
        $view->addTemplateDir($this->Path() . 'Views/');
		$args->getSubject()->View()->extendsTemplate('frontend/plugins/swag_piwik/index.tpl');
	}
	
	/**
	 * standard meta description
	 * @return unknown
	 */
	public function getInfo()
    {
        return array(
            'version' => $this->getVersion(),
            'autor' => 'shopware AG',
            'copyright' => 'Copyright � 2012, shopware AG',
            'label' => 'Piwik - Open source web analytics',
            'source' => $this->getSource(),
            'description' => 'Mit diesem Plugin kann der Shopware Shop an die Open Source Analytics Software Piwik angebunden werden. Piwik ist eine Open-Source (GPL lizenzierte) Webanalyse-Software, die heruntergeladen werden kann. Piwik bietet Ihnen detaillierte Echtzeit-Berichte &uuml;ber die Besucher Ihrer Homepage, die genutzten Suchmaschinen und Suchbegriffe, die Sprache, Ihre beliebten Seiten&hellip; und vieles mehr. Folgende Funktion unterst&uuml;tzt das Plugin aktuell:  Allgemeines Tracking (Zugriffe / Besucher usw.) Erfassen von Bestellungen (inkl. Artikelnummern, Artikelnamen und Brutto-Endbetrag) Erfassen von Warenk&ouml;rben Erfassen von Detailseiten Erfassen von Kategorien  Weitere Informationen zu Piwik: http://de.piwik.org/ http://de.piwik.org/dokumentation/piwik-installieren/  ',
            'license' => '',
            'support' => 'http://forum.shopware.de',
            'link' => 'http://www.shopware.de',
            'changes' => array(
                '1.0.1'=>array('releasedate'=>'2011-06-20', 'lines' => array(
                    'First release'
                )),
                '1.0.2'=>array('releasedate'=>'2012-10-22', 'lines' => array(
                    'Updated for Shopware 4.0.0'
                ))
            ),
        );
    }
}