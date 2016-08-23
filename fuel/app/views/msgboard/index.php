<?php
/**
 * Created by PhpStorm.
 * User: zoular_li
 * Date: 2016/8/9
 * Time: 上午 10:38
 */

?>
<div class="w3-container w3-margin">
    <?= HTML::anchor('/add', '<i class="fa fa-plus"></i>', ['title' =>'留話', 'class' => 'w3-btn w3-btn-floating  w3-teal']); ?>
    <?php if($login){ ?>
        <?= HTML::anchor('/logout', '<i class="fa fa-sign-out"></i>', ['title' =>'登出', 'class' => 'w3-btn w3-btn-floating  w3-blue']); ?>
    <?php }else{ ?>
        <a class="w3-btn w3-btn-floating  w3-pink" title="加入我們" onclick="document.getElementById('modal_register').style.display='block'"><i class="fa fa-user-plus "></i></a>
        <a class="w3-btn w3-btn-floating  w3-blue" title="登入" onclick="document.getElementById('modal_login').style.display='block'"><i class="fa fa-sign-in"></i></a>
    <?php } ?>
</div>

<?php $index = 0; foreach($msgs as $msg): ?>
    <div class=" w3-container w3-margin-bottom  w3-leftbar  <?= $style[($index++) % 3] ?>">
        <div class="w3-container">
            <!-- 留言標題 -->
            <h2><b><?= $msg->title ?></b></h2>
            <!-- 留言人 -->
            <?php if($customer = Model_User::find($msg->user_id)) { ?>
                <?= HTML::anchor('/view/'.$customer->id, $customer->username, ['style' => 'text-decoration:none;', 'class' => 'w3-text-blue']) ?>
            <?php } else { ?>
                <a style="text-decoration: none;" class="w3-text-blue " >Guest</a>
            <?php } ?>
            <!-- 留言日期 -->
            <br><span class="w3-text-grey w3-small">留言日期: <?= Date::forge($msg->created_at)->format("%Y-%m-%d %H:%M:%S"); ?></span>
            <br><span class="w3-text-grey w3-small">更新日期: <?= $msg->updated_at ? Date::forge($msg->updated_at)->format("%Y-%m-%d %H:%M:%S") : '' ?> </span>
            <!-- 留言訊息 -->
            <p><?= nl2br($msg->message) ?></p>
            <?php if($msg->upload): ?>
            <div class="w3-container w3-margin">
                <?= Asset::img('/uploads/'.$msg->upload->saved_as, ['alt' => $msg->upload->name, 'class' => 'w3-round']) ?>
            </div>
            <?php endif; ?>
            <!-- 修改&刪除按鈕 -->
            <?php if($login && ($msg->user_id == $user->id)): ?>
                <a class="w3-btn-floating  w3-purple" title="修改" onclick="show('modal_edit_msg_<?= $msg->id ?>')"><i
                            class="fa fa-pencil"></i></a>
                <?= View::forge('modal_edit', ['type' => 'msg', 'msg' => $msg]) ?>
            <?php endif; ?>
            <?php if($login && ($msg->user_id == $user->id || $user->group == 2)): ?>
                <a class="w3-btn-floating  w3-red" title="刪除" onclick="show('modal_delete_msg_<?= $msg->id ?>')"><i class="fa fa-trash"></i></a>
                <?= View::forge('modal_delete', ['type' => 'msg', 'msg' => $msg ]) ?>
            <?php endif; ?>
        </div>
        <!-- 回覆留言 -->
        <div class="w3-border-top w3-border-teal w3-padding">
            <?php if($login) { ?>
            <?= View::forge('reply/index', ['login'=>$login, 'user' => $user, 'msg'=>$msg, 'bg' => $bg]) ?>
            <?php } else { ?>
            <?= View::forge('reply/index', ['login'=>$login, 'msg'=>$msg, 'bg' => $bg]) ?>
            <?php } ?>
        </div>
    </div>
<?php endforeach;  ?>
<?= Asset::js('modal.js') ?>

<?= View::forge('modal_register') ?>
<?= View::forge('modal_login') ?>
