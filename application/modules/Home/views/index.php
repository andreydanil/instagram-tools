<div class="section-1">
	<div class="wrap">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			<div class="section-title"><span><?=l('title-section-1')?></span></div>
			<div class="section-desc"><span><?=l('desc-section-1')?></span></div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="login-form">
                <ul class="nav nav-tabs">
                    <li class="active" style="<?=(REGISTER_ALLOWED == 0)?"width: 100%":""?>"><a data-toggle="tab" href="#loginFrom"><?=l('login')?></a></li>
                    <?php if(REGISTER_ALLOWED == 1){?>
                    <li><a data-toggle="tab" href="#registerForm"><?=l('register')?></a></li>
                    <?php }?>
                </ul>

                <div class="tab-content">
                    <div id="loginFrom" class="tab-pane fade in active">
                        <div class="col-md-12">
                            <form class="form-horizontal formLogin" role="form">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                        <input type="text" class="form-control" name="email" placeholder="<?=l('email')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-unlock"></i></span>
                                        <input type="password" class="form-control" name="password" placeholder="<?=l('password')?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 pl0"><div class="msg error"></div></div>
                                    <div class="col-md-4">
                                        <div class="form-group pull-right">                
                                            <button type="submit" class="btn btn-login"><i class="fa fa-sign-in"></i> <?=l('login')?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php if(REGISTER_ALLOWED == 1){?>
                    <div id="registerForm" class="tab-pane fade">
                        <div class="col-md-12">
                            <form class="form-horizontal formRegister" role="form">
                            	<div class="form-group">
                            		<div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    	<input type="text" class="form-control" name="fullname" placeholder="<?=l('fullname')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                	<div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    	<input type="text" class="form-control" name="email" placeholder="<?=l('email')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                	<div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-unlock"></i></span>
                                    	<input type="password" class="form-control" name="password" placeholder="<?=l('password')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                	<div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-unlock"></i></span>
                                    	<input type="password" class="form-control" name="repassword" placeholder="<?=l('re-password')?>">
                                    </div>
                                </div>
                                <div class="row">
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-8 pl0"><div class="msg-register error"></div></div>
                                    <div class="col-md-4">
                                        <div class="form-group pull-right">                
                                            <button type="submit" class="btn btn-register"><i class="fa fa-sign-in"></i> <?=l('register')?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php }?>
                    <div class="clearfix"></div>
                    <?php if((FACEBOOK_ID != "" && FACEBOOK_SECRET != "") || (GOOGLE_ID != "" && GOOGLE_SECRET != "") || (TWITTER_ID != "" && TWITTER_SECRET != "")){?>
                	<div class="login-social">
                		<fieldset>
							<legend><span><?=l('or-login-via')?></span></legend>
						</fieldset>
						<div class="list-social">
                            <?php if(FACEBOOK_ID != "" && FACEBOOK_SECRET != ""){?>
							    <a href="<?=FACEBOOK_GET_LOGIN_URL()?>" title=""><img src="<?=BASE?>assets/img/btn-facebook.png" title="" alt=""></a>
							<?php }?>
                            <?php if(GOOGLE_ID != "" && GOOGLE_SECRET != ""){?>
                                <a href="<?=GOOGLE_GET_LOGIN_URL()?>" title=""><img src="<?=BASE?>assets/img/btn-google.png" title="" alt=""></a>
                            <?php }?>
                            <?php if(TWITTER_ID != "" && TWITTER_SECRET != ""){?>
							    <a href="<?=TWITTER_GET_LOGIN_URL()?>" title=""><img src="<?=BASE?>assets/img/btn-twitter.png" title="" alt=""></a>
                            <?php }?>
						</div>
                	</div>
                    <?php }?>
                </div>
            </div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>