<div class="wrap">
	<div class="section-dashboard section-settings">
		
		<form role="form" method="POST" data-redirect="settings" enctype="multipart/form-data">
            <div class="box box-solid section-dashborad-head mb0">
	            <div class="box-header with-border section-head">
	                <i class="fa fa-tint"></i> <?=l('customization-options')?></h3>
	            </div>
	        </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="name"><?=l('website-name')?></label>
                    <input type="hidden" class="form-control" name="token" id="token" value="<?=$this->security->get_csrf_hash();?>">
                    <input type="text" class="form-control" name="title" value="<?=!empty($result)?$result->title:""?>">
                </div>
                <div class="form-group">
                    <label for="name"><?=l('website-description')?></label>
                    <textarea class="form-control" name="description"><?=!empty($result)?$result->description:""?></textarea>
                </div>
                <div class="form-group">
                    <label for="name"><?=l('website-keywords')?></label>
                    <input type="text" class="form-control" name="keywords" value="<?=!empty($result)?$result->keywords:""?>">
                </div>
                <div class="form-group">
                    <label for="file"><?=l('logo')?></label>
                    <div class="row">
                      <div class="col-xs-6 col-md-3">
                        <a href="#" class="thumbnail" style="background: #eee;">
                          <img src="<?=(!empty($result) && $result->logo != "")?BASE.$result->logo:BASE."assets/img/logo.png"?>" alt="" style="max-width: 200px;">
                        </a>
                    <input type="file" class="form-control" name="file" id="file">
                      </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="box box-solid section-dashborad-head mt15 mb0">
	            <div class="box-header with-border section-head">
	                <i class="fa fa-user"></i> <?=l('admin-options')?>
	            </div>
	        </div>
            <div class="box-body">
                <div class="form-group">
                    <label style="padding-right: 10px; position: relative; top: -10px;"><?=l("register")?></label>
                    <input type="radio" class="input-icheck" name="register" value="1" <?=(!empty($result) && $result->register == 1)?"checked":""?>> <span style="margin-right: 10px;"><?=l('yes')?></span>
                    <input type="radio" class="input-icheck" name="register" value="0" <?=(!empty($result) && $result->register == 0)?"checked":""?>> <?=l('no')?>
                </div>
                <div class="form-group">
                    <label style="padding-right: 10px; position: relative; top: -10px;"><?=l("automatically-active-user")?></label>
                    <input type="radio" class="input-icheck" name="auto_active_user" value="1" <?=(!empty($result) && $result->auto_active_user == 1)?"checked":""?>> <span style="margin-right: 10px;"><?=l('yes')?></span>
                    <input type="radio" class="input-icheck" name="auto_active_user" value="0" <?=(!empty($result) && $result->auto_active_user == 0)?"checked":""?>> <?=l('no')?>
                </div>
                <div class="form-group">
                    <label><?=l('timezone')?></label>
                    <select class="form-control" name="default_timezone">
	                    <?php foreach(tz_list() as $t) { ?>
					      	<option value="<?=$t['zone'] ?>" <?=(!empty($result) && $result->default_timezone == $t['zone'])?"selected":""?>>
					        	<?=$t['diff_from_GMT'] . ' - ' . $t['zone'] ?>
				      		</option>
					    <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><?=l('default-language')?></label>
                    <select class="form-control" name="default_language">
                        <?php if(!empty($lang))
                        foreach ($lang as $row) {
                        ?>
                        <option value="<?=$row?>" <?=(!empty($result) && $result->default_language == $row)?"selected":""?>><?=strtoupper($row)?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="form-group">
                    <label><?=l('add-new-language')?></label>
                    <div class="clearfix"></div>
                    <div class="input-group">
                        <input type="file" class="form-control" name="language" id="language">
                        <a href="<?=BASE?>language/en.xml" target="_blank" class="input-group-addon btn btn-demo-language"><i class="fa fa-info-circle"></i> <?=l('view-demo')?></a>
                  	</div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="box box-solid section-dashborad-head mt15 mb0">
	            <div class="box-header with-border section-head">
	                <i class="fa fa-sign-in"></i> <?=l('login-social-options')?>
	            </div>
	        </div>
            <div class="box-body">
            	<ul class="nav nav-tabs">
				    <li class="active"><a data-toggle="tab" href="#facebook"><i class="fa fa-facebook-official" aria-hidden="true"></i> Facebook</a></li>
				    <li><a data-toggle="tab" href="#google"><i class="fa fa-google" aria-hidden="true"></i> Google</a></li>
				    <li><a data-toggle="tab" href="#twitter"><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</a></li>
				</ul>

				<div class="tab-content">
				    <div id="facebook" class="tab-pane fade in active">
				        <div class="form-group">
		                    <label for="name"><?=l('app-id')?></label>
		                    <input type="text" class="form-control" name="facebook_id" value="<?=!empty($result)?$result->facebook_id:""?>">
		                </div>
		                <div class="form-group">
		                    <label for="name"><?=l('app-secret')?></label>
		                    <input type="text" class="form-control" name="facebook_secret" value="<?=!empty($result)?$result->facebook_secret:""?>">
		                </div>
				    </div>
				    <div id="google" class="tab-pane fade">
				        <div class="form-group">
		                    <label for="name"><?=l('client-id')?></label>
		                    <input type="text" class="form-control" name="google_id" value="<?=!empty($result)?$result->google_id:""?>">
		                </div>
		                <div class="form-group">
		                    <label for="name"><?=l('client-secret')?></label>
		                    <input type="text" class="form-control" name="google_secret" value="<?=!empty($result)?$result->google_secret:""?>">
		                </div>
				    </div>
				    <div id="twitter" class="tab-pane fade">
				        <div class="form-group">
		                    <label for="name"><?=l('consumer-key')?></label>
		                    <input type="text" class="form-control" name="twitter_id" value="<?=!empty($result)?$result->twitter_id:""?>">
		                </div>
		                <div class="form-group">
		                    <label for="name"><?=l('consumer-secret')?></label>
		                    <input type="text" class="form-control" name="twitter_secret" value="<?=!empty($result)?$result->twitter_secret:""?>">
		                </div>
				    </div>
				</div>
            </div>


            <div class="clearfix"></div>
            <div class="box box-solid section-dashborad-head mt15 mb0">
	            <div class="box-header with-border section-head">
	                <i class="fa fa-pencil-square-o"></i> <?=l('posting-options')?>
	            </div>
	        </div>
            <div class="box-body">
                <div class="form-group">
                    <label><?=l('default-maximum-account-instagram')?></label>
                    <select class="form-control" name="maximum_account">
                        <?php for ($i=0; $i <= 1000; $i++){ ?>
                        <option value="<?=$i?>" <?=(!empty($result) && $result->maximum_account == $i)?"selected":""?> ><?=$i?> <?=l('accounts')?></option>
                        <?php }?>
                    </select>
                </div>

                <div class="form-group">
                    <label><?=l('upload-max-size')?></label>
                    <select class="form-control" name="upload_max_size">
                        <?php for ($i=0; $i <= 1000; $i++){ ?>
                        <option value="<?=$i?>" <?=(!empty($result) && $result->upload_max_size == $i)?"selected":""?> ><?=$i?><?=l('m')?></option>
                        <?php }?>
                    </select>
                </div>

                <div class="form-group">
                    <label><?=l('default-delay')?></label>
                    <select class="form-control" name="default_deplay">
                        <?php foreach (deplay_time() as $i) {?>
                        <option value="<?=$i?>" <?=(!empty($result) && $result->default_deplay == $i)?"selected":""?> ><?=$i?> <?=l('seconds')?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="form-group">
                    <label><?=l('minimum-delay')?></label>
                    <select class="form-control" name="minimum_deplay">
                        <?php foreach (deplay_time() as $i) {?>
                        <option value="<?=$i?>" <?=(!empty($result) && $result->minimum_deplay == $i)?"selected":""?> ><?=$i?> <?=l('seconds')?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="box-footer mt15">
                <button type="submit" class="btn btn-primary"><?=l('submit')?></button>
            </div>
        </form>
	</div>
</div>