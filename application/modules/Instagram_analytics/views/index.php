<div class="wrap">
	<div class="section-dashboard">
		<div class="box box-solid section-dashborad-head">
            <div class="box-header with-border section-head">
                <i class="fa fa-bar-chart" aria-hidden="true"></i> <?=l('analytics')?>
            </div>
        </div>
        <div class="schedule-analytics row">
	        <div class="schedule-loading"></div>
			<div class="report_posts"></div>
        </div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		Instagram.chart();
	});
</script> 