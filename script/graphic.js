Highcharts.chart('graph-container', {
    title: {
        text: null
    },

    credits: {
        enabled: false
    },

    chart: {
        type: 'networkgraph',
        backgroundColor: null,
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
                    dy: 15
                }
            },
            // linkFormat: '{point.fromNode.name} \u2192 {point.toNode.name}',
            linkFormat: '{point.linkFormat}',
            textPath: {
                enabled: true,
                attributes: {
                    dy: -5,
                    startOffset: '50%',
                    // textLength: 1000
                }
            },
            format: '{point.name}',
            style: {
                color: 'white',
                fontSize: '15px',
                letterSpacing: '0px',   
                textOutline: null
            }
        },
        marker: {
            radius: 20
        },
        data : data,
        nodes : nodes
    }]
});