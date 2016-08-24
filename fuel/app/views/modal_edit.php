<?php
/**
 * Created by PhpStorm.
 * User: yam8511_li
 * Date: 2016/8/17
 * Time: 下午 02:10
 */
?>
<?php 
    $modal_id = 'modal_edit_'.$type.'_'.$msg->id; 
    $root = '/';
?>
<div id="<?= $modal_id ?>" class="w3-modal" style="z-index: 900;">
    <div class="w3-modal-content w3-animate-zoom w3-card-8">
        <header class="w3-container w3-purple">
            <span onclick="cancel('<?= $modal_id ?>')" class="w3-closebtn"><i class="fa fa-close"></i></span>
            <h2><i class="fa fa-commenting-o"></i>Editing...</h2>
        </header>
        <div class="w3-container">
            <?php if($type == 'msg'){ ?>
                <h3 class="w3-text-black"><?= $msg->title ?></h3>
            <?php } else { ?>
                <h3 class="w3-text-black">RE: <?= $msg->msgboard->title ?></h3>
            <?php } ?>

            <div class="w3-container">
                <?= Form::open(['name'=>'editForm','action'=>'/edit_'.$type, 'method'=>'post', 'class' => 'w3-form  w3-margin'], ['id' => $msg->id]) ?>
                <?= Form::csrf() ?>

                <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_name')); ?>
                <?= Form::label("暱稱",'name',['class'=>'w3-label']) ?>
                <?= Form::input('name', ($type == 'msg') ? $msg->user->username : $msg->user($msg->user_id)->username, ['class'=>'w3-input w3-hover-border-cyan','required', 'placeholder' => '您的暱稱', 'readonly']) ?>
                <?= Form::fieldset_close(); ?>

                <?= Form::fieldset_open(array('class' => 'w3-input-group w3-border-0', 'name' => 'group_message')); ?>
                <?= Form::label("訊息",',message',['class'=>'w3-label']) ?>
                <?= Form::textarea('message', $msg->message, ['rows' => 6, 'style' => 'resize: vertical;', 'class'=>'w3-input w3-border w3-hover-border-cyan', 'placeholder' => '輸入內容', 'required']) ?>
                <?= Form::fieldset_close(); ?>

                    <?php
                    $origin = str_replace("\r","",$msg->message);
                    $origin = str_replace("\n","<br>",$origin);
                    $origin = str_replace("'","\\'",$origin);
                    $origin = str_replace('"','\\"',$origin);
                    ?>
                    <button class="w3-btn w3-round-large w3-red"><i class='fa fa-check'></i></button>
                    <a class="w3-btn w3-round-large w3-teal" onclick="cancel('<?= $modal_id ?>', '<?= $origin ?>')"><i class="fa fa-close"></i></a>
                <?= Form::close() ?>
            </div>
        </div>
    </div>
</div>
