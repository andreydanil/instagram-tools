<div class="wrap">
	<div class="section-schedule">
		<div class="box box-solid wrap-box-post">
            <div class="box-header with-border section-head">
                <i class="fa fa-paper-plane text-blue"></i> <?=l('schedule-direct-messages')?>
            </div>
        </div>
        <form class="formSchedule">
        	<ul class="schedule-errors"></ul>
			<div class="schedule-content">
				<div class="schedule-loading"></div>
				<textarea placeholder="<?=l('write-something')?>" name="description"></textarea>
				<div class="schedule-option">
					<div class="form-group col-md-12">
	                	<div class="row">
						    <div class="row">
						    	<div class="form-group col-md-6 mb0">
							        <label class="head-title col-md-12 p0 fn"><i class="fa fa-clock-o"></i> <?=l('time-direct-message')?></label>
							        <input type="text" class="form-control date_range" name="time_post">
							    </div>

							    <div class="form-group col-md-6 mb0">
							        <label class="head-title col-md-12 p0 fn"><i class="fa fa-bullseye"></i> <?=l('deplay')?></label>
							        <select class="form-control" name="deplay">
	                                    <?php foreach (deplay_time() as $value) {?>
	                                        <?php if(MINIMUM_DEPLAY <= $value){?>
	                                        <option value="<?=$value?>" <?=(DEFAULT_DEPLAY == $value)?"selected":""?>><?=$value?> <?=l('seconds')?></option>
	                                        <?php }?>
	                                    <?php }?>
	                                </select>
							    </div>
						    </div>
	                	</div>
	                </div>
					<div class="btn-group right">
					    <button type="button" class="btn btn-success btn-flat btnSaveScheduleDirectMessage"><i class="fa fa-clock-o" aria-hidden="true"></i> <?=l('schedule')?></button>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
			</div>

			<div class="schedule-list-accounts mt15">
	        	<div class="row">
	        		<?php if(!empty($accounts)){?>	
	        			<?php foreach ($accounts as $row) {?>
	        			<div class="item">
	        				<input type="radio" name="account" data-id="<?=$row->id?>" value="<?=$row->id."{-}".$row->username?>"/>
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
	        <div class="btn-group left">
	        	<div class="form-inline right" style="display:inline-block;">
				    <div class="form-group">
				    	<button type="button" class="btn btn-flat btnSearchScrap" data-type="following"><?=l('get-self-users-following')?></button>
				    	<button type="button" class="btn btn-flat btnSearchScrap" data-type="followers"><?=l('get-self-users-followers')?></button>
				    </div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="list-search-feed mt15">
				
			</div>
		</form>

		<form class="formList">
			<div class="schedule-list-posts">
				<div class="schedule-title">
					<i class="fa fa-list-ol" aria-hidden="true"></i> <?=l('review-your-scheduled-direct-message')?>
				</div>
				<div class="schedule-timeline">
					<div class="item-header pb0">
						<div class="schedule-check col-md-12">
							<div class="box-icheck">
			                  	<input type="checkbox" class="icheck checkAllSchedule" id="checkAllSchedule">
			                  	<label class="label-icheck" for="checkAllSchedule">&nbsp;</label>
			                </div>
			                <div class="btn-group right">
						        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?=l('action')?>
						            <span class="fa fa-caret-down"></span></button>
						        <ul class="dropdown-menu">
						        	<?php if($row->status == 1){?>
						            <li><a class="btnActionList" data-action="cancel" href="javascript:void(0);"><?=l('cancel')?></a></li>
						            <?php }?>
						            <li><a class="btnActionList" data-action="delete" href="javascript:void(0);"><?=l('delete')?></a></li>
						        </ul>
						    </div>
						</div>
						<div class="clearfix"></div>
					</div>
					<?php if(!empty($schedule)){
					$date = "";
					foreach ($schedule as $row) {
						$spintax   = new Spintax();
						$time_post = date("Y-m-d", strtotime($row->time_post));
						$status    = INSTAGRAM_STATUS($row->status);
					?>
					<?php if($date != $time_post){
						$date = $time_post;
					?>
					<div class="schedule-timeline-title"><i class="fa fa-circle" aria-hidden="true"></i> <span><?=date('l j F, Y', strtotime($date))?></span></div>
					<?php }?>
					<div class="item" data-id="<?=$row->id?>">
						<div class="schedule-check col-md-1">
							<div class="box-icheck">
			                  	<input type="checkbox" name="id[]" class="icheck checkItemSchedule" id="checkItemSchedule-<?=$row->id?>" value="<?=$row->id?>">
			                  	<label class="label-icheck" for="checkItemSchedule-<?=$row->id?>">&nbsp;</label>
			                </div>
						</div>
						<div class="schedule-info col-md-3">
							<ul class="list-info">
								<li>
									<i class="fa fa-calendar"></i> <?=date("M d, y", strtotime($row->time_post))?><br/>
									<i class="fa fa-clock-o"></i> <?=date("h:i A", strtotime($row->time_post))?>
								</li>
								<li><i class="fa fa-user"></i> <?=$row->name?></li>
								<li><i class="fa fa-comment"></i> <?=ucfirst($row->type)?></li>
								<li class="border-none"><span class="btn-xs label-<?=$status->label?> btn-result"><?=$status->text?></span></li>
							</ul>
						</div>
						<div class="schedule-text col-md-7">
							<?=$spintax->process($row->description)?><br/>
							<span class="small">To <a href="https://www.instagram.com/<?=$row->title?>/" target="_blank"><?=$row->title?></a></span>	
						</div>
						<div class="schedule-option col-md-1 pr0">
							<a class="btnActionItem" data-action="delete" data-toggle="tooltip" title="<?=l('delete')?>" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i></a>
							<?php if($row->title != ""){?>
								<a href="https://www.instagram.com/<?=$row->title?>/" target="_blank" data-toggle="tooltip" title="<?=l('view')?>" ><i class="fa fa-eye" aria-hidden="true"></i></a>
							<?php }?>
						</div>
						<div class="clearfix"></div>
					</div>
					<?php }}else{?>
						<div class="schedule-empty"><?=l('empty')?></div>
					<?php }?>
				</div>
			</div>
		</form>
	</div>
</div>