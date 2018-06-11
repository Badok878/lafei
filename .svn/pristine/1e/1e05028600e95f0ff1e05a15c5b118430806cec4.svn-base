function loadbouns(min, max, id) {
//$('#ex1Slider').remove();
    //  var mySelect = $('#first-disabled2');
    var max = (parseFloat(max) - 0.1).toFixed(1);
    // var min = (parseFloat(min) - 0.1).toFixed(1);
    $('#range-def-min').html(min);
    $('#range-def-max').html(max);
    var count = max / 0.1;


    s = 0.0;

   var  str = '<option>请选择</option>'
    for (var i = 0; i < count + 1; i++) {
        /* var number = $("#trr tr:eq("+i+")").children().eq(0).find("input").val();*/

        str += '<option>' + (s.toFixed(1)) + '</option>';
        /*  var count = $("#trr tr:eq("+i+")").children().eq(1).find("input").val();*/
        /* jsonStr +="\"text\":\""+(s.toFixed(1))+"\"},";*/
        s += 0.1;
    }

    //var str =jsonStr.substring(0,jsonStr.length-1);
    //str += "]";
    //alert(str);
    //console.log(JSON.parse(str));
    // str = [{"id":"0","text":"0"},{"id":"1","text":"0.1"},{"id":"2","text":"0.2"},{"id":"3","text":"0.30000000000000004"},{"id":"4","text":"0.4"},{"id":"5","text":"0.5"},{"id":"6","text":"0.6000000000000001"},{"id":"7","text":"0.7000000000000001"},{"id":"8","text":"0.8"},{"id":"9","text":"0.9"},{"id":"10","text":"1"},{"id":"11","text":"1.1"},{"id":"12","text":"1.2000000000000002"},{"id":"13","text":"1.3"},{"id":"14","text":"1.4000000000000001"},{"id":"15","text":"1.5"},{"id":"16","text":"1.6"},{"id":"17","text":"1.7000000000000002"},{"id":"18","text":"1.8"},{"id":"19","text":"1.9000000000000001"},{"id":"20","text":"2"},{"id":"21","text":"2.1"},{"id":"22","text":"2.2"},{"id":"23","text":"2.3000000000000003"},{"id":"24","text":"2.4000000000000004"},{"id":"25","text":"2.5"},{"id":"26","text":"2.6"},{"id":"27","text":"2.7"},{"id":"28","text":"2.8000000000000003"},{"id":"29","text":"2.9000000000000004"},{"id":"30","text":"3"},{"id":"31","text":"3.1"},{"id":"32","text":"3.2"},{"id":"33","text":"3.3000000000000003"},{"id":"34","text":"3.4000000000000004"},{"id":"35","text":"3.5"},{"id":"36","text":"3.6"},{"id":"37","text":"3.7"},{"id":"38","text":"3.8000000000000003"},{"id":"39","text":"3.9000000000000004"},{"id":"40","text":"4"},{"id":"41","text":"4.1000000000000005"},{"id":"42","text":"4.2"},{"id":"43","text":"4.3"},{"id":"44","text":"4.4"},{"id":"45","text":"4.5"},{"id":"46","text":"4.6000000000000005"},{"id":"47","text":"4.7"},{"id":"48","text":"4.800000000000001"},{"id":"49","text":"4.9"},{"id":"50","text":"5"},{"id":"51","text":"5.1000000000000005"},{"id":"52","text":"5.2"},{"id":"53","text":"5.300000000000001"},{"id":"54","text":"5.4"},{"id":"55","text":"5.5"},{"id":"56","text":"5.6000000000000005"},{"id":"57","text":"5.7"},{"id":"58","text":"5.800000000000001"},{"id":"59","text":"5.9"},{"id":"60","text":"6"},{"id":"61","text":"6.1000000000000005"},{"id":"62","text":"6.2"},{"id":"63","text":"6.300000000000001"},{"id":"64","text":"6.4"},{"id":"65","text":"6.5"},{"id":"66","text":"6.6000000000000005"},{"id":"67","text":"6.7"},{"id":"68","text":"6.800000000000001"},{"id":"69","text":"6.9"},{"id":"70","text":"7"},{"id":"71","text":"7.1000000000000005"},{"id":"72","text":"7.2"},{"id":"73","text":"7.300000000000001"},{"id":"74","text":"7.4"},{"id":"75","text":"7.5"},{"id":"76","text":"7.6000000000000005"},{"id":"77","text":"7.7"},{"id":"78","text":"7.800000000000001"}];
    $("#fandian").html(str);


    /* var min = parseFloat(min).toFixed(1);

     var max = (parseFloat(max) - 0.1).toFixed(1);
     $('#range-def-min').html(min);
     $('#range-def-max').html(max);


     // Without JQuery
     var slider = new Slider("#ex1", {

     min: 0,
     max: max,
     step: 0.1,
     tooltip: 'always'

     }).on("slide", function(slideEvt) {
     var values = slideEvt.value;

     var ff = parseFloat(values);
     if (ff > 0) {
     $("#" + id).val(ff.toFixed(1));
     $("#" + id).text(ff.toFixed(1));
     }
     else {
     $("#" + id).val(min);
     $("#" + id).text(min);
     }
     });
     $("#" + id).val(min);
     $("#" + id).text(min);
     return;*/

    /* slider.on('slide', function (slideEvt) {

     var values = slideEvt;

     var ff = parseFloat(values);
     if (ff > 0) {
     $("#" + id).val(ff.toFixed(1));
     $("#" + id).text(ff.toFixed(1));
     }
     else {
     $("#" + id).val(min);
     $("#" + id).text(min);
     }
     });
     $("#" + id).val(min);
     $("#" + id).text(min);
     return;
     }); */

    /* $('#ex1').slider({
     formatter: function (value) {
     return '当前值: ' + value;

     },
     min: 0,
     max: max,
     step: 0.1,
     tooltip: 'always'
     }).on('slide', function (slideEvt) {
     var values = $(this).val();

     var ff = parseFloat(values);
     if (ff > 0) {
     $("#" + id).val(ff.toFixed(1));
     $("#" + id).text(ff.toFixed(1));
     }
     else {
     $("#" + id).val(min);
     $("#" + id).text(min);
     }
     });
     $("#" + id).val(min);
     $("#" + id).text(min);
     return; */
    /* $(".noUiSlider").html('');

     var min=parseFloat(min).toFixed(1);
     var max=(parseFloat(max)-0.1).toFixed(1);
     $('#range-def-min').html(min);
     $('#range-def-max').html(max);

     $(".noUiSlider").noUiSlider({
     range: [min, max],
     start: min,
     handles: 1,
     step: 0.1,
     slide: function() {
     var values = $(this).val();
     var ff=parseFloat(values);
     if(ff>0){
     $("#"+id).val(ff.toFixed(1));
     $("#"+id).text(ff.toFixed(1));
     }
     else {
     $("#"+id).val(min);
     $("#"+id).text(min);
     }
     }
     });
     $("#"+id).val(min);
     $("#"+id).text(min);
     return;
     */
}
function chva(me) {
    var values = me.options[me.selectedIndex].text;
    if (values > 0) {
        $('#range-def-val').val(values);
    } else {

        $('#range-def-val').text(0.0);
    }


    /* var ff = parseFloat(values);
     if (ff > 0) {
     $("#" + id).val(ff.toFixed(1));
     $("#" + id).text(ff.toFixed(1));
     }
     else {
     $("#" + id).val(min);
     $("#" + id).text(min);
     }
     });
     $("#" + id).val(min);
     $("#" + id).text(min);
     return;*!/*/

}

