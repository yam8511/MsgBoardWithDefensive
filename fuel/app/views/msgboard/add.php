<?= Html::anchor('/', '<i class="fa fa-mail-reply"></i>', ['class' => 'w3-btn w3-btn-floating  w3-green']); ?>
<?= Form::open(['name'=>'addMessage','action'=>'add','method'=>'post', 'enctype' => 'multipart/form-data', 'class' => 'w3-form w3-border w3-border-teal w3-margin'], ['random' => $captchas->random()]); ?>
    <?= Form::csrf() ?>

    <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_title')); ?>
    <?= Form::label("標題",'title',['class'=>'w3-label']) ?>
    <?= Form::input('title', Session::get_flash('title'), ['class'=>'w3-input w3-hover-border-cyan','required', 'placeholder' => '輸入主題', 'required']) ?>
    <?= Form::fieldset_close(); ?>

    <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_message')); ?>
    <?= Form::label("訊息",',message',['class'=>'w3-label']) ?>
    <?= Form::textarea('message', Session::get_flash('message'), ['style' => 'resize: vertical;', 'class'=>'auto-height w3-input w3-border w3-hover-border-cyan', 'placeholder' => '輸入內容', 'required']) ?>
    <?= Form::fieldset_close(); ?>

    <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_photo')); ?>
    <?= Form::label("上傳圖片",',photo',['class'=>'w3-label']) ?>
    <?= Form::file('photo' , ['class' => 'w3-border-0']) ?>
    <?= Form::fieldset_close(); ?>

    <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_captcha')); ?>
    <?= Form::label("我不是機器人",'captcha',['class'=>'w3-label']) ?>
    <?= Form::input('captcha', '', ['class' => 'w3-input w3-hover-border-cyan', 'require' ]) ?>
    <?= $captchas->image() ?>
    <?= Html::anchor('/add', '<i class="fa fa-refresh w3-spin"></i>', ['class' => 'w3-btn-floating w3-yellow'])
; ?>
    <?= Form::fieldset_close(); ?>

    <?= Form::submit('send' , '留言', ['class' => 'w3-btn w3-teal w3-ripple']) ?>
<?= Form::close(); ?>