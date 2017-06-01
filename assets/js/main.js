function Page(){
	var self= this;
	var show_timeout = 0;
	this.init= function(){
        if(window.location.hash == "#not-active"){
            self.show_notice(not_activated, 'error', '.msg');
        }

        $('input.input-icheck').iCheck({
            checkboxClass: 'icheckbox_square-orange',
            radioClass: 'iradio_square-orange',
        });

		$('.formRegister').submit(function(){
            _that = $(this);
            _data = _that.serialize();
            _redirect = _that.data("redirect");
            _data = _data + '&' + $.param({token:token});

            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                self.show_notice(system_processing, 'error', '.msg-register');
                $.post(PATH + "Users/ajax_register", _data, function(data){
                    if(data.st == 'success'){
                        self.show_notice(data.txt, data.st, '.msg-register');
                        setTimeout(function(){
                            window.location.reload();
                        },1000);
                    }else{
                        self.show_notice(data.txt, data.st, '.msg-register');
                    }
                    _that.removeClass('disable');
                },'json');
            }

            return false;
        });

        $('.formVerify').submit(function(){
            _that = $(this);
            _data = _that.serialize();
            _data = _data + '&' + $.param({token:token});

            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                self.show_notice(system_processing, 'error', '.msg');
                $.post(PATH+"Home/ajax_verify", _data, function(data){
                    window.location.assign(PATH);
                    _that.removeClass('disable');
                },'json');
            }

            return false;
        });

        $('.formLogin').submit(function(){
            _that = $(this);
            _data = _that.serialize();
            _data = _data + '&' + $.param({token:token});

            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                self.show_notice(system_processing, 'error', '.msg');
                $.post(PATH+"Users/ajax_login", _data, function(data){
                    if(data.st == 'success'){
                        self.show_notice(data.txt, data.st, '.msg');
                        setTimeout(function(){
                            window.location.reload();
                        },1000);
                    }else{
                        self.show_notice(data.txt, data.st, '.msg');
                    }
                    _that.removeClass('disable');
                },'json');
            }

            return false;
        });
	};

	this.show_notice= function(txt, class_name, element){
        $(element).removeClass('error success').addClass(class_name).html(txt);

        clearTimeout(show_timeout);
        show_timeout = setTimeout(function(){
            $(element).html('');
        }, 8000);
    };

    this.startPageLoading = function(element,overplay) {
        if (element) {
            $(element).append('<div class="page-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>');
        } else {
            $('body').append('<div class="page-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>');
        }
    };

    this.stopPageLoading = function(element) {
        $(element + ' .page-loading, '+element + '.page-spinner-bar').remove();
    };
}
Page= new Page();
$(function(){
	Page.init();
});