function showBet() {
    var href = $(this).attr('action');
    var v_id = $(this).attr('data-value');
    var me = this;
    $("#betDetail").empty();
    $.ajax({
        type: "GET",
        url: href,
        data: {},
        dataType: "html",
        global: false,
        success: function (data) {
            $("#betDetail").append(data);
            $("#cancelproject").click(function () {
                if (true) {
                    $.ajax({
                        type: "POST",
                        url: "index.php?s=/home/game/deletecode",
                        data: {id: v_id},
                        dataType: "json",
                        global: false,
                        success: function (data) {
                            try {
                                if (data.status == 0) {
                                    bootbox.alert(data.info);
                                } else {
                                    $(me).parent().siblings("td:last").html('<label class="graylab">已撤单</label>');
                                    $("#details-modal").hide();
                                    bootbox.alert("撤单成功");
                                    //$("#betrecord").submit();
                                }
                            } catch (e) {
                                bootbox.alert("撤单失败，请梢后重试");
                            }
                        },
                        error: null,
                        cache: false
                    })
                }
            })
        },
        error: null,
        cache: false
    })
}
var dataAlert = [{
    type: "info"
}, {
    type: "primary"
}, {
    type: "success"
}, {
    type: "warning"
}, {
    type: "danger"
}, {
    type: "mint"
}, {
    type: "purple"
}, {
    type: "pink"
}, {
    type: "dark"
}];
var faIcon = {
    valid: "fa fa-check-circle fa-lg text-success",
    invalid: "fa fa-times-circle fa-lg",
    validating: "fa fa-refresh"
};

