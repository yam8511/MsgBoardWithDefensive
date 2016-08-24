<div id="modal_login" class="w3-modal" style="z-index: 900;">
    <div class="w3-modal-content w3-animate-zoom w3-card-8">
        <header class="w3-container w3-blue">
            <span onclick="document.getElementById('modal_login').style.display='none'" class="w3-closebtn"><i class="fa fa-close"></i></span>
            <h2><i class="fa fa-user"></i>登入</h2>
        </header>
        <div class="w3-container">
            <?= Form::open(['name'=>'loginForm','action'=>'/login','method'=>'post', 'class' => 'w3-form  w3-margin']) ?>
            <?= Form::csrf() ?>

            <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_username')); ?>
            <?= Form::label("帳號",'username',['class'=>'w3-label']) ?>
            <?= Form::input('username', Session::get_flash('username'), ['class'=>'w3-input w3-hover-border-cyan','required', 'placeholder' => '輸入 帳戶名稱 或 Email']) ?>
            <?= Form::fieldset_close(); ?>

            <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_password')); ?>
            <?= Form::label("密碼",'password',['class'=>'w3-label']) ?>
            <?= Form::input('password', '', ['class'=>'w3-input w3-hover-border-cyan','required','type'=>'password', 'placeholder' => '輸入密碼', 'maxlength' => 30, 'minlength' => 7]) ?>
            <?= Form::fieldset_close(); ?>

            <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_remember')); ?>
            <?= Form::checkbox('remember', 1, Session::get_flash('remember'), ['class'=>'w3-check' ]) ?>
            <?= Form::label("記住我",'remember',['class'=>'w3-validate']) ?>
            <?= Form::fieldset_close(); ?>

            <?= Form::submit('login','登入',['class'=>'w3-btn w3-blue w3-ripple']) ?>
            <p class="hint" id="hint_login"></p>
            <?= Form::close(); ?>
        </div>
    </div>
</div>
