$().ready(function(){
    if(hasFlag == 0){
        return;
    }
    var statistics = JSON.parse($('#hideinfo').html());
    if(hasFlag == 1){
        var labels = [];
        var data = [];
        $.each(statistics, function(key, value) {
            labels.push(key);
            data.push(value);
        });

        var lineChartData = {
            labels : labels,
            datasets : [
                {
                    label: "报修统计",
                    fillColor : "rgba(160,160,220,0.2)",
                    strokeColor : "rgba(120,120,220,1)",
                    pointColor : "rgba(80,80,220,1)",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : "rgba(80,220,80,1)",
                    data : data
                }
            ]
        };
        var ctx = $("#myChart").get(0).getContext("2d");
        //This will get the first returned node in the jQuery collection.
        var myNewChart = new Chart(ctx).Line(lineChartData, {
            responsive: false
        });
    } else if(hasFlag == 2){
        var labels = [];
        var datasets = [] ;
        var i = 0;
        var strokeColors = ["rgba(220,120,120,0.8)", "rgba(120,220,120,0.8)",
                "rgba(120,120,220,0.8)", "rgba(220,220,120,0.8)",
                "rgba(220,120,220,0.8)", "rgba(120,220,220,0.8)",
                "rgba(220,220,220,0.8)", "rgba(120,120,120,0.8)"];
        var pointColors = ["rgba(220,120,120,1)", "rgba(120,220,120,1)",
                "rgba(120,120,220,1)", "rgba(220,220,120,1)",
                "rgba(220,120,220,1)", "rgba(120,220,220,1)",
                "rgba(220,220,220,1)", "rgba(120,120,120,1)"];
        $.each(statistics, function(key, value) {
            var data = [];
            $.each(value, function(k, v) {
                if(i==0){
                    labels.push(k);
                }
                data.push(v);
            });
            datasets.push({
                label: key,
                fillColor : "rgba(160,160,220,0)",
                strokeColor : strokeColors[i],
                pointColor : pointColors[i],
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(80,220,80,1)",
                data : data
            });
            i = i+1;
        });

        var lineChartData = {
            labels : labels,
            datasets : datasets
        };
        console.log(lineChartData);
        var ctx = $("#myChart").get(0).getContext("2d");
        //This will get the first returned node in the jQuery collection.
        var myNewChart = new Chart(ctx).Line(lineChartData, {
            responsive: false, datasetLabelWidth: 34, showDatasetLabel: true
        });
    }
});