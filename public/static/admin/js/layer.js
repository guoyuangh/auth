layer.config({
    shade: [0.4, '#000000'],
    resize: false,
    title: '提示信息！',
});

function showPageHtml(params, callBack) {
    var width = $(params).data('width'), height = $(params).data('height'), minMax = $(params).data('minmax'),
        autoMax = $(params).data('autoMax'), url = $(params).data('href');

    var width = width ? parseInt(width) + 'px' : 'auto';
    var height = height ? parseInt(height) + 'px' : 'auto';
    if (minMax == true) {
        minMax = true;
    } else {
        minMax = false
    }

    $.get(url, function (res) {
        if (res.status == 1) {
            var data = res.data;
            var index = layer.open({
                type: 1,
                content: data.page,
                title: data.title,
                closeBtn: 1,
                area: [width, height],
                maxmin: minMax,
                // shadeClose:true
            });
            if (autoMax === true) {
                layer.full(index);
            }
            callBack ? callBack() : '';
        } else {
            showError(res.msg);
        }
    });
}

function showError(msg) {
    layer.alert(msg, {
        icon: 2,
        closeBtn: false,
    });
}

$.ajaxSetup({
    timeout:10000,
    error: function (jqXHR, textStatus, errorThrown) {
        switch (jqXHR.status) {
            case(500):
                layer.alert('服务器系统内部错误！' + textStatus, {icon: 2});
                break;
            case(408):
                layer.alert('请求超时！' + textStatus, {icon: 3});
                break;
            default:
                layer.alert('请求出错！' + textStatus, {icon: 2});
        }
    },

    beforeSend: function () {
        layer.load(1, {shade: [0.3, '#FFFFFF']});
    },

    complete: function () {
        layer.closeAll('loading');
    }
});