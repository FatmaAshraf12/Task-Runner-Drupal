<?php

/**
    * Handles creating a task.
*
* PHP Version 8
*/

namespace Drupal\task\Plugin\QueueWorker;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
*
* @inheritdoc
*/
class TaskCreationBase extends QueueWorkerBase implements ContainerFactoryPluginInterface {

/**
*
* @var Drupal\Core\Mail\MailManager
*/
protected $mail;
/**
* constructor
*/
public function __construct() {
}

/**
* {@inheritdoc}
*/
public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
return new static(
//$container->get('plugin.manager.mail')
);
}


    public function count_words($linkFile,$taskID){
        $result = \Drupal::database()->query("UPDATE `dr2o_projects` SET started_at = CURRENT_TIMESTAMP WHERE id = $taskID");
        $wordCount = str_word_count(file_get_contents('https://your-beauty.online/drupal0/sites/default/files/files/'.$linkFile));
        
        if($wordCount>0){
            $result = \Drupal::database()->query("UPDATE `dr2o_projects` SET occurences = $wordCount , ended_at = CURRENT_TIMESTAMP , running = 'Pass' WHERE id = $taskID");
        }
        else{
            $result = \Drupal::database()->query("UPDATE `dr2o_projects` SET occurences = $wordCount , ended_at = CURRENT_TIMESTAMP , running = 'Fail' WHERE id = $taskID");
        }
      
    }
    
    public function count_lines($linkFile,$taskID){
        $result = \Drupal::database()->query("UPDATE `dr2o_projects` SET started_at = CURRENT_TIMESTAMP WHERE id = $taskID");
        $lineCount = count(file('https://your-beauty.online/drupal0/sites/default/files/files/'.$linkFile));

        if($wordCount>0){
        $result = \Drupal::database()->query("UPDATE `dr2o_projects` SET occurences = $lineCount , ended_at = CURRENT_TIMESTAMP , running = 'Pass' WHERE id = $taskID");
        }
        else{
        $result = \Drupal::database()->query("UPDATE `dr2o_projects` SET occurences = $lineCount , ended_at = CURRENT_TIMESTAMP , running = 'Fail' WHERE id = $taskID");
        }

    }
    
    
    public function count_character($linkFile,$taskID){
        $result = \Drupal::database()->query("UPDATE `dr2o_projects` SET started_at = CURRENT_TIMESTAMP WHERE id = $taskID");
        $charCount = file_get_contents('https://your-beauty.online/drupal0/sites/default/files/files/'.$linkFile);
        
        $len = strlen($charCount);

        if(strlen($charCount)>0){
            $result = \Drupal::database()->query("UPDATE `dr2o_projects` SET occurences = $len , ended_at = CURRENT_TIMESTAMP , running = 'Pass' WHERE id = $taskID");
        }
        else{
            $result = \Drupal::database()->query("UPDATE `dr2o_projects` SET occurences = $len , ended_at = CURRENT_TIMESTAMP , running = 'Fail' WHERE id = $taskID");
        }
    }
    


/**
* Processes a single item of Queue.
*
*/
public function processItem($data) {
    $projectId = $data->projectId;
    $taskID = $data->custom_id;
    $taskType = $data->taskType;
    $linkFile = $data->linkFile;
    
    if($taskType){
    $result = \Drupal::database()->query("UPDATE `dr2o_projects` SET running_status = 'YES' WHERE name = $projectId");

    if($taskType == 'countWords')               
        $this->count_words($linkFile , $taskID);
    
    else if($taskType == 'countLines')          
        $this->count_lines($linkFile , $taskID);
    
    else if($taskType == 'countCharcters')      
        $this->count_character($linkFile , $taskID);
    }
    $result = \Drupal::database()->query("UPDATE `dr2o_projects` SET running_status = 'NO' WHERE name = $projectId");



}
}