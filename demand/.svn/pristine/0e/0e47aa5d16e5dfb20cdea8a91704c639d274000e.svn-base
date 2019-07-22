$(function () {
    layer.config({
        skin: 'layer-ext-seaning',
        extend: 'skin/seaning/style.css'
    });
    $('.icon_logo').click(function () {
        location.href = '/reg.html';
    });
});
function resetVerifyCode(width, height, fontsize) {
    var width = width || 110;
    var height = height || 55;
    var fontsize = fontsize || 20;
    var timenow = new Date().getTime();
    document.getElementById('verifyImage').src = 'http://121.41.95.9/wpp.php?s=/wpp/api/verify/length/4/mode/1/width/' + width + '/height/' + height + '/size/' + fontsize + '/time/' + timenow + '.html';
}

function dialog_loading() {
    layer.load(2, {shade: [0.4, '#fff']})
}

function dialog_alert(msg) {
    layer.alert(msg, {
        icon: 6
    });
}

function dialog_close() {
    layer.closeAll();
}