<div class="wrap">
	<div class="section-dashboard">
		<div class="box box-solid section-dashborad-head">
            <div class="box-header with-border section-head">
                <i class="fa fa-history" aria-hidden="true"></i> <?=l('history-follow')?>
            </div>
        </div>
		<form class="formList">
			<div class="form-inline" style="display:inline-block;">
			    <div class="form-group">
			        <select class="form-control" name="type">
			        	<option value=""><?=l('all-type')?></option>
			        	<option value="follow" <?=(get('type') == "follow")?"selected":""?> ><?=l('follow')?></option>
			        	<option value="followback" <?=(get('type') == "followback")?"selected":""?> ><?=l('follow-back')?></option>
			        	<option value="unfollow" <?=(get('type') == "unfollow")?"selected":""?> ><?=l('unfollow')?></option>
			        </select>
			    </div>
			    <?php if(!empty($accounts)){?>
			    <div class="form-group">
			        <select class="form-control" name="id">
			        	<option value=""><?=l('all-account')?></option>
			        	<?php foreach ($accounts as $row) {?>
			        		<option value="<?=$row->id?>" <?=(get('id') == $row->id)?"selected":""?> ><?=$row->username?></option>
			        	<?php }?>
			        </select>
			    </div>
			    <?php }?>
			    <button type="submit" class="btn btn-default"><?=l('submit')?></button>
			</div>
	        <div class="btn-group right">
		        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?=l('action')?>
		            <span class="fa fa-caret-down"></span></button>
		        <ul class="dropdown-menu">
		            <li><a class="btnActionModule" data-action="delete" data-confirm="<?=l('confirm-delete')?>" href="javascript:void(0);"><?=l('delete')?></a></li>
		        </ul>
		    </div>
		    <div class="clearfix"></div>
			<div class="table-responsive mt15">
	            <table class="table table-bordered table table-striped">
	                <tbody><tr>
	                    <th style="width: 10px">
	                    	<div class="box-icheck">
			                  	<input type="checkbox" class="icheck checkAllSchedule" id="checkAllSchedule">
			                  	<label class="label-icheck m0" for="checkAllSchedule">&nbsp;</label>
			                </div>
	            		</th>
	                    <th style="width: 10px"><?=l('no.')?></th>
	                    <th><?=l('type')?></th>
	                    <th><?=l('username')?></th>
	                    <th><?=l('your-account')?></th>
	                    <th><?=l('created')?></th>
	                    <th class="text-center" style="width: 90px"><?=l('option')?></th>
	                </tr>
	                <?php 
	                if(!empty($result)){
	                foreach ($result as $key => $row) {
	                ?>
	                <tr class="item" data-id="<?=$row->id?>">
	                	<td>
	                		<div class="box-icheck">
			                  	<input type="checkbox" name="id[]" class="icheck checkItemSchedule" id="checkItemSchedule-<?=$row->id?>" value="<?=$row->id?>">
			                  	<label class="label-icheck m0" for="checkItemSchedule-<?=$row->id?>">&nbsp;</label>
			                </div>
	                	</td>
	                    <td><?=((int)get('p')) + ($key+1)?></td>
	                    <td><?=$row->type?></td>
	                    <td><?=$row->name?></td>
	                    <td><?=$row->account_name?></td>
	                    <td><?=date("H:i:s d-m-Y", strtotime($row->created))?></td>
	                    <td class="text-center">
	                    	<div class="btn-group btn-group-sm">
	                      		<a href="javascript:void(0);"  data-confirm="<?=l('confirm-delete')?>" class="btn btn-default btnDelete btnActionModuleItem" data-action="delete"><i class="fa fa-trash-o"></i></a>
	                        </div>
	                    </td>
	                </tr>
	                <?php }}else{?>
	                <tr>
	                	<td class="text-center" colspan="8">
	                		Empty
	                	</td>
	                </tr>
	                <?php }?>
	            </tbody></table>
	            <div class="box-footer clearfix">
		        	<?=$this->pagination->create_links();?>
		        </div>
	        </div>
		</form>
	</div>
</div>