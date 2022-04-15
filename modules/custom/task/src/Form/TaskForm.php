<?php
/**
 * @file
 * Contains \Drupal\task\Form\TaskForm.
 */
namespace Drupal\task\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Random;

class TaskForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'customlogin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['projectId'] = array(
      '#type' => 'textfield',
      '#title' => t('Project Id :'),
      '#required' => TRUE,
         '#maxlength' => 10,
           '#default_value' => 'PRJ_ABCDEF',

    );

    $form['taskType'] = array(
      '#type' => 'radios',
      '#title' => t('Task Type :'),
      '#required' => TRUE,
      '#options' => array(
        'countWords' => t('Count Words'),
		'countLines' => t('Count Lines'),
        'countCharcters' => t('Count Characters'),
      ),
    );
    /*
    $form['inputFile'] = array(
      '#type' => 'file',
      '#title' => t('Input File :'),
      '#required' => FALSE,
    );
    
    */
    
     $form['inputFile'] = array(
        '#type' => 'managed_file',
        '#name' => 'inputFile',
        '#title' => t('File'),
              '#required' => TRUE,

        '#upload_validators' => array(
            'file_validate_extensions' => array('txt')
            ),
        '#upload_location' => 'public://files/',
        );

 
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Create Task'),
      '#button_type' => 'primary',
    );
    return $form;
  }



  /**
   * {@inheritdoc}
   */
    public function validateForm(array &$form, FormStateInterface $form_state) {


    }

  /**
   * {@inheritdoc}
   */
   
     public function submitForm(array &$form, FormStateInterface $form_state) {
        //require 'projectsPage.php';

       if ($form_state->hasFileElement())
        {
          $passport_scan_file_array = $form_state->getValue('inputFile');
          if (is_array($passport_scan_file_array))
          {
            if (isset($passport_scan_file_array[0]))
            {
              $passport_scan_file_id = $passport_scan_file_array[0];
              $file = \Drupal\file\Entity\File::load($passport_scan_file_id);
              if ($file != NULL)
              {
                $filename = $file->getFilename();
              }
            }
          }
        }
        
        $randomID =  rand(100000, 999999);

        $connection = \Drupal::service('database');
        $result = $connection->insert('projects')
          ->fields(['name', 'id' , 'task_type', 'running','occurences' , 'link','file' , 'created_at' ])
          ->values([
            'name' => $form_state->getValue('projectId'),
            'id' => $randomID,
            'task_type' => $form_state->getValue('taskType'),
            'running' => '',
            'occurences' => 0,
            'link' => $filename,
            'file' => $filename,
            'created_at' => date("Y-m-d H:i:s", time()),
          ])
          ->execute();


        \Drupal::messenger()->addMessage('Filename: ' . $filename);

	   $queue_factory = \Drupal::service('queue');
        /** @var QueueInterface $queue */
        $queue = $queue_factory->get('create_task');
        $item = new \stdClass();
        $item->projectId = $form_state->getValue('projectId');
        $item->taskType = $form_state->getValue('taskType');
        $item->custom_id = $randomID;
        $item->linkFile = $filename;
        $queue->createItem($item);
         header("Location: https://your-beauty.online/drupal0/projects");
die();

    
  }
}