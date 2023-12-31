<?php
namespace phpfusionthree\forms;

use std, gui, framework, phpfusionthree;


class SettingsForm extends AbstractForm
{


    /**
     * @event button3.click-Left 
     */
    function doButton3ClickLeft(UXMouseEvent $e = null)
    {
        $s = array();
        $s['developerKey'] = $this->edit->text;
        $s['autobuild'] = $this->checkbox->selected;
        $s['type'] = $this->combobox->value;
        file_put_contents('config_pfs.json', json_encode($s));
    }

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        $content = json_decode(file_get_contents('config_pfs.json'), 1);
        $this->edit->text = $content['developerKey'];
        $this->checkbox->selected = $content['autobuild'];
        $this->combobox->value = $content['type'];
    }

    /**
     * @event button.click-Left 
     */
    function doButtonClickLeft(UXMouseEvent $e = null)
    {
        if(UXDialog::confirm('Do you really want to go back?')){
            $this->form(MainForm)->fragment->content = MenuForm;
            $this->free();
        }
    }

}
