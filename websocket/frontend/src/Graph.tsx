// import React from 'react';
import Highcharts from 'highcharts';
import HighchartsReact from 'highcharts-react-official';
import HCNG from 'highcharts/modules/networkgraph';

// Init Highcharts Networkgraph module
HCNG(Highcharts);

export interface DataPoint {
    from: string;
    to: string;
    linkFormat: string;
}
  
export interface Nodes {
    id: string;
    color: string;
}
  
interface GraphProps {
    data: DataPoint[];
    nodes: Nodes[];
}

const Graph = ({ data, nodes }: GraphProps) => {
  const chartOptions = {
    title: {
      text: null,
    },
    credits: {
      enabled: false,
    },
    chart: {
      type: 'networkgraph',
      backgroundColor: null,
    },
    plotOptions: {
      networkgraph: {
        draggable: true,
        layoutAlgorithm: {
          enableSimulation: true,
        },
      },
    },
    series: [
      {
        dataLabels: {
          enabled: true,
          linkTextPath: {
            attributes: {
              dy: 15,
            },
          },
          linkFormat: '{point.linkFormat}',
          textPath: {
            enabled: true,
            attributes: {
              dy: -5,
              startOffset: '50%',
            },
          },
          format: '{point.name}',
          style: {
            color: 'white',
            fontSize: '15px',
            letterSpacing: '0px',
            textOutline: null,
          },
        },
        marker: {
          radius: 20,
        },
        data,
        nodes,
      },
    ],
  };

  return <HighchartsReact id='chart' highcharts={Highcharts} options={chartOptions} />;
};

export default Graph;