<h1>
    <?php echo \functions\LangHelper::translate('locked_password_required_title');?>
</h1>
<p>
  <?php echo \functions\LangHelper::translate('locked_password_required_content', get_option('admin_email'));?>
  <hr>
  <form method="get" action="">
    <input type="password" length="30" placeholder="Password" name="password"/>
    <input type="submit" value="<?php echo \functions\LangHelper::translate('locked_password_required_btn_caption');?>" class="btn btn-primary" />
  </form>
</p>
