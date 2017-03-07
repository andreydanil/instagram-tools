<div class="wrap">
	<div class="section-dashboard">
		<div class="box box-solid section-dashborad-head">
            <div class="box-header with-border section-head">
                <i class="fa fa-instagram" aria-hidden="true"></i> <?=l('account-manage')?>
            </div>
        </div>
		<form class="formList">
			<div class="btn-group">
		        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal"><?=l('add-new')?></button>
		    </div>
	        <div class="btn-group right">
		        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?=l('action')?>
		            <span class="fa fa-caret-down"></span></button>
		        <ul class="dropdown-menu">
		            <li><a class="btnActionModule" data-action="active" href="javascript:void(0);"><?=l('active')?></a></li>
		            <li><a class="btnActionModule" data-action="disable" href="javascript:void(0);"><?=l('disable')?></a></li>
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
	                    <th><?=l('username')?></th>
	                    <th class="text-center"  style="width: 70px"><?=l('status')?></th>
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
	                    <td><?=$row->username?></td>
	                    <td class="text-center">
	                    	<?php if($row->status != 0){?>
	                        	<span class="text-success btnActionModuleItem" data-action="disable" style="font-size: 25px;"><i class="fa fa-toggle-on"></i></span>
	                        <?php }else{?>
	                        	<span class="text-danger btnActionModuleItem" data-action="active" style="font-size: 25px;"><i class="fa fa-toggle-off"></i></span>
	                        <?php }?>
	                    </td>
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