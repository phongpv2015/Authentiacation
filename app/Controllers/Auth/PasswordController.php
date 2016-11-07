<?php

namespace App\Controllers\Auth;
use App\Controllers\Controller;
use App\Models\User;
use Respect\Validation\Validator as v;

class PasswordController extends Controller
{
	public function getChangePassword($request,$reponse)
	{
		return $this->view->render($reponse,'auth/password/change.twig');
	}
	public function postChangePassword($request,$reponse)
	{
		$validation = $this->validator->validate($request,[
				'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
				'password' => v::noWhitespace()->notEmpty(),
			]);

		if ($validation->failed()) {
				return $this->response->withRedirect($this->router->pathFor('auth.password.change'));
		}

		$this->auth->user()->setPassword($request->getParam('password'));

		$this->flash->addMessage('info','Your password had been changed.');

		return $this->response->withRedirect($this->router->pathFor('home'));
	}
}