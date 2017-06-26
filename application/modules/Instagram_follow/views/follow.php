<div class="wrap">
	<div class="section-schedule">
		<div class="box box-solid wrap-box-post">
            <div class="box-header with-border section-head">
                <i class="fa fa-paper-plane text-blue"></i> <?=l('schedule-follow')?>
                <div class="btn-group btn-group-xs right">
			        <a href="<?=PATH."instagram/follow/log?type=follow"?>" class="btn btn-warning"><i class="fa fa-history" aria-hidden="true"></i> <?=l('history')?></a>
			    </div>
            </div>
        </div>
        <form class="formSchedule">
        	<ul class="schedule-errors"></ul>
        	<div class="schedule-list-accounts mt15">
	        	<div class="row">
	        		<?php if(!empty($accounts)){?>	
	        			<?php foreach ($accounts as $row) {?>
	        			<div class="item">
	        				<input type="checkbox" name="accounts[]" data-id="<?=$row->id?>" value="<?=$row->id."{-}".$row->username?>"/>
			                <i class="fa fa-user"></i>
			                <div class="text"><?=$row->username?></div>
			                <div class="check">
			                	<i class="fa fa-check"></i>
			                </div>
			        	</div>
			        	<?php }?>
			        	<div class="item white" data-toggle="modal" data-target="#myModal">
		        			<i class="fa fa-plus"></i>
		        			<div class="text"><?=l('add-new')?></div>
		        		</div>
	        		<?php }else{?>
	        			<div class="item-empty" data-toggle="modal" data-target="#myModal">
		        			<i class="fa fa-instagram"></i>
		        			<div class="text"><?=l('add-new-account')?></div>
		        		</div>
	        		<?php }?>
	        	</div>
	        </div>
			<div class="schedule-content">
				<div class="schedule-loading"></div>
				<div class="schedule-option">
					<div class="form-group col-md-12">
	                	<div class="row">
						    <div class="row">
							    <div class="form-group col-md-12 mb0">
						            <label class="head-title col-md-12 p0 fn"><i class="fa fa-hashtag"></i> <?=l('Hashtags')?></label>
						            <input type="hidden" name="tags" id="mySingleField" value="" >
						            <ul id="singleFieldTags"></ul>
							        <label class="head-title col-md-12 p0 fn"><i class="fa fa-bullseye"></i> <?=l('time-cronjob')?></label>
							        <select class="form-control" name="deplay">
	                                    <?php for ($i=1; $i <= 1440; $i++) { 
	                                    	if($i%5 == 0){
	                                    ?>
	                                        <option value="<?=$i*60?>" ><?=$i?> <?=l('minutes')?></option>
	                                    <?php }}?>
	                                </select>
							    </div>
							    <div class="form-group col-md-6 mb0 hide">
							        <label class="head-title col-md-12 p0 fn"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i> <?=l('maximum-follow')?></label>
							        <select class="form-control" name="maximum">
	                                    <?php for ($i=1; $i <= 20; $i++) { ?>
	                                        <option value="<?=$i?>" ><?=$i?></option>
	                                    <?php }?>
	                                </select>
							    </div>
						    </div>
	                	</div>
	                </div>
					<div class="btn-group right">
					    <button type="button" class="btn btn-success btn-flat btnSaveScheduleFollow"><i class="fa fa-clock-o" aria-hidden="true"></i> <?=l('schedule')?></button>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
			</div>
		</form>

		<form class="formList">
			<div class="schedule-list-posts">
				<div class="schedule-title">
					<i class="fa fa-list-ol" aria-hidden="true"></i> <?=l('review-your-scheduled-follow')?>
				</div>
				<div class="schedule-timeline">
					<div class="item-header pb0">
						<div class="schedule-check col-md-12 pr0">
							<div class="box-icheck">
			                  	<input type="checkbox" class="icheck checkAllSchedule" id="checkAllSchedule">
			                  	<label class="label-icheck" for="checkAllSchedule">&nbsp;</label>
			                </div>
			                <div class="btn-group right">
						        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?=l('action')?>
						            <span class="fa fa-caret-down"></span></button>
						        <ul class="dropdown-menu">
						            <li><a class="btnActionList" data-action="delete" href="javascript:void(0);"><?=l('delete')?></a></li>
						        </ul>
						    </div>
						</div>
						<div class="clearfix"></div>
					</div>
					<?php if(!empty($schedule)){
					foreach ($schedule as $row) {
						$spintax   = new Spintax();
						$status    = INSTAGRAM_STATUS($row->status);
					?>
					<div class="item" data-id="<?=$row->id?>">
						<div class="schedule-check col-md-1">
							<div class="box-icheck">
			                  	<input type="checkbox" name="id[]" class="icheck checkItemSchedule" id="checkItemSchedule-<?=$row->id?>" value="<?=$row->id?>">
			                  	<label class="label-icheck" for="checkItemSchedule-<?=$row->id?>">&nbsp;</label>
			                </div>
						</div>
						<div class="schedule-info col-md-5">
							<ul class="list-info">
								<li>
									<i class="fa fa-calendar"></i> <?=date("M d, y", strtotime($row->time_post))?><br/>
									<i class="fa fa-clock-o"></i> <?=date("h:i A", strtotime($row->time_post))?>
								</li>
								<li><i class="fa fa-user"></i> <?=$row->name?></li>
								<li class="border-none"><span class="btn-xs label-<?=$status->label?> btn-result"><?=$status->text?></span></li>
							</ul>
						</div>
						<div class="schedule-info col-md-5 bl0">
							<ul class="list-info">
								<li><i class="fa fa-bullseye"></i><?=l('time-cronjob')?>: <?=$row->deplay?> <?=l('seconds')?></li>
								<li class="border-none"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i> <?=l('maximum-follow')?>: <?=$row->maximum?></li>
							</ul>
						</div>
						<div class="schedule-option col-md-1 pr0">
							<a class="btnActionItem" data-action="delete" data-toggle="tooltip" title="<?=l('delete')?>" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i></a>
							<?php if($row->code != ""){?>
								<a href="https://www.instagram.com/p/<?=$row->code?>/" target="_blank" data-toggle="tooltip" title="<?=l('view')?>" ><i class="fa fa-eye" aria-hidden="true"></i></a>
							<?php }?>
						</div>
						<div class="clearfix"></div>
					</div>
					<?php }}else{?>
							<div class="schedule-empty"><?=l('empty')?></div>
						<?php }?>
					<div class="clearfix"></div>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		$('#singleFieldTags').tagit({
            singleField: true,
            singleFieldNode: $('#mySingleField')
        });
	});
</script>