UI = {
    //加载模态框
    alert: function (obj) {
        var title = (null == obj || undefined === obj || undefined === obj.title) ? "系统提示" : obj.title;
        var message = (null == obj || undefined === obj || undefined === obj.message) ? "" : obj.message;
        var icon = (null == obj || undefined === obj || undefined === obj.icon) ? "error" : obj.icon;
        var html = "<div class=\"modal fade\" id=\"ui-alert-sm\" tabindex=\"-1\" role=\"dialog\">\n" +
            "    <div class=\"modal-dialog modal-sm\" role=\"document\">\n" +
            "        <div class=\"modal-content\">\n" +
            "            <div class=\"modal-header\">\n" +
            "                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>\n" +
            "                <h4 class=\"modal-title\">" + title + "</h4>\n" +
            "            </div>\n" +
            "            <div class=\"modal-body\">\n" +
            "                <p><img src=\"static/image/" + icon + ".png\" style='width: 32px;height: 32px; margin-right: 5px;'>" + message + "</p>\n" +
            "            </div>\n" +
            "            <div class=\"modal-footer\">\n" +
            "                <button type=\"button\" class=\"btn btn-primary\" onclick=\"$('#ui-alert-sm').modal('hide')\">確定</button>\n" +
            "            </div>\n" +
            "        </div><!-- /.modal-content -->\n" +
            "    </div><!-- /.modal-dialog -->\n" +
            "    </div><!-- /.modal -->";
        $('body').append(html);
        $('#ui-alert-sm').modal({backdrop: 'static'});
        $('#ui-alert-sm').modal("show");
        $('#ui-alert-sm').on('hidden.bs.modal', function (e) {
            // do something...
            $('#ui-alert-sm').remove();
        })
    },
    //加载页面
    open: function (obj) {
        var title = (null == obj || undefined === obj || undefined === obj.title) ? "" : obj.title;
        var url = (null == obj || undefined === obj || undefined === obj.url) ? "" : obj.url;
        var width = (null == obj || undefined === obj || undefined === obj.width) ? 550 : obj.width;
        var height = (null == obj || undefined === obj || undefined === obj.height) ? 450 : obj.height;
        var html = "<div class=\"modal fade\" id=\"ui-alert-lg\" tabindex=\"-1\" role=\"dialog\">\n" +
            "    <div class=\"modal-dialog modal-lg\" role=\"document\">\n" +
            "        <div class=\"modal-content\">\n" +
            "            <div class=\"modal-header\">\n" +
            "                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>\n" +
            "                <h4 class=\"modal-title\">" + title + "</h4>\n" +
            "            </div>\n" +
            "            <div class=\"modal-body\">\n" +
            "                   <iframe src='"+url+"' style='width: 100%;height: 100%' frameborder='0'></iframe>" +
            "            </div>\n" +
            "        </div><!-- /.modal-content -->\n" +
            "    </div><!-- /.modal-dialog -->\n" +
            "    </div><!-- /.modal -->";
        $('body').append(html);
        $('#ui-alert-lg .modal-lg').css('width',width);
        $('#ui-alert-lg .modal-body').css('height',height);
        $('#ui-alert-lg').modal({backdrop: 'static'});
        $('#ui-alert-lg').modal("show");
        $('#ui-alert-lg').on('hidden.bs.modal', function (e) {
            // do something...
            $('#ui-alert-lg').remove();
        })
    }
}