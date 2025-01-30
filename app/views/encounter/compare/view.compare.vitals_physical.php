<?php

    $data_labels = $data_weight = $data_height = $data_bmi = Array();

    foreach ($_['encounters'] as $encounter) {
        $data_labels[] = "$encounter->date";
        $data_weight[] = round($encounter->physical_weight,2);
        $data_height[] = round($encounter->physical_height,2);
        $bmi           = $encounter->physical_weight / (  $encounter->physical_height  * $encounter->physical_height );
        $bmi*= 703 ;
        $data_bmi[] = round( $bmi , 2 );
    }

?>



<script type="text/javascript">
    
    var canvas = document.getElementById('canvas_chart');
    
    new Chart(canvas, {
        type: 'line',
        data: {
            labels: <?= json_encode($data_labels); ?>.map(element => moment(element).format("MMM/DD")),
            datasets: [
            {
                label: 'Peso',
                yAxisID: 'Kg',
                data: <?= json_encode($data_weight); ?>,
                fill:false,
                borderColor: chartColors.red,
            },{
                label: 'Altura',
                yAxisID: 'M',
                data: <?= json_encode($data_height); ?>,
                fill:false,
                borderColor: chartColors.green,
            },{
                label: 'IMC',
                yAxisID: 'IMC',
                data: <?= json_encode($data_bmi); ?>,
                fill:false,
                borderColor: chartColors.blue,
            }]
        },
        options: {
            scales: {
                yAxes: [
                    {
                        id: 'Kg',
                        position: 'left',
                        scaleLabel: {
                            display: true,
                            labelString: "Lb",
                        },
                    },
                    {
                        id: 'M',
                        position: 'right',
                        scaleLabel: {
                            display: true,
                            labelString: "In",
                        },
                    },
                    {
                        id: 'IMC',
                        position: 'right',
                        scaleLabel: {
                            display: true,
                            labelString: "IMC",
                        },
                    }
                ]
            }
        }
    });
</script>


<div class="row">
    <div class="col-md-12 text-center">
        <canvas id="canvas_chart" height=100></canvas>
        <hr>
    </div>
</div>