<?php
/**
*
* PHP Version 8
*/

namespace Drupal\task\Plugin\QueueWorker;

/**
*
* @QueueWorker(
* id = "create_task",
* title = "My custom Queue Worker",
* cron = {"time" = 10}
* )
*/
class CronEventProcessor extends TaskCreationBase {

}