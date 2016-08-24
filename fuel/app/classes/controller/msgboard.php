<?php

require "CaptchasDotNet.php";

class Controller_Msgboard extends Controller_Template
{
	public function before(){
        parent::before();

        $this->template->login = Auth::check();
    }

	public function get_index()
	{
		$msgs = Model_Msgboard::find('all',[
            'order_by' => [
                'updated_at' => 'desc',
                'created_at' => 'desc',
            ]
        ]);
        $style = ['w3-pale-blue w3-border-blue', 'w3-pale-green w3-border-teal', 'w3-pale-yellow w3-border-yellow'];
        $bg = ['w3-black', 'w3-white'];

		$data = ['login' => Auth::check(), 'msgs' => $msgs, 'style' => $style, 'bg' => $bg];
        if (Auth::check()) {
            $data['user'] = Model_User::find(Auth::get('id'));
        }
		$this->template->title = '留言板';
		$this->template->content = View::forge('msgboard/index', $data);
	}

	public function get_add()
	{
        $captchas = new CaptchasDotNet ('demo', 'secret');

		$data = ['login' => Auth::check() , 'captchas' => $captchas ];
        $this->template->title = '留下訊息...';
        $this->template->content = View::forge('msgboard/add', $data);
	}

    public function post_add()
    {
        $captchas = new CaptchasDotNet ('demo', 'secret');
        $random_string = Input::post('random');
        $password = Input::post('captcha');

        $title = Security::xss_clean(Input::post('title'));
        $message = Security::xss_clean(Input::post('message'));

        Session::set_flash('title', $title);
        Session::set_flash('message', $message);

        // Check the random string to be valid and return an error message
        // otherwise.
        if (!$captchas->validate ($random_string))
        {
            Session::set_flash('failed', '網站系統錯誤');
            return Response::redirect('/add');
        }
        // Check, that the right CAPTCHA password has been entered and
        // return an error message otherwise.
        elseif (!$captchas->verify ($password))
        {
            Session::set_flash('failed', '驗證碼錯誤');
            return Response::redirect('/add');
        }

        $msgboard = new Model_Msgboard();
        $msgboard->title = $title;
        $msgboard->message = $message;

        // Auth
        if(Auth::check()) {
            $msgboard->user_id = Auth::get('id');
            $msgboard->user = Model_User::find(Auth::get('id'));
        } else {
            $msgboard->user_id = 0;
        }
            // 自訂此上傳的配置
            $config = array(
                'path' => './assets/img/uploads',
                'randomize' => true,
                'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
            );

            // 處理 $_FILES 中上傳的檔案
            Upload::process($config);

            if(Upload::get_files(0)){


                // 如果有任何有效檔案
                if (Upload::is_valid())
                {
                    Upload::save();

                    $file = Upload::get_files(0);
                    // 呼叫一個模型方法來更新資料庫
                    $upload = new Model_Upload();
                    $upload->name = $file['name'];
                    $upload->extension = $file['extension'];
                    $upload->saved_as = $file['saved_as'];
                    $upload->saved_to = $file['saved_to'];
                    $msgboard->upload = $upload;
                }
                else{
                    Session::set_flash('failed','上傳圖片有誤');
                    return Response::redirect('/add');
                }
            }

        if($msgboard->save()) {
            Session::set_flash('success','Message 新增成功');
            Response::redirect_back('/');
        } else {
            Session::set_flash('failed','Message 新增失敗');
            Response::redirect_back('/');
        }
    }

	public function post_editMessage()
	{
        if(!Auth::check()) {
            Session::set_flash('warning', '請先登入');
            return Response::redirect_back('/');
        }

        // 檢查 CSRF 符記是否有效
        if ( ! \Security::check_token())
        {
            // CSRF 攻擊或過期的 CSRF 符記
            Session::set_flash('failed','編輯失敗，驗證無效，請重新編輯');
            return Response::redirect_back('/');
        }

        $id = Security::xss_clean(Input::post('id'));
        $message = Security::xss_clean(Input::post('message'));
        
        $msg = Model_Msgboard::find($id);
        if($msg) {
            if(Auth::get('id') != $msg->user_id) {
                Session::set_flash('failed', '這不是你發的文!');
                return Response::redirect_back('/');
            }

            $origin_msg = preg_replace('/\s(?=)/', '', trim($msg->message));
            $new_msg = preg_replace('/\s(?=)/', '', trim($message));

            if($origin_msg == $new_msg) {
                return Response::redirect('/');
            }

            $msg->message = $message;
            $msg->save();
            Session::set_flash('success', '編輯成功');
        } else {
            Session::set_flash('failed', '編輯錯誤');
        }
        Response::redirect_back('/');
	}

