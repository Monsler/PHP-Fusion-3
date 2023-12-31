<?php
namespace phpfusionthree\forms;

use facade\Json;
use std, gui, framework, phpfusionthree;


class ValueEForm extends AbstractForm
{

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        global $_cp, $vsel_, $___ctable;
        $seler = $vsel_ + 1;
        $seler = "block$seler";
        $this->addStylesheet('/.theme/newdark.css');
        $this->title = "$_cp: Value Editor";
        for($i = 0; $i < $___ctable[$seler]['pcount']; $i++){
            $n = $i + 1;
            $this->listView->items->add("Parameter $n");
        }
        $this->textArea->text = $___ctable[$seler]['param1'];
        global $_cup;
        $_cup = 'param1';
    }

    /**
     * @event close 
     */
    function doClose(UXWindowEvent $e = null)
    {    
        app()->form(MainForm)->fragment->content->renderBlocks();
        $this->free();
    }

    /**
     * @event listView.click-2x 
     */
    function doListViewClick2x(UXMouseEvent $e = null)
    {    
        global $_cp, $vsel_, $___ctable;
        $seler = $vsel_ + 1;
        $seler = "block$seler";
        $s = $this->listView->selectedIndex + 1;
        $this->textArea->text = $___ctable[$seler]['param'.$s];
        global $_cup;
        $_cup = 'param'.$s;
    }

    /**
     * @event button.action 
     */
    function doButtonAction(UXEvent $e = null)
    {    
         global $vsel_, $___ctable, $_cup, $PD, $_cp, $_os, $___editor;
        $seler = $vsel_ + 1;
        //alert($PD.'/'.$_cp.'/code_blocks.json');
        $seler = "block$seler";
        $r = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1);
        $r[$_os][$seler][$_cup] = $this->textArea->text;
        
        var_dump($r);
        file_put_contents($PD.'/'.$_cp.'/code_blocks.json', json_encode($r));
        $___ctable = $r[$_os];
        $___editor->renderBlocks();
        //$this->free();
        $this->toast('Parameter successfully saved');
    }

    /**
     * @event listViewAlt.click-2x 
     */
    function doListViewAltClick2x(UXMouseEvent $e = null)
    {    
        $_s = $this->listViewAlt->selectedItem;
        
        if($_s == 'Variable'){
            $tx = UXDialog::input('Variable name:');
            if($tx != ''){
                $this->textArea->text .= '$objs["VARS_"]["'.$tx.'"]';
            }
        }elseif($_s == 'Project path'){
            $this->textArea->text .= '$project_path';
        }elseif($_s == 'Read from save'){
            $r = UXDialog::input("Filename:");
            if($r != ''){
                $this->textArea->text .= "file_get_contents(\"".'$project_path'."/dat/$r\")";
            }
        }
    }

    /**
     * @event listView3.click-2x 
     */
    function doListView3Click2x(UXMouseEvent $e = null)
    {
        $_s = $this->listView3->selectedItem;
        
        if($_s == 'Equals'){
            $this->textArea->text .= ' == ';
        }elseif($_s == 'True'){
            $this->textArea->text .= 'true';
        }elseif($_s == 'False'){
            $this->textArea->text .= 'false';
        }elseif($_s == 'Or (in variable)'){
            $this->textArea->text .= ' ?: ';
        }elseif($_s == 'Or (in compare)'){
            $this->textArea->text .= ' or ';
        }
    }

    /**
     * @event buttonAlt.action 
     */
    function doButtonAltAction(UXEvent $e = null)
    {
        $this->textArea->text = '';
        $this->toast('Parameter successfully cleared');
    }

    /**
     * @event button3.action 
     */
    function doButton3Action(UXEvent $e = null)
    {
         $this->textArea->text = substr($this->textArea->text, 0, strlen($this->textArea->text)-1);
    }

    /**
     * @event colorPicker.action 
     */
    function doColorPickerAction(UXEvent $e = null)
    {    
        $this->textArea->text .= "'".$this->colorPicker->value."'";
    }

    /**
     * @event listView4.click-2x 
     */
    function doListView4Click2x(UXMouseEvent $e = null)
    {
        $_s = $this->listView4->selectedItem;
        
        if($_s == 'Information'){
            $this->textArea->text .= '"INFORMATION"';
        }elseif($_s == 'Warning'){
            $this->textArea->text .= '"WARNING"';
        }elseif($_s == 'Error'){
            $this->textArea->text .= '"ERROR"';
        }
    }


}
