$(function() {
    //    headerE.waypoint('sticky');
    var headerE = $('#header');
    $(window).scroll(function() {
        var boxTop = headerE.offset().top;
        if(boxTop <= 0) {
            headerE.css("opacity", "1.0");
        } else {
            headerE.css("opacity", ".5");
        }
    });
    headerE.hover(function() {
        headerE.css("opacity", "1.0");
    }, function() {
        var boxTop = headerE.offset().top;
        if(boxTop > 0) {
            headerE.css("opacity", ".5");
        }
    });


	$("#crawlButton").click(function(){

		var text = $("#crawl").val();

		var matches = text.match(/授業名[\n\t\s]+(.*)/);
		var name = matches[1];
		matches = text.match(/目的概要[\n\t\s]+((.|\n)+)\n達成目標/);
		var detail_text = matches[1];
		matches = text.match(/開講年度学期[\n\t\s]+20(.*)年度\s(.{0,2})期/);
		var term_y = matches[1];
		var term_t = matches[2];
		for(var i = 0; i<2; i++){
		    term_t = term_t.replace("前", "1").replace("後", "2");
		}
		if(term_t.length == 1)term_t += "0";

		matches = text.match(/曜日・時限[\n\t\s]+(.曜.限)\s?(.曜.限)?\s?(.曜.限)?\s?(.曜.限)?\n単位数/);
		var schedule = new Array(5);
		for(var i = 0; i < 5 ; i++ ) {
		    schedule[i] = toScheduleNum(matches[i]);
		}

		matches         = text.match(/主担当教員[\n\t\s]+((.|\n)*)\n副担当/);
		var teacher     = matches[1];
		matches         = text.match(/履修条件[\n\t\s]+((.|\n)*)\n教科書名/);
		var limit       = matches[1];
		matches         = text.match(/教科書名[\n\t\s]+((.|\n)*)\n参考書名/);
		var textbook    = matches[1];
		matches         = text.match(/評価方法[\n\t\s]+((.|\n)*)\n進級コード/);
		var measurement = matches[1];
		matches         = text.match(/単位数[\n\t\s]+(\d)\.\d\n主担当/);
		var credit      = matches[1];
		matches         = text.match(/準備学習[\n\t\s]+((.*\n)*)自由記載欄/);
		var prepare     = matches[1];

		if($("input[name='name']"))        $("input[name='name']").val(name);
		if($("textarea[name='detail']"))   $("textarea[name='detail']").val(detail_text);

		if($("select[name='term_y']"))     $("select[name='term_y']").val(term_y);
		if($("select[name='term_t']"))     $("select[name='term_t']").val(term_t);
		if($("input[name='teacher']"))     $("input[name='teacher']").val(teacher);
		if($("input[name='textbook']"))    $("input[name='textbook']").val(textbook);
		if($("input[name='limit']"))       $("input[name='limit']").val(limit);
		if($("input[name='measurement']")) $("input[name='measurement']").val(measurement);
		if($("input[name='prepare']"))     $("input[name='prepare']").val(prepare);
		if($("input[name='credit']"))      $("input[name='credit']").val(credit);

	    for(var i = 0; i < 5; i++) {
		    if(schedule[i] != ''){
		        var d = schedule[i].charAt(0);
		        var k = schedule[i].charAt(1);
			    if($("input[name='schedule[" + d + "][" + k + "]']"))
                    $("input[name='schedule[" + d + "][" + k + "]']").attr("checked", true);
		    }
	    }
	});



    var paste_box = $('.paste-box');
    paste_box.change(function (){
        if(paste_box.value() == '') {
            paste_box.css('border-color', 'red');
        } else {
            paste_box.css('border-color', '');
        }
    });

});



function closeMessageBox() {
    var box = $("#message-box");
    box.slideUp("slow");
}

function switchSearch(a){
    $("#bar-search").children("form").children("input[type='button']").css("display","block");
    $("#bar-search").children("form").children("div").css("display","none");
    $("#search-type").attr("value",a);
    $("input.sb-"+a).css("display","none");
    $("div.sb-"+a).css("display","block");
};
function switchNote(a){
    var middleE = $('#notification-box>.middle');
    var formE = $('#notification-box>.middle>.switchs-line>form');
    decorateSwitchOff(formE.children('input'));
    decorateSwitchOn(formE.children("input.nw-"+a));
    middleE.children("div[class *= 'notification-box']").hide();
    middleE.children('div.notification-box-'+a).css('display', 'block');
};
function decorateSwitchOn(a) {
    a.css('border-top', 'solid #01095e 3px');
}
function decorateSwitchOff(a) {
    a.css('border-top', 'none');
}

function switchMakeClass() {
    var mkc   = $("input[name='make-class']");
    var clbox = $("#class-form-div");
    var btn   = $("input[name='make-class-switch']");
    if( mkc.val() == 1) {           //change from on to off
        mkc.val(0);
        clbox.slideUp("slow");
        btn.val("クラスを同時に作成する");
    } else {                        //change form off to on
        mkc.val(1);
        clbox.slideDown("slow");
        btn.val("クラスを同時に作成しない");
    }
}



function toScheduleNum(str) {
	return String(str).replace("月", "1").replace("火", "2").replace("水", "3").replace("木", "4").replace("金", "5").replace("土", "6").replace("曜", "").replace("限", "");
}