	public function post_deleteMessage()
	{
        // 檢查 CSRF 符記是否有效
        if ( ! \Security::check_token())
        {
            // CSRF 攻擊或過期的 CSRF 符記
            Session::set_flash('failed','刪除失敗，驗證無效，請重新刪除');
            return Response::redirect_back('/');
        }

        if(!Auth::check()) {
            Session::set_flash('warning', '請先登入');
            return Response::redirect_back('/');
        }

        $id = Security::xss_clean(Input::post('id'));
        $msg = Model_Msgboard::find($id);

        if($msg) {
            if(Auth::get('id') != $msg->user_id && Auth::get('group') != 2) {
                Session::set_flash('failed', '這不是你發的文!');
                return Response::redirect_back('/');
            }

            #先刪除圖片與解除關係
            $pic = $msg->upload;
            $msg->upload = null;
            if($pic){
                if(!File::delete($pic->saved_to.$pic->saved_as)) {
                Session::set_flash('failed','圖片刪除失敗');
                return Response::redirect('/');
                }
                $pic->delete();
            }
            
            #再刪除每個關聯回覆
            $replies = $msg->replies;
            unset($msg->replies);
            foreach ($replies as $reply) {
                $reply->delete();
            }

            #最後刪除留言
            if($msg->delete()){
                Session::set_flash('success','Message 刪除成功');
                return Response::redirect_back('/');
            }
        }
        Session::set_flash('failed','Message 刪除失敗');

        Response::redirect_back('/');
	}

	public function get_belongUser()
	{
        if(!Auth::check()) {
            Session::set_flash('warning', '請先登入');
            return Response::redirect_back('/');
        }

        $id = Auth::get('id');
		$msgs = Model_Msgboard::find('all',[
            'where' => ['user_id' => $id ],
            'order_by' => [
                'updated_at' => 'desc',
                'created_at' => 'desc',
            ]
        ]);

        $style = ['w3-pale-blue w3-border-blue', 'w3-pale-green w3-border-teal', 'w3-pale-yellow w3-border-yellow'];
        $bg = ['w3-black', 'w3-white'];

        $data = ['login' => Auth::check(), 'msgs' => $msgs, 'style' => $style, 'bg' => $bg];
        $data['user'] = Model_User::find($id);
        
        $this->template->title = '我的留言板';
        $this->template->content = View::forge('msgboard/index', $data);
	}

	public function get_view()
	{
        $id = Security::xss_clean($this->param('id'));
        if(!is_numeric($id)) {
            return Response::redirect_back('/');
        }
        $login = Auth::check();
        $auth_id = Auth::get('id');

        if($login && ($auth_id == $id)) {
            return Response::redirect('belong');
        }

        $person = Model_User::find($id);

        if(!$person) {
            Session::set_flash('failed', 'There is no anyone!');
            return Response::redirect('/');
        }        

        $msgs = Model_Msgboard::find('all',[
            'where' => ['user_id' => $person->id ],
            'order_by' => [
                'updated_at' => 'desc',
                'created_at' => 'desc',
            ]
        ]);


        $style = ['w3-pale-blue w3-border-blue', 'w3-pale-green w3-border-teal', 'w3-pale-yellow w3-border-yellow'];
        $bg = ['w3-black', 'w3-white'];

        $data = ['login' => $login, 'msgs' => $msgs, 'style' => $style, 'bg' => $bg];
        if($login) {
            $data['user'] = $person;
        }

        $this->template->title = $person->username.'的留言板';
        $this->template->content = View::forge('msgboard/index', $data);        

	}

    public function action_404()
    {
        $this->template->title = '此頁面不存在，看看史努比';
        $this->template->content = VIew::forge('msgboard/404');
    }

}
