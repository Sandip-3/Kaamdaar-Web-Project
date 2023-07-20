function generateChart(context, label, labels, data)
{
    let datasets = [];
    for(let i = 0; i < data.length; i++)
    {
        datasets.push({label: labels[i], data: data[i], backgroundColor: "pink"});
    }

    const chart = new Chart(context, {
        type: "bar",
        data: {
            labels: [label],
            datasets
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    return chart;
}

function updateChartType(chart, type)
{
    chart.type = type;
    chart.update();
}

function updateChart(chart, label, types = [], newdata = [])
{
    let datasets = [];
    for(let i = 0; i < newdata.length; i++)
    {
        datasets.push({label: types[i], data: newdata[i], backgroundColor: "pink"});
    }

    if(newdata.length > 0)
    {
        chart.data.labels = [label];
        chart.data.datasets.pop();
        chart.data.datasets = datasets;
        chart.update();
    }
}