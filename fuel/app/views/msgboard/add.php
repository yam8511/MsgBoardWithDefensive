<?php $root = '/'; ?>
<a class="w3-btn w3-btn-floating  w3-green" href="<?= $root ?>"><i class="fa fa-mail-reply"></i></a>
<?= Form::open(['name'=>'addMessage','action'=>'add','method'=>'post', 'enctype' => 'multipart/form-data', 'class' => 'w3-form w3-border w3-border-teal w3-margin']); ?>
    <label class="w3-label">標題</label>
    <input type="text" name="title" class="w3-input w3-hover-border-cyan" placeholder="輸入主題" required>
    <br>
    <label class="w3-label">訊息</label>
    <textarea name="message" class="auto-height w3-input w3-border w3-hover-border-cyan" style="resize:none;" placeholder="輸入內容" required></textarea>
    <br>
    <label class="w3-label">上傳圖片</label>
    <!-- 錯誤訊息 -->
	<?php if(Session::get_flash('failed')): ?>
	    <div class="w3-round w3-pale-red">
            <span onclick="this.parentElement.style.display='none'" class="w3-closebtn"><i class="fa fa-close"></i></span>
            <h3><i class="fa fa-frown-o"></i><?= Session::get_flash('failed') ?></h3>
        </div>
	<?php endif; ?>
    <input type="file" name="photo" value="" class="w3-input w3-border-0">
    <input type="submit" name="send" value="留言" class="w3-btn w3-teal w3-ripple">
<?= Form::close(); ?>