<style>
    #container {
    min-width: 320px;
    max-width: 800px;
    margin: 0 auto;
    height: 500px;
}
</style>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/networkgraph.js"></script>
<div id="container"></div>
<script>
    Highcharts.chart('container', {
    chart: {
        type: 'networkgraph'
    },
    plotOptions: {
        networkgraph: {
            draggable: false,
            layoutAlgorithm: {
                enableSimulation: true
            }
        }
    },

    series: [{
        dataLabels: {
            enabled: true,
            linkTextPath: {
                attributes: {
                    dy: 12
                }
            },
            linkFormat: '{point.fromNode.name} \u2192 {point.toNode.name}',
            textPath: {
                enabled: true,
                attributes: {
                    dy: 14,
                    startOffset: '45%',
                    textLength: 80
                }
            },
            format: 'Node: {point.name}'
        },
        marker: {
            radius: 35
        },
        data: [{
            from: 'n1',
            to: 'n2'
        }, {
            from: 'n2',
            to: 'n3'
        }, {
            from: 'n3',
            to: 'n4'
        }, {
            from: 'n4',
            to: 'n5'
        }, {
            from: 'n5',
            to: 'n1'
        }]
    }]
});
</script>