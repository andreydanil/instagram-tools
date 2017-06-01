function Instagram(){
	var self= this;
    var w = $(window);
	var show_timeout = 0;
	this.init= function(){
        $(document).on("click", ".menu .mb-icon-menu", function(){
            _that = $(this);
            _main = $(".menu-main");
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                if(_that.hasClass('active')){
                    _main.fadeOut(200);
                    _that.removeClass("active");
                }else{
                    _main.fadeIn(200);
                    _that.addClass("active");
                }
                setTimeout(function(){
                    _that.removeClass('disable');
                },500);
            }
        });

        $(document).on("click", ".menu li", function(){
            _that = $(this);
            _main = _that.find(".sub-menu");
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                if(_that.hasClass('active')){
                    _main.fadeOut(200);
                    _that.removeClass("active");
                }else{
                    _main.fadeIn(200);
                    _that.addClass("active");
                }
                setTimeout(function(){
                    _that.removeClass('disable');
                },500);
            }
        });

        $('[data-toggle="tooltip"]').tooltip();


        $(window).resize(function () {
            var h = $(window).height();
            if ($('#elfinder').height() != h) {
                $('#elfinder').height(h).resize();
            }
        });

        $(document).on('click', '.schedule-list-accounts .item', function(){
            _that = $(this);
            _input = _that.find("input");
            if(_that.hasClass('active')){
                _that.removeClass('active');
                _input.prop('checked', false);
            }else{
                if(_input.attr("type") == "radio"){
                    $(".schedule-list-accounts .item").removeClass('active');
                    $(".schedule-list-accounts .item input").prop('checked', false);
                }
                _that.addClass('active');
                _input.prop('checked', true);
            }
        });

        $(document).on('click', '.checkAllFeed', function(){
            _that = $(this);
            if(_that.is(":checked")){
                $('.checkItemFeed').prop('checked', true);
            }else{
                $('.checkItemFeed').prop('checked', false);
            }
        });

        $(document).on('click', '.checkAllSchedule', function(){
            _that = $(this);
            if(_that.is(":checked")){
                $('.checkItemSchedule').prop('checked', true);
            }else{
                $('.checkItemSchedule').prop('checked', false);
            }
        });

        $(document).on('click', '.btnOpenSchedule', function(){
            if($('.schedule-list-option').hasClass('active')){
                $('.schedule-list-option').removeClass('active');
            }else{
                $('.schedule-list-option').addClass('active');
            }
            return false;
        });

        $('.date_range').appendDtpicker({
            "current": moment().format('YYYY-MM-DD HH:mm'),
            "minDate": moment().format('YYYY-MM-DD HH:mm'),
            "maxDate": moment().add(60, 'days').format('YYYY-MM-DD HH:mm'),
            "autodateOnStart": true
        });

        $('.date_range_only_day').appendDtpicker({
            "dateOnly": true,
            "current": moment().format('YYYY-MM-DD'),
            "minDate": moment().format('YYYY-MM-DD'),
            "autodateOnStart": true
        });

        $(document).mouseup(function (e){
            var container = $(".datepicker,.date_range,.date_range_only_day");
            if (!container.is(e.target) && container.has(e.target).length === 0){
                $(".datepicker").hide();
            }
        });

        $('.dialog-upload').click(function() {
            var _that = $(this);
            var fm = $('<div/>').dialogelfinder({
                url : BASE+'assets/plugins/elfinder/php/connector.php',
                lang : 'en',
                width : (w.width() > 840)?840:w.width() - 30,
                resizable: false,
                destroyOnClose : true,
                getFileCallback : function(files, fm) {
                    _that.parents(".form-group").find("input").val(PATH+files.url);
                },
                commandsOptions : {
                    getfile : {
                        oncomplete : 'close',
                        folders : false
                    }
                }
            }).dialogelfinder('instance');
        });

        $('.formUpdateAccount').submit(function(){
            _that = $(this);
            _data = _that.serialize();
            _data = _data + '&' + $.param({token:token});

            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                self.show_notice(system_processing, 'error', '.msg-add-new-account');
                $.post(PATH+'Instagram_account/ajax_update', _data, function(data){
                    if(data.st == 'success'){
                        self.show_notice(data.txt, data.st, '.msg-add-new-account');
                        setTimeout(function(){
                            window.location.reload();
                        },1000);
                    }else{
                        self.show_notice(data.txt, data.st, '.msg-add-new-account');
                    }
                    _that.removeClass('disable');
                }, 'json');
            }
            return false;
        });

        $(document).on("click", ".btn-modal-save", function(){
            $('.btnSavePost').trigger("click");
        });

        $('.btnSavePost').click(function(){
            _that = $(this);
            _form = _that.closest(".formSchedule");
            _data = _form.serialize();
            _title = $(".save_title").val();
            _data = _data + '&' + $.param({token:token, title: _title});
            $(".schedule-loading").fadeIn();
            if(!_form.hasClass('disable')){
                _form.addClass('disable');

                $.post(PATH + "Instagram_save/ajax_save_post", _data, function(result){
                    if(result.st == "error"){
                        $('.schedule-errors').html('');
                        $.each(result, function(key, value) {
                            if(key != "st"){
                                $('.schedule-errors').addClass('has-message error').slideDown(300);
                                $('.schedule-errors').append('<li>'+value["text"]+'</li>');
                            }
                        });
                        _form.removeClass('disable');
                    }else if(result.st == "title"){
                        _form.removeClass('disable');
                        $('#modal-save').modal('toggle');
                    }else{
                        $(".save_title").val("");
                        $('#modal-save').modal('hide');
                        $.each(result, function(key, value) {
                            if(key != "st"){
                                $('.schedule-errors').addClass('has-message success').slideDown(300);
                                $('.schedule-errors').append('<li>'+value["text"]+'</li>');
                            }
                        });

                        setTimeout(function(){
                            $('.schedule-errors').html('').slideUp(300);
                        },2500);
                    }

                    $(".schedule-loading").fadeOut();
                },'json');
            }

            return false;
        });

        $(document).on("change", ".getSavePost", function(){
            _that = $(this);
            _value = _that.val();
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(PATH + "Instagram_save/get_save_post", {token: token, value: _value}, function(data){
                    if(data != ""){
                        $("input[value="+data.type+"]").next("ins").trigger("click");
                        $("[name=description]").val(data.description);
                        $("[name=media]").val(data.image);
                    }
                    _that.removeClass('disable');
                },'json');
            }
        });

        $(document).on("click", ".btnSearchScrap", function(){
            _that = $(this);
            _type = _that.data("type");
            _user = $("input[name='user_comment_search']").val();
            _account = $('input[name=account]:checked').val();
            _account = (_account != undefined)?_account:0;
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                self.show_notice(system_processing, 'error', '.msg-search');
                $.post(PATH+'Instagram_search/ajax_search_feed', { token: token, account: _account, type: _type, user: _user}, function(result){
                    if(_account != 0 && result.length > 100){
                        $('.list-search-feed').html(result);
                        $('.schedule-errors').html("");
                        $('.schedule-errors').addClass('has-message error').slideUp(300);
                    }else{
                        $('.schedule-errors').html("");
                        $('.schedule-errors').addClass('has-message error').slideDown(300);
                        $('.schedule-errors').append('<li>'+result+'</li>');
                    }
                     _that.removeClass('disable');
                });
            }
        });

        //Schedule
        $(document).on('click', '.btnPostNow', function(){
            _that = $(this);
            _data = _that.closest("form").serialize();
            _data = _data + '&' + $.param({token:token});

            _accounts  = [];
            $('.schedule-list-accounts input').each(function(index,value){
                if($(this).is(':checked')){
                    _accounts.push($(this).val());
                }
            });
            
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(PATH + "Schedule/ajax_schedule_check", _data, function(result){
                    $('.schedule-errors').removeClass('has-message error').html("");
                    if(result.st == "error"){
                        $.each(result, function(key, value) {
                            if(key != "st"){
                                $('.schedule-errors').addClass('has-message error').slideDown(300);
                                $('.schedule-errors').append('<li>'+value["text"]+'</li>');
                            }
                        });
                        _that.removeClass('disable');
                    }else{  
                        self.post_now(_data, _accounts, 0);
                    }
                },'json');
            }

            return false;
        });

        $(document).on('click', '.btnSaveSchedule', function(){
            _that = $(this);
            _data = _that.closest("form").serialize();
            _data = _data + '&' + $.param({token:token});
            $(".schedule-loading").fadeIn();
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(PATH + "Schedule/ajax_schedule", _data, function(result){
                    $('.schedule-errors').removeClass('has-message error').html("");
                    if(result.st == "error"){
                        $.each(result, function(key, value) {
                            if(key != "st"){
                                $('.schedule-errors').addClass('has-message error').slideDown(300);
                                $('.schedule-errors').append('<li>'+value["text"]+'</li>');
                            }
                        });
                        _that.removeClass('disable');
                    }else{
                        $.each(result, function(key, value) {
                            if(key != "st"){
                                $('.schedule-errors').addClass('has-message success').slideDown(300);
                                $('.schedule-errors').append('<li>'+value["text"]+'</li>');
                            }
                        });
                        setTimeout(function(){
                            window.location.reload();
                        },1000);
                    }

                    _that.removeClass('disable');
                    $(".schedule-loading").fadeOut();
                },'json');
            }

            return false;
        });

        $(document).on('click', '.btnSaveScheduleDirectMessage', function(){
            self.schedule($(this), 'direct_message');
        });

        $(document).on('click', '.btnSaveScheduleComment', function(){
            self.schedule($(this), 'comment');
        });

        $(document).on('click', '.btnSaveScheduleLike', function(){
            self.schedule($(this), 'like');
        });

        $(document).on('click', '.btnSaveScheduleFollow', function(){
            self.schedule($(this), 'follow');
        });

        $(document).on('click', '.btnSaveScheduleFollowBack', function(){
            self.schedule($(this), 'followback');
        });

        $(document).on('click', '.btnSaveScheduleUnFollow', function(){
            self.schedule($(this), 'unfollow');
        });

        $(document).on('click', '.btnActionItem', function(){
            _that   = $(this);
            _action = _that.data("action");
            _id     = _that.parents(".item").data("id");
            _data   = $.param({token:token, action: _action, id: _id});
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(PATH + "Schedule/ajax_action_item", _data, function(result){
                    window.location.reload();
                },'json');
            }
            return false;
        });

        $(document).on('click', '.btnActionList', function(){
            _that   = $(this);
            _action = _that.data("action");
            _data   = _that.closest("form").serialize();
            _data   = _data + '&' + $.param({token:token, action: _action});
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(PATH + "Schedule/ajax_action_multiple", _data, function(result){
                    window.location.reload();
                    _that.removeClass('disable');
                },'json');
            }
            return false;
        });

        $(document).on('click', '.btnActionModuleItem', function(){
             _that   = $(this);
            _action = _that.data("action");
            _confirm = _that.data("confirm");
            if(_action == "delete"){
                var result = confirm(_confirm);
                if (!result) {
                    return false;
                }
            }

            _id     = _that.parents(".item").data("id");
            _data   = $.param({token:token, action: _action, id: _id});
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(PATH + module + "/ajax_action_item", _data, function(result){
                    window.location.reload();
                },'json');
            }
            return false;
        });

        $(document).on('click', '.btnActionModule', function(){
            _that   = $(this);
            _action = _that.data("action");
            _data   = _that.closest("form").serialize();
            _data   = _data + '&' + $.param({token:token, action: _action});
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(PATH + module + "/ajax_action_multiple", _data, function(result){
                    window.location.reload();
                    _that.removeClass('disable');
                },'json');
            }
            return false;
        });

        $('.formUpdate').submit(function(){
            _that = $(this);
            _data = _that.serialize();
            _data = _data + '&' + $.param({token:token});

            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                self.show_notice(system_processing, 'error');
                $.post(PATH + module + "/postUpdate", _data, function(data){
                    if(data.st == 'success'){
                        self.show_notice(data.txt, data.st, ".message");
                        setTimeout(function(){
                            window.location.assign(data.redirect);
                        },1000);
                    }else{
                        self.show_notice(data.txt, data.st, ".message");
                    }
                    _that.removeClass('disable');
                },'json');
            }

            return false;
        });

        $('.formProfile').submit(function(){
            _that = $(this);
            _data = _that.serialize();
            _data = _data + '&' + $.param({token:token});

            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                self.show_notice(system_processing, 'error');
                $.post(PATH + module + "/postProfile", _data, function(data){
                    if(data.st == 'success'){
                        self.show_notice(data.txt, data.st, ".message");
                        setTimeout(function(){
                            window.location.assign(data.redirect);
                        },1000);
                    }else{
                        self.show_notice(data.txt, data.st, ".message");
                    }
                    _that.removeClass('disable');
                },'json');
            }

            return false;
        });

        $(document).on('click', '.btnFollowItem', function(){
            _that     = $(this);
            _action   = _that.data("action");
            _id       = _that.data("id");
            _username = _that.data("username");
            _data     = $.param({token:token, action: _action, id: _id, username: _username});
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(PATH + "Instagram_follow/ajax_action_follow", _data, function(result){
                    if(result.st == "success"){
                        switch(_action){
                            case "follow":
                                _that.removeClass("btn-danger btn-success").addClass("btn-danger").data("action", "unfollow").html(Unfollow);
                                break;
                            case "unfollow":
                                _that.removeClass("btn-danger btn-success").addClass("btn-success").data("action", "follow").html(follow);
                                break;  
                        }                   
                    }
                    _that.removeClass('disable');
                },'json');
            }
        });
	};

    this.schedule = function(_that, action){
        _data = _that.closest("form").serialize();
        _data = _data + '&' + $.param({token:token});
        $(".schedule-loading").fadeIn();
        if(!_that.hasClass('disable')){
            _that.addClass('disable');
            $.post(PATH + "Schedule/ajax_schedule_"+action, _data, function(result){
                $('.schedule-errors').removeClass('has-message error').html("");
                if(result.st == "error"){
                    $.each(result, function(key, value) {
                        if(key != "st"){
                            $('.schedule-errors').addClass('has-message error').slideDown(300);
                            $('.schedule-errors').append('<li>'+value["text"]+'</li>');
                        }
                    });
                    _that.removeClass('disable');
                }else{
                    $.each(result, function(key, value) {
                        if(key != "st"){
                            $('.schedule-errors').addClass('has-message success').slideDown(300);
                            $('.schedule-errors').append('<li>'+value["text"]+'</li>');
                        }
                    });
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }

                _that.removeClass('disable');
                $(".schedule-loading").fadeOut();
            },'json');
        }

        return false;
    }

    this.post_now = function(data, accounts, index){
        if(accounts.length > 0){
            _data  = _data + '&' + $.param({account: accounts[index]});
        }

        if(index < accounts.length){
            $(".schedule-progress").show();
            $(".schedule-loading").fadeIn();
            $.post(PATH+"Schedule/ajax_post_now", _data, function(result){
                $('.schedule-errors').removeClass('has-message error').html('');
                
                if(result.st == "success"){
                    $(".schedule-list-accounts .item input[value='"+accounts[index]+"']").parents(".item").trigger("click");

                    //Progress Bar
                    _percent = Math.round((index+1)/accounts.length*100,0);
                    $(".schedule-progress div").css({ 'width' : _percent+'%' });

                    //Message
                    if(_percent == 100){
                        setTimeout(function(){
                            $(".btnPostNow").removeClass('disable');
                            $(".schedule-loading").fadeOut();
                            $(".schedule-progress").hide(400);
                            $(".schedule-progress div").css({ 'width' : '0%' });
                            setTimeout(function(){
                                window.location.reload();
                            },1000);
                        },2000);
                    }

                    //Loop
                    index++;
                    self.post_now(data, accounts, index);
                }else{
                    $('.schedule-errors').removeClass('has-message error').html("");
                    $.each(result, function(key, value) {
                        if(key != "st"){
                            $('.schedule-errors').addClass('has-message error').slideDown(300);
                            $('.schedule-errors').append('<li>'+value["text"]+'</li>');
                        }
                    });
                }
            }, 'json');
        }
    }

    this.chart = function(){
        _timeout = 0;
        setTimeout(function(){ self.ajax_chart('report_posts'); },_timeout);
    };

    this.ajax_chart = function(element){
        $('.' + element).html('');
        $(".schedule-loading").fadeIn();
        _daterange = $('.daterange').val();
        _data = $.param({token:token, daterange: _daterange});
        $.post(PATH + 'Instagram_analytics/ajax_' + element, _data, function(data){
            $('.' + element).html(data);
            $(".schedule-loading").fadeOut();
        });
    };

    this.round = function(value, decimals) {
        return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
    };

    this.Highcharts = function(options){
        Highcharts.theme = {
           colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
           chart: {
              borderWidth: 0,
              plotShadow: false,
              plotBorderWidth: 0
           },
           title: {
              style: {
                 color: '#000'
              }
           },
           subtitle: {
              style: {
                 color: '#666666'
              }
           },
           xAxis: {
              labels: {
                 style: {
                    color: '#000'
                 }
              },
              title: {
                 style: {
                    color: '#333'
                 }
              }
           },
           yAxis: {
              minorTickInterval: 'auto',
              tickColor: '#000',
              labels: {
                 style: {
                    color: '#000'
                 }
              },
              title: {
                 style: {
                    color: '#333',
                    fontWeight: 'bold',
                    fontSize: '12px',
                 }
              }
           },
           legend: {
              itemStyle: {
                 color: 'black'

              },
              itemHoverStyle: {
                 color: '#039'
              },
              itemHiddenStyle: {
                 color: 'gray'
              }
           },
           labels: {
              style: {
                 color: '#99b'
              }
           },

           navigation: {
              buttonOptions: {
                 theme: {
                    stroke: '#CCCCCC'
                 }
              }
           }
        };

        Highcharts.setOptions(Highcharts.theme);

        $(options.element).highcharts({
            chart: {
                zoomType: 'x',
                height  : (options.height)?options.height:300
            },
            title: {
                text: (options.title)?options.title:''
            },
            subtitle: {
                text: (options.subtitle)?options.subtitle:''
            },
            xAxis: {
                type: (options.titlex)?options.titlex:'',
                dateTimeLabelFormats: {
                    day: (options.format)?options.format:'%b %e',
                }
            },
            yAxis: {
                title: {
                    text: (options.titley)?options.titley:''
                }
            },
            legend: {
                enabled: true
            },
            tooltip: {
                crosshairs: (options.crosshairs)?true:false,
                shared: true
            },
            plotOptions: {
                spline: {
                    marker: {
                        radius: 4,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                },
                line: {
                    marker: {
                        radius: 4,
                        lineColor: '#666666',
                        lineWidth: 1
                    },
                    tooltip: {
                        valueSuffix: (options.suffix)?options.suffix:''
                    },
                    color: (options.colory)?options.colory:Highcharts.getOptions().colors[5]
                },
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, (options.colorx)?options.colorx:Highcharts.getOptions().colors[5]],
                            [1, Highcharts.Color((options.colory)?options.colory:Highcharts.getOptions().colors[5]).setOpacity(1).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 0
                    },
                    color: (options.colory)?options.colory:Highcharts.getOptions().colors[5],
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 0
                        }
                    },
                    threshold: null
                },
                pie: {
                    tooltip: {
                        valueSuffix: '%',
                        pointFormatter: function() {
                            return '<span style="color: '+this.series.tooltipOptions.backgroundColor+'">\u25CF</span> '+this.series.name+': <b>'+self.round(this.percentage,2)+'%</b><br/>.'
                        }
                    },
                }
            },

            series: (options.multi)?options.data:[{ type: (options.type)?options.type:'line',name: (options.name)?options.name:'', data: (options.data)?options.data:'', dataLabels: (options.dataLabels)?options.dataLabels:'{point.y}' }]
        });
        list_chart.push(options.element);
    };

	this.show_notice= function(txt, class_name, element){
        $(element).removeClass('error success').addClass(class_name).html(txt);

        clearTimeout(show_timeout);
        show_timeout = setTimeout(function(){
            $(element).html('');
        }, 8000);
    };
}

Instagram= new Instagram();
$(function(){
	Instagram.init();
});