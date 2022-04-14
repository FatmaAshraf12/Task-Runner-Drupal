<?php
/**
 * @file
 * Contains \Drupal\customlogin\Form\LoginForm.
 */
namespace Drupal\customlogin\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class LoginForm extends FormBase {
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

    $form['username'] = array(
      '#type' => 'textfield',
      '#title' => t('Username :'),
      '#required' => TRUE,
    );

    $form['password'] = array(
      '#type' => 'password',
      '#title' => t('Password:'),
      '#required' => TRUE,
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Login'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
    /*public function validateForm(array &$form, FormStateInterface $form_state) {

      if (strlen($form_state->getValue('candidate_number')) < 10) {
        $form_state->setErrorByName('candidate_number', $this->t('Mobile number is too short.'));
      }

    }*/

  /**
   * {@inheritdoc}
   */
   
     public function submitForm(array &$form, FormStateInterface $form_state) {
       $userLogin = $form_state->getValues();
       $username = $userLogin['username'];
       $password = $userLogin['password'];
	
	 // \Drupal::messenger()->addMessage($username.'    '.$password);
	  $uid = \Drupal::service('user.auth')->authenticate($username, $password);
	  if(!(empty($uid))){
	      	  \Drupal::messenger()->addMessage($uid);
	     // $form_state->setRedirectUrl('https://www.google.com/');
	      header("Location: https://your-beauty.online/drupal0/createtask");
die();
	  }
	  else
	      	  \Drupal::messenger()->addMessage('not valid');

    
  }
}