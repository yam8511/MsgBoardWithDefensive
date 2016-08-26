<?php
/**
 * Created by PhpStorm.
 * User: yam8511_li
 * Date: 2016/8/10
 * Time: 下午 04:54
 */
?>

<div class="w3-container">
    <div class="w3-card-4">
        <?= Form::open(['name'=>'registerForm','action'=>'/register','method'=>'post', 'onsubmit' => 'return validate()', 'class' => 'w3-form w3-margin'], ['random' => $captchas->random()]) ?>
            <?= Form::csrf() ?>

            <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_captcha')); ?>
            <?= Form::label("我不是機器人",'captcha',['class'=>'w3-label']) ?>
            <?= Form::input('captcha', '', ['class' => 'w3-input w3-hover-border-cyan', 'require' ]) ?>
            <?= $captchas->image() ?>
            <?= Html::anchor('/register', '<i class="fa fa-refresh w3-spin"></i>', ['class' => 'w3-btn-floating
            w3-yellow'])
            ; ?>
            <?= Form::fieldset_close(); ?>

            <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_username')); ?>
            <?= Form::label("帳戶",'username',['class'=>'w3-label']) ?>
            <?= Form::input('username', Session::get_flash('username'), ['class'=>'w3-input w3-hover-border-cyan','placeholder'=>'您的帳戶名稱']) ?>
            <span id="hint_username" class="hint"><?= Session::get_flash('hint_username') ?></span>
            <?= Form::fieldset_close(); ?>

            <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_password')); ?>
            <?= Form::label("密碼",'password',['class'=>'w3-label']) ?>
            <?= Form::input('password', '', ['class'=>'w3-input w3-hover-border-cyan','type'=>'password', 'placeholder' => '輸入7~30個數字', 'maxLength' => 30, 'minLength' => 7]) ?>
            <?= Form::fieldset_close(); ?>

            <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_confirm')); ?>
            <?= Form::label("確認密碼",'confirm',['class'=>'w3-label']) ?>
            <?= Form::input('confirm', '', ['class'=>'w3-input w3-hover-border-cyan','type'=>'password', 'placeholder' => '再次輸入密碼', 'maxLength' => 30, 'minLength' => 7]) ?>
            <span id="hint_password" class="hint"></span>
            <?= Form::fieldset_close(); ?>

            <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_comfirm')); ?>
            <?= Form::label("E-mail",'email',['class'=>'w3-label']) ?>
            <?= Form::input('email', Session::get_flash('email'), ['class'=>'w3-input w3-hover-border-cyan','placeholder'=>'您的E-mail', 'type'=>'email']) ?>
            <span id="hint_email" class="hint"><?= Session::get_flash('hint_email') ?></span>
            <?= Form::fieldset_close(); ?>

            <?= Form::submit('send','註冊',['class'=>'w3-btn w3-pink w3-ripple']) ?>
        <?= Form::close(); ?>
    </div>
</div>

<?= Asset::js('validate.js') ?>