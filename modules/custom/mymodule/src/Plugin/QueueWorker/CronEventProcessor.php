<?php
/**
*
* PHP Version 5
*/

namespace Drupal\mymodule\Plugin\QueueWorker;

/**
*
* @QueueWorker(
* id = "email_processor",
* title = "My custom Queue Worker",
* cron = {"time" = 10}
* )
*/
class CronEventProcessor extends EmailEventBase {

}