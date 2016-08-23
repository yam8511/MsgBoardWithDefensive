<?php

class Controller_Reply extends Controller_Template
{
	public function before() {
		parent::before();

        if(!Auth::check()) {
            Session::set_flash('warning', '請先登入');
            return Response::redirect_back('/');
        }
	}

	public function post_addReply()
	{
		$message = Input::post('message');
        $msg_id = Input::post('msg_id');

		if ($message == '') {
            Session::set_flash('failed', '回覆內容不能空白');
            return redirect_back('/');
        }

        $msg = Model_Msgboard::find($msg_id);
        if(!$msg) {
            Session::set_flash('failed', '回覆發生錯誤');
            return redirect_back('/');
        }

        $rpl = new Model_Reply();
        $rpl->message = $message;
        $rpl->msgboard_id = $msg->id;
        $rpl->user_id = Auth::get('id');

        $msg->replies[] = $rpl;
        
        if(!$msg->save()) {
            Session::set_flash('failed', '回覆發生錯誤');
            return Response::redirect_back('/');
        }
        Session::set_flash('success', '回覆成功');
        return Response::redirect_back('/');
	}

	public function post_editReply()
	{
        $id = Input::post('id');
        $message = Input::post('message');
        $user_id = Auth::get('id');
        
        $msg = Model_Reply::find($id);
        if($msg) {
            if($user_id != $msg->user_id) {
                Session::set_flash('failed', '這不是你的回覆!');
                return Response::redirect_back('/');
            }

            $origin_msg = preg_replace('/\s(?=)/', '', trim($msg->message));
            $new_msg = preg_replace('/\s(?=)/', '', trim($message));

            if($origin_msg == $new_msg) {
                return Response::redirect('/');
            }

            $msg->message = $message;
            $msg->save();
            Session::set_flash('success', 'Reply 編輯成功');
        } else {
            Session::set_flash('failed', 'Reply 編輯錯誤');
        }
        return Response::redirect_back('/');
	}

	public function post_deleteReply()
	{
        $id = Input::post('id');
        $user_id = Auth::get('id');

		$rpl = Model_Reply::find($id);
        if($rpl) {
        	if($user_id != $rpl->user_id) {
                Session::set_flash('failed', '這不是你的回覆!');
                return Response::redirect_back('/');
            }
            if($rpl->delete()) {
                Session::set_flash('success', 'Reply 刪除成功');
                return Response::redirect_back('/');
            }
        }
        Session::set_flash('failed', 'Reply 刪除失敗');
        Response::redirect_back('/');
	}

}
