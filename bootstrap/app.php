<?php

use Respect\Validation\Validator as v;

session_start();

require __DIR__.'/../vendor/autoload.php';

$app = new \Slim\App([
	'settings'=> [
		'displayErrorDetails'=>true,	
	    'determineRouteBeforeAppMiddleware' => true,
	    'addContentLengthHeader' => false,	
		'db'=>[
			'driver'=>'mysql',
			'host'=>'localhost',
			'database'=>'site',
			'username'=>'root',
			'password'=>'',
			'charset'=>'utf8',
			'collation'=>'utf8_unicode_ci',
			'prefix'=>'',
		],
		'mail'=>[
			'smtp_auth'=>true,
			'smtp_secure'=>'tsl',
			'host'=>'smtp.gmail.com',
			'username'=>'phanvanphong18@gmail.com',
			'password'=>'12345',
			'port'=>587,
			'html'=>true,
		]
	]
]);


$container = $app->getContainer();

$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($container) use ($capsule){
	return $capsule;
};

$container['mailer'] = function($container){
	return ;
};

$container['auth'] = function($container){
	return new \App\Auth\Auth;	
};
$container['flash'] = function($container){
	return new \Slim\Flash\Messages;	
};
$container['view'] = function($container){
	$view = new \Slim\Views\Twig(__DIR__.'/../resource/views',['cache' =>false]);
	$view->addExtension(new \Slim\Views\TwigExtension(
			$container->router,
			$container->request->getUri()			
		));
	$view->getEnvironment()->addGlobal('auth',[
			'check'=>$container->auth->check(),
			'user'=>$container->auth->user(),
			'cookie'=>$container->auth->user()

		]);
	$view->getEnvironment()->addGlobal('flash',$container->flash);
	return $view;
};
$container['csrf'] = function($container){
	return new \Slim\Csrf\Guard;	
};

$container['HomeController'] = function($container){
	return new \App\Controllers\HomeController($container);	
};

$container['AuthController'] = function($container){
	return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function($container){
	return new \App\Controllers\Auth\PasswordController($container);
};

$container['validator'] = function($container){
	return new \App\Validation\Validator;
};

$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);

v::with('App\\Validation\\Rules\\');
require '/../app/routes.php';