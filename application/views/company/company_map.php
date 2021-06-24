<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/company.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <script src="http://api.map.baidu.com/api?v=1.2" type="text/javascript"></script>
        <title><?= $company['companyname'] ?></title>
    </head>

    <body id="company_map">
        <div id="map" class="map"></div>
        <script>
            var map = new BMap.Map("map");
            var point = new BMap.Point(<?= $company['map_x'] ?>,<?= $company['map_y'] ?>);
            map.centerAndZoom(point, <?= $company['map_zoom'] ?>);
            var opts = {type: BMAP_NAVIGATION_CONTROL_SMALL, anchor: BMAP_ANCHOR_TOP_RIGHT}
            map.addControl(new BMap.NavigationControl(opts)); //添加鱼骨
            map.enableScrollWheelZoom();//启用滚轮放大缩小。
            // 创建标注
            var qs_marker = new BMap.Marker(point);
            map.addOverlay(qs_marker);
            // 创建标注 
            // 打开信息窗口 
            var opts = {
                width: 150, // 信息窗口宽度   
                height: 50, // 信息窗口高度   
                title: "<?= $company['companyname'] ?>"  // 信息窗口标题   
            }
            var infoWindow = new BMap.InfoWindow("<?= $company['address'] ?>", opts);  // 创建信息窗口对象   
            map.openInfoWindow(infoWindow, point);
            $('#map').height($(window).height());
            // 打开信息窗口  
        </script>
        <script src="<?= VIEW_PATH; ?>js/2019/common.js"></script>
    </body>
</html>
