<?php
/**
 * @file
 * Contains \Drupal\task\Controller\taskController.
 */

namespace Drupal\task\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use \Drupal\Component\Render\FormattableMarkup;

/**
 * Class taskController.
 *
 * @package Drupal\task\Controller
 */
class taskController extends ControllerBase {

  /**
   * showdata.
   *
   * @return string
   *   Return Table format data.
   */
   
   
   
    
  public function showProjectsPage() {

    $result = \Drupal::database()->query("SELECT * FROM `dr2o_projects` GROUP BY `name`");

    $rows = array();
    
    /** Replaced latest stauses with charcter insted of circlls and icons
      * *  '+' : pass status
      *     '-' : not yet
      *     'x' : failed
      * 
     */
    
    foreach ($result as $row => $content) {
        $strWords='' ; $strLines='' ; $strChar='';
        $countWordsTasks = \Drupal::database()->query("SELECT `running` FROM `dr2o_projects` WHERE `name` = '$content->name' AND `task_type` = 'countWords' ORDER BY `created_at` DESC LIMIT 5");

        foreach ($countWordsTasks as $rr => $x) {
            if($x->running=='Pass')          $strWords .= '+';
            else if($x->running=='Fail')     $strWords .= 'x';
        }
            
        while(strlen($strWords) <5)     $strWords .= '-';
        
        $countLineTasks = \Drupal::database()->query("SELECT `running` FROM `dr2o_projects` WHERE `name` = '$content->name' AND `task_type` = 'countLines' ORDER BY `created_at` DESC LIMIT 5");

        foreach ($countLineTasks as $rr => $x) {    
            if($x->running=='Pass')          $strLines .= '+';
            else if($x->running=='Fail')     $strLines .= 'x';
        }
        while(strlen($strLines) <5)      $strLines .= '-';
            
        $countCharTasks = \Drupal::database()->query("SELECT `running` FROM `dr2o_projects` WHERE `name` = '$content->name' AND `task_type` = 'countCharcters' ORDER BY `created_at` DESC LIMIT 5");

        foreach ($countCharTasks as $rr => $x) {    
            if($x->running =='Pass')         $strChar .= '+';
            else if($x->running=='Fail')     $strChar .= 'x';
        }
         while(strlen($strChar) <5)     $strChar .= '-';
            

        $rows[] = array(
            'data' => array( $content->name, 'Count Words' ,$strWords, $content->running_status ,  
            new FormattableMarkup('<a href=":link">@name</a>', [':link' =>'/drupal0/projectPage/'.$content->name , '@name' => 'Project Page'])));
            
         $rows[] = array(
            'data' => array( '', 'Count Lines' ,$strLines, '' , ''));
            
        $rows[] = array(
            'data' => array( '', 'Count Charcters' ,$strChar, '' , ''));
    }

    $header = array('name', 'tasks' ,'', 'running' , 'link');
    $output = array(
      '#theme' => 'table',    
      '#header' => $header,
      '#rows' => $rows
    );
    return $output;
  }
  
  
  public function showProjectPage($name) {
      
    $result = \Drupal::database()->query("SELECT * FROM `dr2o_projects` WHERE `name` = '$name' ORDER BY `created_at` DESC ");
    
    foreach ($result as $row => $content) {
        $rows[] = array(
                'data' => array( $content->id, $content->task_type , $content->occurences ,$content->running , $content->created_at , $content->started_at , $content->ended_at));
            }
 
    $header = array('ID', 'Type', '#occurences' ,'Result', 'Created At' , 'Started At' , ' Ended At');
    $output = array(
      '#theme' => 'table',    
      '#header' => $header,
      '#rows' => $rows
                
    );
    return $output;
  }

}


