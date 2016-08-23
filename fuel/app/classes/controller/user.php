<?php

class Controller_User extends Controller_Template
{

	public function before(){
        parent::before();
        $this->template->login = Auth::check();
    }

	public function get_register()
	{
		if (Auth::check()) {
			return Response::redirect_back('/');
		}

		$this->template->title = '加入會員';
		$this->template->content = View::forge('user/register');
	}

	public function post_register()
	{
		$username = Input::post('username');
		$password = Input::post('password');
		$email = Input::post('email');

		try {
			$created = Auth::create_user($username, $password, $email);
			if ($created) {
				Session::set_flash('success','加入會員成功，記得登入:)');
                return Response::redirect_back('/');
			} else {
				Session::set_flash('error','加入會員失敗:(');
			}
		} catch(SimpleUserUpdateException $e) {
			Session::set_flash('username', Input::post('username'));
            Session::set_flash('email', Input::post('email'));

			if($e->getCode() == 2) {
				Session::set_flash('hint_email', '此Email已註冊過');
			} elseif($e->getCode() == 3) {
				Session::set_flash('hint_username', '此帳戶已存在');
			} else { 
				Session::set_flash('failed', $e->getMessage());
			}
		}
		return Response::redirect('/register');
	}

	public function get_login()
	{
		if (Auth::check()) {
			Session::set_flash('success','您已經登入:) '.Auth::get('username'));
			return Response::redirect_back('/');
		}

        $this->template->title = '登入';
        $this->template->content = View::forge('user/login');
	}

	public function post_login()
	{
        $username = Input::post('username');
		$password = Input::post('password');
		$remember = Input::post('remember');

		// 檢查 CSRF 符記是否有效
		if ( ! \Security::check_token())
		{
			// CSRF 攻擊或過期的 CSRF 符記
			Session::set_flash('failed','登入失敗，驗證無效');
			return Response::redirect_back('/');
		}

		if (Auth::login($username, $password)) {
			if($remember) {
				Auth::remember_me();
			} else {
				Auth::dont_remember_me();
			}
			Session::set_flash('success','登入成功:)');
		} else {
			Session::set_flash('failed','登入失敗，請確認帳號密碼:(');
		}
		return Response::redirect('/login');
	}

	public function get_logout()
	{
		Auth::dont_remember_me();
		Auth::logout();
        return Response::redirect('/');
	}

}
