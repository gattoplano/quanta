<?php
namespace Quanta\Common;

/**
 * Implements hook_page_after_build().
 *
 * @param Environment $env
 *   The Environment.
 * @param array $vars
 *   An array of variables.
 */
function message_page_after_build(Environment $env, array $vars) {
  $messages = $env->getData('message');
  // Check if there are messages to be added to the system log file.
  if (!empty($messages)) {
    foreach ($messages as $message) {
      if ($message->type == \Quanta\Common\Message::MESSAGE_TYPE_LOG) {
      $username = UserFactory::current($env)->name;
        $fh = fopen($env->dir['log'] . '/log.txt', 'a+') or die("error");
        fwrite($fh, '[' . date('Y-m-d h:i:s') . '] ' . str_replace('!username', $username, $message->body) . '
  ');
        fclose($fh);
      }
    }
  }
}

/**
 * Implements hook_load_includes().
 *
 * @param Environment $env
 *   The Environment.
 *
 * @param array $vars
 *   An array of variables.
 */
function message_load_includes(Environment $env, array $vars) {
  $module_path = $env->getModulePath('message');
  $env->addInclude($module_path . '/assets/css/message.css');
}
