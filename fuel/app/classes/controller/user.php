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
		// 檢查 CSRF 符記是否有效
		if ( ! \Security::check_token())
		{
			// CSRF 攻擊或過期的 CSRF 符記
			Session::set_flash('failed','註冊失敗，驗證無效，請重新註冊');
			return Response::redirect('/register');
		}

		// 避免有意的特殊HTML標籤
		$username = Security::xss_clean(Input::post('username'));
		$password = Security::xss_clean(Input::post('password'));
		$email = Security::xss_clean(Input::post('email'));

		Session::set_flash('username', $username);
		Session::set_flash('email', $email);

		// 制定輸入限制
		$val = Validation::forge('register');
		$val->add_field('username', 'username', 'required');
		$val->add_field('password', 'password', 'required|min_length[7]|max_length[30]');
		$val->add_field('email', 'email', 'required|valid_email');
		$val->set_message('required', '不能為空');
		$val->set_message('valid_email', '無效');
		$val->set_message('min_length', '最少7碼');
		$val->set_message('max_length', '最多30碼');

		// 檢查輸入
		if (!$val->run())
		{
			// 驗證失敗
			$errorMsg = '';
			if($val->error_message('username')) {
				$errorMsg .= '帳號'.$val->error_message('username').'<br>';
			}
			if($val->error_message('password')) {
				$errorMsg .= '密碼'.$val->error_message('password').'<br>';
			}
			if($val->error_message('email')) {
				$errorMsg .= 'Email'.$val->error_message('email').'<br>';
			}

			Session::set_flash('failed', $errorMsg);
			return Response::redirect('/register');
		}

		// 創建會員
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
		// 檢查 CSRF 符記是否有效
		if ( ! \Security::check_token())
		{
			// CSRF 攻擊或過期的 CSRF 符記
			Session::set_flash('failed','登入失敗，驗證無效');
			return Response::redirect('/login');
		}

		// 避免有意的特殊HTML標籤
		$username = Security::xss_clean(Input::post('username'));
		$password = Security::xss_clean(Input::post('password'));
		$remember = Security::xss_clean(Input::post('remember'));

		Session::set_flash('username', $username);
		Session::set_flash('remember', $remember);

		// 進行登入動作
		if (Auth::login($username, $password)) {
			if($remember == 1) {
				Auth::remember_me();
			} else {
				Auth::dont_remember_me();
			}

			Session::set_flash('success','登入成功:)');
			return Response::redirect_back('/');
		} else {
			Session::set_flash('failed','登入失敗，請確認帳號密碼');
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
