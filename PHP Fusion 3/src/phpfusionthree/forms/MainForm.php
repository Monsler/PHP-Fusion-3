<?php
namespace phpfusionthree\forms;

use std, gui, framework, phpfusionthree;


class MainForm extends AbstractForm
{

    function langGet($elem){
        global $currLang;
        if(!$currLang){
            return json_decode(file_get_contents('res://.data/ru.json'), 1)[$elem];
        }else{
            return json_decode(file_get_contents('res://.data/'.$currLang.'.json'), 1)[$elem];
        }
    }

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {
        global $PD;
        $PD = '.pf3';
        $AD = 'modules';
        
        global $_LOCALE;
        $r = json_decode(file_get_contents("config_pfs.json"), 1)['lang'];
        //$_LOCALE = json_decode(file_get_contents("res://.data/locale/$r.json"), 1);
        
        if(!file_exists($PD)){
            mkdir($PD);
        }
        $this->addStylesheet('/.theme/newdark.css');
        $this->fragment->content = MenuForm;
        $this->minWidth = 792;
        $this->minHeight = 504;
        
        
    }

    /**
     * @event keyDown-F11 
     */
    function doKeyDownF11(UXKeyEvent $e = null)
    {    
        $this->fullScreen = !$this->fullScreen;
    }




}
