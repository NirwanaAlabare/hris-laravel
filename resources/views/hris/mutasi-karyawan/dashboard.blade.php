@extends('admin.adminlayouts.adminlayout-mut-karyawan')

@section('head')
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('assets/css/iziToast.min.css') }}">

<style>
    #chartdiv {
      width: 100%;
      height: 700px;
    }
    </style>

@stop
@section('mainarea')

<!-- page-header -->
<div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
    <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
        <li><a href="{{route('hris.dashboard.tes')}}">Mutasi Karyawan</a></li>
        <li class="active"><span>DASHBOARD</span></li>
    </ol>
    <div class="ml-auto">
        <div class="input-group">
            <a href="#" id="btn-refresh-data" class="btn btn-icon btn-secondary p-0 m-0" data-toggle="tooltip"
                title="" data-placement="bottom" data-original-title="Refresh Halaman">
                <span>
                    <i class="fa fa-refresh"></i>
                </span>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card shadow pt-5 px-3">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div id="chartdiv"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('footerjs')

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="{{URL::asset('assets/js/script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{URL::asset('assets/js/iziToast.min.js')}}"></script>
<script>
    function autoBreak(label) {
        const maxLength = 5;
        const lines = [];

        for (let word of label.split(" ")) {
            if (lines.length == 0) {
                lines.push(word);
            } else {
                const i = lines.length - 1
                const line = lines[i]

                if (line.length + 1 + word.length <= maxLength) {
                    lines[i] = `${line} ${word}`
                } else {
                    lines.push(word)
                }
            }
        }

        return lines;
    }
</script>

<script>
    am5.ready(function() {
        var root = am5.Root.new("chartdiv");

        root._logo.dispose();
        root.setThemes([
            am5themes_Animated.new(root)
        ]);

        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true,
            paddingLeft:0,
            paddingRight:1
        }));

        var title = chart.plotContainer.children.push(am5.Label.new(root, {
            text: "PT NIRWANA ALABARE GARMENT",
            fontSize: 20,
            fontWeight: "400",
            x: am5.p50,
            centerX: am5.p50,
            y:-5,
            }))

        var labelContainer = chart.plotContainer.children.push(am5.Container.new(root, {
            layout: root.horizontalLayout,
            x: am5.p50,
            centerX: am5.p50,
            y: 20,
            dy: title.height() + 10,
            paddingLeft: 10,
            paddingRight: 10,
            spacing: 10
        }));


        var backgroundColor = am5.color(0x15435A);
        var borderColor = am5.color(0x15435A);
        var textColor = am5.color(0xFFFFFF);


        var sewingLabel = labelContainer.children.push(am5.Label.new(root, {
            text: "SEWING: 40",
            fontSize: 14,
            fontWeight: "400",
            fill: textColor,
            background: am5.RoundedRectangle.new(root, {
                fill: backgroundColor,
                fillOpacity: 1,
                stroke: borderColor,
                strokeWidth: 1,
                cornerRadiusTL: 5,
                cornerRadiusTR: 5,
                cornerRadiusBL: 5,
                cornerRadiusBR: 5
            }),
            paddingTop: 5,
            paddingBottom: 5,
            paddingLeft: 15,
            paddingRight: 15,
            marginRight: 10
        }));


        var nonSewingLabel = labelContainer.children.push(am5.Label.new(root, {
            text: "NON SEWING: 30",
            fontSize: 14,
            fontWeight: "400",
            fill: textColor,
            background: am5.RoundedRectangle.new(root, {
                fill: backgroundColor,
                fillOpacity: 1,
                stroke: borderColor,
                strokeWidth: 1,
                cornerRadiusTL: 5,
                cornerRadiusTR: 5,
                cornerRadiusBL: 5,
                cornerRadiusBR: 5
            }),
            paddingTop: 5,
            paddingBottom: 5,
            paddingLeft: 15,
            paddingRight: 15
        }));

        chart.zoomOutButton.set("forceHidden", true);

        var xRenderer = am5xy.AxisRendererX.new(root, {
            minGridDistance: 30,
            minorGridEnabled: true
        });
        xRenderer.labels.template.setAll({
            rotation: -90,
            paddingRight: 15,
            centerY: am5.p50,
            centerX: am5.p100,
        });
        xRenderer.grid.template.set("visible", false);

        var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
            maxDeviation: 0.3,
            categoryField: "line",
            renderer: xRenderer
        }));

        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 0.3,
            min: 0,
            renderer: am5xy.AxisRendererY.new(root, {})
        }));

        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
            name: "Series 1",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            categoryXField: "line"
        }));

        series.columns.template.setAll({
            cornerRadiusTL: 5,
            cornerRadiusTR: 5,
            strokeOpacity: 0
        });

        series.columns.template.adapters.add("fill", function(fill, target) {
            return chart.get("colors").getIndex(series.columns.indexOf(target));
        });

        series.columns.template.adapters.add("stroke", function(stroke, target) {
            return chart.get("colors").getIndex(series.columns.indexOf(target));
        });

        series.bullets.push(function() {
            return am5.Bullet.new(root, {
                locationY: 1,
                sprite: am5.Label.new(root, {
                    text: "{valueYWorking.formatNumber('#.')}",
                    fill: am5.color(0x000000),
                    centerY: am5.p100,
                    centerX: am5.p50,
                    populateText: true
                })
        });
        series.dataItems.sort(function (x, y) {
            return y.get("valueY") - x.get("valueY"); // descending
        });
    });

    function sortAndUpdateChart(dataArr, total_sewing, total_non_sewing) {
            dataArr.sort((a, b) => b.value - a.value);
            // Update chart data
            xAxis.data.setAll(dataArr);
            series.data.setAll(dataArr);
            sewingLabel.set("text", `SEWING : ${total_sewing}`);
            nonSewingLabel.set("text", `NON SEWING : ${total_non_sewing}`);
    }

    // Function to fetch and update data
    function getLineData() {
        $.ajax({
            url: '{{ route('hris.line-dashboard-mutasi-karyawan') }}',
            type: 'get',
            dataType: 'json',
            success: function(res) {
                let dataArr = [];
                let total_sewing = 0;
                let total_non_sewing = 0;
                res.forEach(element => {
                    dataArr.push({
                        line: autoBreak(element.line.replace(/ /g, "-")),
                        value: element.tot_orang
                    });
                    total_sewing += element.jumlah_sewing;
                    total_non_sewing += element.jumlah_non_sewing;
                });

                sortAndUpdateChart(dataArr, total_sewing, total_non_sewing);
            },
            error: function(jqXHR) {
                let res = jqXHR.responseJSON;
                console.error(res.message);
                iziToast.error({
                    title: 'Error',
                    message: res.message,
                    position: 'topCenter'
                });
            }
        });
    }

    // Initial fetch
    getLineData();

    // Fetch data every 30 seconds
    setInterval(function() {
        getLineData();
    }, 30000);

    series.appear(1000);
    chart.appear(1000, 100);
});
</script>

@endsection
