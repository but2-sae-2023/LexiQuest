import React, { useEffect, useRef } from 'react';
import * as Highcharts from "highcharts";
import HighchartsNetworkgraph from 'highcharts/modules/networkgraph';

import Exporting from 'highcharts/modules/exporting';
Exporting(Highcharts);

HighchartsNetworkgraph(Highcharts);

const OnePlayer: React.FC = () => {
    const chartContainer = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (chartContainer.current) {
            // Generate the chart options
            const options: Highcharts.Options = {
                title: {
                    text: "Partie monojoueur",
                    style: {
                        color: 'white',
                        fontSize: '30px'
                    }
                },
                credits: {
                    enabled: false
                },
                chart: {
                    type: 'networkgraph',
                    backgroundColor: 'transparent',
                    renderTo: chartContainer.current
                },
                plotOptions: {
                    networkgraph: {
                        draggable: true,
                        layoutAlgorithm: {
                            enableSimulation: true
                        },
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
                                textOutline: undefined
                            }
                        },
                        marker: {
                            radius: 20
                        }
                    }
                },
                series: [{
                    type: 'networkgraph',
                    data: [{
                        from: 'startWord',
                        to: 'endWord'
                    }, {
                        from: 'endWord',
                        to: 'startWord'
                    }]
                }],
                
            };

            // Generate the chart using the options
            Highcharts.chart(options);
        }
    }, []);

    return (
        <div id='chart'>
            <div  ref={chartContainer}></div>
            <form method="POST">
                <div className="word-area">
                    <input type='text' name='user_word' />
                    <input type="submit" id="play" name="play" value="Jouer" />
                </div>
            </form>
            <br />
            <p><a href="home.php">Retour</a></p>
        </div>
    );
};

export default OnePlayer;