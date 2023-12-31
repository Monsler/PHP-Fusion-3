<?php
namespace phpfusionthree\modules;

use std, gui, framework, phpfusionthree;


class MainModule extends AbstractModule
{

    /**
     * @event timer.action 
     */
    function doTimerAction(ScriptEvent $e = null)
    {    
        $this->hide();
        $this->form(MainForm)->show();
    }



}
