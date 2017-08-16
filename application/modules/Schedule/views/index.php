<div class="wrap">
	<div class="section-schedule">
		<div class="box box-solid wrap-box-post">
            <div class="box-header with-border section-head">
                <i class="fa fa-paper-plane text-blue"></i> <?=l('schedule-posts')?>
                <div class="btn-group btn-group-xs right">
			        <a href="<?=PATH."instagram/save"?>" class="btn btn-warning"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?=l('save-posts-manage')?></a>
			    </div>
            </div>
        </div>
        <form class="formSchedule">
        	<ul class="schedule-errors"></ul>
        	<?php if(!empty($savepost)){?>
            <div class="form-group">
                <select class="form-control getSavePost">
                    <option value=""><?=l('post-list-saved')?></option>
                    <?php foreach ($savepost as $row){?>
                    <option value="<?=$row->id?>"><?=$row->name?></option>
                    <?php }?>
                </select>
            </div>
            <?php }?>

			<div class="schedule-content">
				<div class="schedule-loading"></div>
				<textarea placeholder="<?=l('write-something')?>" name="description"></textarea>
				<div class="schedule-option">
					<?php if(check_FFMPEG()){ ?>
					<div class="form-group">
	                    <input type="radio" class="input-icheck" checked="true" name="type" value="photo"> <span style="margin-right: 20px;">&nbsp;&nbsp;<i class="fa fa-camera-retro" aria-hidden="true"></i> <?=l('photo')?></span>
	                    <input type="radio" class="input-icheck" name="type" value="video"> &nbsp;&nbsp;<i class="fa fa-video-camera" aria-hidden="true"></i> <?=l('video')?>
	                </div>
	                <?php }?>
					<div class="input-group form-group mb15">
	                	<input type="text" class="form-control" name="media" checked="" placeholder="<?=l('enter-url-or-upload-image')?>">
	                    <span class="input-group-btn">
	                      <button type="button" class="btn btn-block btn-default dialog-upload"><i class="fa fa-camera" aria-hidden="true"></i> <?=l('add-photo')?></button>
	                    </span>
	              	</div>
	              	<div class="progress progress-xs progress-striped active schedule-progress">
                  		<div class="progress-bar progress-bar-success progress-post-now" style="width: 0%"></div>
                    </div>
                    <div class="btn-group left">
					    <button type="button" class="btn btn-default btn-flat btnSavePost"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?=l('save-post')?></button>
					</div>
					<div class="btn-group right">
					    <button type="submit" class="btn btn-success btn-flat btnPostNow"><i class="fa fa-paper-plane" aria-hidden="true"></i> <?=l('post-now')?></button>
					    <button type="button" class="btn btn-success btn-flat btnOpenSchedule"><i class="fa fa-clock-o" aria-hidden="true"></i> <?=l('schedule')?></button>
					</div>
					<div class="clearfix"></div>
					<div class="schedule-list-option">
	                    <div class="form-group col-md-12 mt15 bt-line">
	                    	<div class="row">
							    <div class="row">
							    	<div class="form-group col-md-6 mb0">
								        <label class="head-title col-md-12 p0 fn"><i class="fa fa-clock-o"></i> <?=l('time-post')?></label>
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

	                			<div class="col-md-6 col-sm-6">
	                    			<div class="row">
						                <div class="box-icheck">
						                  	<input type="checkbox" class="icheck" id="random-post-accounts">
						                  	<label class="label-icheck" for="random-post-accounts"> <?=l('random-post-accounts')?></label>
						                </div>
	                    			</div>
		                    	</div>
		                    	<div class="col-md-6 col-sm-6 prmobile0">
					                <div class="box-icheck">
					                  	<input type="checkbox" class="icheck" id="delete-after-finished">
					                  	<label class="label-icheck" for="delete-after-finished"> <?=l('delete-schedule-after-finished')?></label>
					                </div>
		                    	</div>
	                    	</div>
	                    </div>
	                    <div class="form-group col-md-12 bt-line">
	                    	<div class="row">
	                    		<div class="box-icheck">
				                  	<input type="checkbox" class="icheck" id="repeat-post">
				                  	<label class="label-icheck" for="repeat-post"> <?=l('repeat-post')?></label>
				                </div>
		                        <div class="row">
					                <div class="clearfix"></div>
		                            <div class="col-md-6">
		                                <label class="col-md-12 p0 fn"><?=l('repeat')?></label>
		                                <select class="form-control" name="repeat_time">
		                                    <option value="86400"><?=l('one-per-day')?></option>
                                            <option value="172800"><?=l('every-two-days')?></option>
                                            <option value="259200"><?=l('every-three-days')?></option>
                                            <option value="345600"><?=l('every-four-days')?></option>
                                            <option value="432000"><?=l('every-five-days')?></option>
                                            <option value="518400"><?=l('every-six-days')?></option>
                                            <option value="604800"><?=l('once-per-week')?></option>
		                                </select>
		                            </div>
		                            <div class="col-md-6">
		                                <label class="col-md-12 p0 fn"><?=l('end-day')?></label>
		                                <input type="text" class="form-control date_range_only_day" name="repeat_end">
		                            </div>
		                        </div>
	                    	</div>
	                    </div>
						<div class="btn-group right">
						    <button type="button" class="btn btn-primary btn-flat btnSaveSchedule"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?=l('save-schedule')?></button>
						</div>
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
	        	<div class="clearfix"></div>
	        </div>
        </form>

        <form class="formList">
			<div class="schedule-list-posts">
				<div class="schedule-title">
					<i class="fa fa-list-ol" aria-hidden="true"></i> <?=l('peview-your-schedule-post')?>
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
						            <li><a class="btnActionList" data-action="cancel" href="javascript:void(0);"><?=l('cancel')?></a></li>
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
					<div class="schedule-timeline-title"><i class="fa fa-circle" aria-hidden="true"></i> <span><?=date('l j F, Y')?></span></div>
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
								<li><i class="fa fa-camera-retro"></i> <?=ucfirst($row->type)?></li>
								<li class="border-none" ><span class="btn-xs label-<?=$status->label?> btn-result" data-toggle="tooltip" title="<?=$row->message_error?>" ><?=$status->text?></span></li>
							</ul>
						</div>
						<?php if($row->type == "photo"){?>
							<div class="schedule-text col-md-5"><?=$spintax->process($row->description)?></div>
							<div class="schedule-image col-md-2 p0">
								<img src="<?=$spintax->process($row->image)?>" title="" alt="">
							</div>
						<?php }else{?>
							<div class="schedule-text col-md-7"><?=$spintax->process($row->description)?></div>
						<?php }?>
						<div class="schedule-option col-md-1 pr0">
							<?php if($row->status == 1){?>
							<a class="btnActionItem" data-action="cancel" data-toggle="tooltip" title="<?=l('cancel')?>" href="javascript:void(0);"><i class="fa fa-stop" aria-hidden="true"></i></a>
							<?php }?>
							<?php if($row->status != 1 && $row->status != 4){?>
							<a class="btnActionItem" data-action="repost" data-toggle="tooltip" title="<?=l('repost')?>" href="javascript:void(0);"><i class="fa fa-refresh" aria-hidden="true"></i></a>
							<?php }?>
							<?php if($row->code != ""){?>
								<a href="https://www.instagram.com/p/<?=$row->code?>/" target="_blank" data-toggle="tooltip" title="<?=l('view')?>" ><i class="fa fa-eye" aria-hidden="true"></i></a>
							<?php }?>
							<a class="btnActionItem" data-action="delete" data-toggle="tooltip" title="<?=l('delete')?>" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i></a>
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

<!-- Modal -->
<div class="modal fade" id="modal-save" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-owner">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?=l('title')?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control save_title"/>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-modal-save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?=l('save')?></button>
            </div>
        </div>
    </div>
</div>