function UpdateValidateCode(encrypCode, verifyCode, codeImage) {
    $(verifyCode).val("");
    $(encrypCode).val("");
    $.ajax({
        type: "POST",
        url: "/public/getValidCodeByJosn?time=" + (new Date()).getTime(),
        dataType: "json",
        global: false,
        success: function (data) {
            $(verifyCode).val("");
            $(encrypCode).val(data.EncrypCode);
            $(codeImage).attr("src", data.CodeImage)
        },
        error: function () {
            $(verifyCode).val("");
            $(encrypCode).val("");
            $(codeImage).attr("src", "")
        }
    })
}
function UpdateSecurityCode(encrypCode, verifyCode, codeImage) {
    $(verifyCode).val("");
    $(encrypCode).val("");
    $.ajax({
        type: "POST",
        url: "/public/getSecurityCodeByJosn?time=" + (new Date()).getTime(),
        dataType: "json",
        global: false,
        success: function (data) {
            $(verifyCode).val("");
            $(encrypCode).val(data.EncrypCode);
            $(codeImage).attr("src", data.CodeImage)
        },
        error: function () {
            $(verifyCode).val("");
            $(encrypCode).val("");
            $(codeImage).attr("src", "")
        }
    })
}
function passwordStrength(pwdId, strengthId, sdivId) {
    var pwd = $(pwdId).val();
    var strength = 0;
    var reg = /[0-9]/;
    if (reg.test(pwd)) {
        strength = strength + 1
    }
    reg = /[a-z]/;
    if (reg.test(pwd)) {
        strength = strength + 1
    }
    reg = /[A-Z]/;
    if (reg.test(pwd)) {
        strength = strength + 1
    }
    reg = /[`~!@#\$%\^\&\*\(\)_\+<>\?:"\{\},\.\\\/;'\[\]]/;
    if (reg.test(pwd)) {
        strength = strength + 1
    }
    if (pwd.length >= 12) {
        strength = strength + 1
    }
    $(strengthId).val(strength);
    if (strength <= 3) {
        $(sdivId).html("弱");
        $(sdivId).css("width", "33%");
        $(sdivId).attr("class", "progress-bar progress-bar-danger")
    } else {
        if (strength == 4) {
            $(sdivId).html("一般");
            $(sdivId).css("width", "66%");
            $(sdivId).attr("class", "progress-bar progress-bar-warning")
        } else {
            $(sdivId).html("强");
            $(sdivId).css("width", "100%");
            $(sdivId).attr("class", "progress-bar progress-bar-info")
        }
    }
}
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return encodeURI(r[2])
    }
    return null
}
function showNiftyNoty() {
    if (arguments.length == 1) {
        if (typeof(arguments[0]) == "object") {
            var niftyType = arguments[0].IsSuccess ? "success" : "danger";
            $.niftyNoty({
                type: niftyType,
                message: arguments[0].Message,
                container: "floating",
                closeBtn: true,
                timer: 3000
            })
        } else {
            $.niftyNoty({
                type: "danger",
                message: arguments[0],
                container: "floating",
                closeBtn: true,
                timer: 3000
            })
        }
    } else {
        if (arguments.length == 2) {
            if (typeof(arguments[0]) == "boolean") {
                var type = arguments[0] ? "success" : "danger";
                $.niftyNoty({
                    type: type,
                    message: arguments[1],
                    container: "floating",
                    closeBtn: true,
                    timer: 3000
                })
            }
        }
    }
};