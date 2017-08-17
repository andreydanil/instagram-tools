<?php if(!empty($result) && $result->status == "ok" && !empty($result->items)){?>
<table class="table table-striped">
    <tbody>
	    <tr>
	    	<th>
	    		<div class="box-icheck">
                  	<input type="checkbox" class="icheck checkAllFeed" id="checkAllFeed">
                  	<label class="label-icheck m0" for="checkAllFeed">&nbsp;</label>
                </div>
	    	</th>
	        <th><?=l('type')?></th>
	        <th><?=l('photo')?></th>
	        <th><?=l('caption')?></th>
	        <th><?=l('likes')?></th>
	        <th><?=l('comments')?></th>
	        <th><?=l('option')?></th>
	    </tr>
	    <?php foreach ($result->items as $row) {
	    	$list_image = end($row->image_versions2);
	    	$image = $list_image->url;
	    ?>
	    <tr>
	    	<td>
	    		<div class="box-icheck">
                  	<input type="checkbox" class="icheck checkItemFeed" name="media_id[]" id="feed-<?=$row->code?>" value="<?=$row->id."{-}".$row->code?>">
                  	<label class="label-icheck m0" for="feed-<?=$row->code?>">&nbsp;</label>
                </div>
	    	</td>
	        <td><?=INSTAGRAM_TYPE($row->media_type)?></td>
	        <td>
	        	<div class="photo" style="background-image: url('<?=$image?>')"></div>
	        </td>
	        <td class="caption"><?=(is_object($row->caption)?$row->caption->text:"")?></td>
	        <td><?=$row->like_count?></td>
	        <td><?=$row->comment_count?></td>
	        <td>
	        	<div class="btn-group">
             	 	<a href="https://www.instagram.com/p/<?=$row->code?>" class="btn btn-default" target="_blank"><i class="fa fa-eye"></i></a>
                </div>
	        </td>
	    </tr>
	    <?php }?>
	</tbody>
</table>
<?php }else{?>
	<div class="schedule-empty aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"><?=l('empty')?></div>
<?php }?>