<?php
  // Include the Environment module.
  require_once('core/environment/environment.module');

  // Include the Cache module.
  require_once('core/cache/cache.module');

  // Pre-set host.
  if (!isset($host)) {
    $host = NULL;
  }
  // Pre-set request uri.
  if (!isset($request_uri)) {
    $request_uri = NULL;
  }

  // Pre-set document root.
  if (!isset($docroot)) {
    $docroot = NULL;
  }

  // Create a new Environment.
  $env = new Environment($host, $request_uri, $docroot);

  // Check if the current request is a file rendering request.
  $env->checkFile();

  // Load the environment.
  $env->load();

  // Start the user session.
  $env->startSession();

  // Run all system modules.
  $env->runModules();

  // Run the boot hook.
  $env->hook('boot');

  // Start page's standard index.html.
  $page = new Page($env);
  $vars = array('page' => &$page);

  $env->setData('page', $vars['page']);

  // Run the init hook.

  if (!isset($_REQUEST['ajax'])) {
    $env->hook('load_includes', $vars);
    $page->loadIncludes();
  }

  // TODO: determine when to run setup.
  if (isset($doctor_cmd) && $doctor_cmd == 'setup') {
    // Create the doctor.
    $doctor = new Doctor($env);
    // Run the setup.
    $doctor->runSetup();
    exit;
  }

  // TODO: determine when to run doctor.
  if (isset($doctor_cmd)) {
    $doctor = new Doctor($env);
    switch ($doctor_cmd) {
      case 'clear-cache':
        $doctor->runClearCache();
        break;

      case 'setup':
        $doctor->runSetup();
        $doctor->runDoctor();

        break;

      case 'check':
      default:
        $doctor->runDoctor();
        break;
    }
    exit;
  }

  // Check if there is any requested action.
  $env->checkActions();

  // Run the init hook.
  $env->hook('init', $vars);

  // Build the page's HTML code.
  $page->buildHTML();

  // Render the page.
  print $page->render();

  // Run the complete hook.
  $env->hook('complete');

  // End the bootstrap.
  exit();
