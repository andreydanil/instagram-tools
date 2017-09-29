<div class="header">
	<div class="header_top">
		<div class="wrap">
			<div class="logo">
				<a href="<?=PATH?>"><img src="<?=LOGO?>" alt="" data-pin-nopin="true"></a>
			</div>
			<div class="btn-group btn-group-sm right btn-language">
		        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?=strtoupper(LANGUAGE)?>
		            <span class="fa fa-caret-down"></span>
		        </button>
		        <ul class="dropdown-menu">
		        	<?php if(!empty($lang))
                    foreach ($lang as $row) {
                    ?>
                    <li><a class="<?=(LANGUAGE == $row)?"active":""?>" href="<?=PATH?>language?lang=<?=$row?>"><?=strtoupper($row)?></a></li>
                    <?php }?>
		        </ul>
		    </div>
			<?php if(session('uid')){?>
			<div class="menu">
				<div class="mb-icon-menu"><i class="fa fa-bars"></i></div>
			    <ul class="menu-main">
			    	
			    	<li><a href="<?=PATH?>dashboard" title=""><?=l('dashboard')?></a></li>
			    	<li><a href="#" title=""><?=l('instagram-tools')?> <i class="fa fa-caret-down" aria-hidden="true"></i></a>
			    		<ul class="sub-menu">
			    			<li><a href="<?=PATH."schedule"?>"><?=l('auto-posts')?></a></li>
			    			<li><a href="<?=PATH."instagram/direct-message"?>"><?=l('auto-direct-message')?></a></li>
			    			<li><a href="<?=PATH."instagram/comment"?>"><?=l('auto-comments')?></a></li>
			    			<li><a href="<?=PATH."instagram/like"?>"><?=l('auto-likes')?></a></li>
			    			<li><a href="<?=PATH."instagram/follow"?>"><?=l('auto-follow')?></a></li>
			    			<li><a href="<?=PATH."instagram/followback"?>"><?=l('auto-follow-back')?></a></li>
			    			<li><a href="<?=PATH."instagram/unfollow"?>"><?=l('auto-unfollow')?></a></li>
			    			<li><a href="<?=PATH."instagram/search"?>"><?=l('search')?></a></li>
			    		</ul>
			    	</li>
			    	<li><a href="<?=PATH."instagram/analytics"?>" title=""><?=l('analytics')?></a></li>
			    	<li><a href="<?=PATH."instagram/account"?>" title=""><?=l('account-manage')?></a></li>

			    	<?php if(session('admin') == 1){?>
			    	<li><a href="#" title=""><?=l('admin')?> <i class="fa fa-caret-down" aria-hidden="true"></i></a>
			    		<ul class="sub-menu">
			    			<li><a href="<?=PATH."users"?>" title=""><?=l('user-manage')?></a></li>
			    			<li><a href="<?=PATH."settings"?>" title=""><?=l('settings')?></a></li>
			    		</ul>
			    	</li>
			    	
			    	
			    	<?php }?>
		    		<li class="btn-profile"><a href="#" title=""><?=l('Hi,')?> <?=session('fullname')?></a>
		    			<ul>
			    			<li>
			    				<i class="fa fa-caret-up" aria-hidden="true"></i>
			    				<a href="<?=PATH."users/profile"?>"><i class="fa fa-user" aria-hidden="true"></i> <?=l('profile')?></a>
			    			</li>
			    			<li class="li-last">
			    				<a href="<?=PATH."logout"?>"><i class="fa fa-sign-out" aria-hidden="true"></i> <?=l('logout')?></a>
			    			</li>
		    			</ul>
		    		</li>
				</ul>
			</div>
			<?php }?>
		</div>
	</div>
</div>
<div class="header-margin"></div>