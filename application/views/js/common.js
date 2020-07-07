function show_select(title, get_type, item) {
    $('#select_box').hide();
    $('#select_box .title p').html(title);
    $('#select_box').attr('data-item', item);
    $.get('/ajax_data/' + get_type, function(result) {
        var li_str = "";
        var r_arr = result.split('-|-');
        $.each(r_arr, function(key, val) {
            var data_arr = val.split('||');
            var li_data_str = "";
            var li_data_num = 1;
            $.each(data_arr, function(k, v) {
                if (k > 0) {
                    li_data_str = li_data_str + " data-data" + li_data_num + "='" + v + "'"
                    li_data_num++;
                }
            })
            li_str = li_str + "<li " + li_data_str + "><p>" + data_arr[0] + "</p></li>"
        });
        $('#select_box ul').html(li_str);
        $('#select_box').show();
        $('#select_box .bg').show();
        $('#select_box .select').show();
        $('body').css('overflow', 'hidden');
        $('body').css('height', '100%');
    })
}
$('#select_box').on('click', '.close', function() {
    $('#select_box').hide();
    $('body').css('overflow', '');
})

$('#select_box').on('click', '.bg', function() {
    $('#select_box').hide();
    $('body').css('overflow', '');
})

function show_select2(item) {
    $('#select_box2').show();
    $('#select_box2 .bg').show();
    $('#select_box2 .select').show();
    $('#select_box2').attr('data-item', item);
    $('body').css('overflow', 'hidden');
    $('body').css('height', '100%');
}

$('#select_box2').on('click', '.title .left', function() {
    $('#select_box2').hide();
    $('body').css('overflow', '');
})

$('#select_box2').on('click', '.bg', function() {
    $('#select_box2').hide();
    $('body').css('overflow', '');
})

$('.del_submit').click(function() {
    if (!confirm('È·¶¨É¾³ý£¿')) {
        return false;
    }
})


$('#page_box select').change(function() {
    var page_num = $(this).val();
    var url = $('#page_box').attr('data-url');
    var para = $('#page_box').attr('data-parameter');
    window.location.href = url + page_num + '?' + para;
})

$('#page_box a').click(function() {
    var page_num = $(this).attr('data-page');
    var url = $('#page_box').attr('data-url');
    var para = $('#page_box').attr('data-parameter');
    window.location.href = url + page_num + '?' + para;
})

var totalpage = $('#page_box').attr('data-totalpage');
var perpage = $('#page_box a.left').attr('data-page');
var nextpage = $('#page_box a.right').attr('data-page');
if (perpage == 1) {
    $('#page_box a.left').css('background-image', '')
}

$('#header_box a.go_back').click(function() {
    window.history.back();
})
