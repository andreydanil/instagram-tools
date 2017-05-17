<div class="col-md-6 box-chart">
    <div class="reports-title">
        <div class="name"><?=l('posts-by-day')?></div>
    </div>
    <div class="ajax-post-by-day"></div>
</div>
<div class="col-md-6 box-chart">
    <div class="reports-title">
        <div class="name"><?=l('posts-by-status')?></div>
    </div>
    <div class="ajax-post-by-status"></div>
</div>

<div class="col-md-6 box-chart">
    <div class="reports-title">
        <div class="name"><?=l('comments-by-day')?></div>
    </div>
    <div class="ajax-comment-by-day"></div>
</div>
<div class="col-md-6 box-chart">
    <div class="reports-title">
        <div class="name"><?=l('comments-by-status')?></div>
    </div>
    <div class="ajax-comment-by-status"></div>
</div>

<div class="col-md-6 box-chart">
    <div class="reports-title">
        <div class="name"><?=l('dm-by-day')?></div>
    </div>
    <div class="ajax-dm-by-day"></div>
</div>
<div class="col-md-6 box-chart">
    <div class="reports-title">
        <div class="name"><?=l('dm-by-status')?></div>
    </div>
    <div class="ajax-dm-by-status"></div>
</div>

<div class="col-md-6 box-chart">
    <div class="reports-title">
        <div class="name"><?=l('likes-by-day')?></div>
    </div>
    <div class="ajax-like-by-day"></div>
</div>
<div class="col-md-6 box-chart">
    <div class="reports-title">
        <div class="name"><?=l('likes-by-status')?></div>
    </div>
    <div class="ajax-like-by-status"></div>
</div>

<div class="clearfix"></div>
<div class="table-responsive mt15">
    <table class="table table-bordered table table-striped">
        <tbody>
            <tr>
                <th><?=l('type')?></th>
                <th style="width: 100px"><?=l('total')?></th>
            </tr>
            <tr>
                <td><?=l('follow')?></td>
                <td><?=$follow['follow']?></td>
            </tr>
            <tr>
                <td><?=l('follow-back')?></td>
                <td><?=$follow['followback']?></td>
            </tr>
            <tr>
                <td><?=l('unfollow')?></td>
                <td><?=$follow['unfollow']?></td>
            </tr>
        </tbody>
    </table>
    <div class="box-footer clearfix">
        <?=$this->pagination->create_links();?>
    </div>
</div>

<script type="text/javascript">
	$(function(){
    	Instagram.Highcharts({
    		element : '.ajax-post-by-day',
    		titlex  : 'datetime',
    		colorx  : '#ee5f15',
    		colory  : '#ee5f15',
            height  : 200,
    		name    : '<?=l('posts')?>',
    		data    : [<?=!empty($post_by_day)?$post_by_day:""?>]
    	});

    	Instagram.Highcharts({
            element : '.ajax-post-by-status',
            height  : 200,
            titlex  : 'datetime',
            type    : 'pie',
            name    : '<?=l('posts')?>',
            data    : [<?=!empty($post_by_status)?$post_by_status:""?>],
            dataLabels : {
                formatter: function() {
                    return this.y > 1 ? '<b>'+ this.point.name +':</b> '+ Instagram.round(this.percentage,2) +'%'  : null;
                }
            }
        });

        Instagram.Highcharts({
            element : '.ajax-comment-by-day',
            titlex  : 'datetime',
            colorx  : '#ee5f15',
            colory  : '#ee5f15',
            height  : 200,
            name    : '<?=l('comments')?>',
            data    : [<?=!empty($comment_by_day)?$comment_by_day:""?>]
        });

        Instagram.Highcharts({
            element : '.ajax-comment-by-status',
            height  : 200,
            titlex  : 'datetime',
            type    : 'pie',
            name    : '<?=l('comments')?>',
            data    : [<?=!empty($comment_by_status)?$comment_by_status:""?>],
            dataLabels : {
                formatter: function() {
                    return this.y > 1 ? '<b>'+ this.point.name +':</b> '+ Instagram.round(this.percentage,2) +'%'  : null;
                }
            }
        });

        Instagram.Highcharts({
            element : '.ajax-dm-by-day',
            titlex  : 'datetime',
            colorx  : '#ee5f15',
            colory  : '#ee5f15',
            height  : 200,
            name    : '<?=l('comments')?>',
            data    : [<?=!empty($dm_by_day)?$dm_by_day:""?>]
        });

        Instagram.Highcharts({
            element : '.ajax-dm-by-status',
            height  : 200,
            titlex  : 'datetime',
            type    : 'pie',
            name    : '<?=l('comments')?>',
            data    : [<?=!empty($dm_by_status)?$dm_by_status:""?>],
            dataLabels : {
                formatter: function() {
                    return this.y > 1 ? '<b>'+ this.point.name +':</b> '+ Instagram.round(this.percentage,2) +'%'  : null;
                }
            }
        });

        Instagram.Highcharts({
            element : '.ajax-like-by-day',
            titlex  : 'datetime',
            colorx  : '#ee5f15',
            colory  : '#ee5f15',
            height  : 200,
            name    : '<?=l('likes')?>',
            data    : [<?=!empty($like_by_day)?$like_by_day:""?>]
        });

        Instagram.Highcharts({
            element : '.ajax-like-by-status',
            height  : 200,
            titlex  : 'datetime',
            type    : 'pie',
            name    : '<?=l('likes')?>',
            data    : [<?=!empty($like_by_status)?$like_by_status:""?>],
            dataLabels : {
                formatter: function() {
                    return this.y > 1 ? '<b>'+ this.point.name +':</b> '+ Instagram.round(this.percentage,2) +'%'  : null;
                }
            }
        });
	});
</script>