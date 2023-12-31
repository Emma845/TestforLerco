<?php
use app\assets\HighchartsAsset;

HighchartsAsset::register($this);

/* @var $this yii\web\View */

$this->title = '';
?>

<div class="dashboard-index">
    <div id="demo-chart"></div>
</div>

<style>
#demo-chart {
    height: 400px;
    max-width: 800px;
    min-width: 320px;
    margin: 0 auto;
}
.highcharts-pie-series .highcharts-point {
    stroke: #EDE;
    stroke-width: 2px;
}
.highcharts-pie-series .highcharts-data-label-connector {
    stroke: silver;
    stroke-dasharray: 2, 2;
    stroke-width: 2px;
}
</style>


<script type="text/javascript">
    $(document).ready(function(){
        Highcharts
            .chart('demo-chart', {

                title: {
                    text: 'Pie point CSS'
                },

                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },

                series: [{
                    type: 'pie',
                    allowPointSelect: true,
                    keys: ['name', 'y', 'selected', 'sliced'],
                    data: [
                        ['Apples', 29.9, false],
                        ['Pears', 71.5, false],
                        ['Oranges', 106.4, false],
                        ['Plums', 129.2, false],
                        ['Bananas', 144.0, false],
                        ['Peaches', 176.0, false],
                        ['Prunes', 135.6, true, true],
                        ['Avocados', 148.5, false]
                    ],
                    showInLegend: true
                }]
            });

    });
</script>
