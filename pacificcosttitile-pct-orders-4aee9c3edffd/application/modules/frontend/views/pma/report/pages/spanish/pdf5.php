<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.6/Chart.js"></script>
    <?php
    $areaSalePrice = minMax('SalePrice', 'all', $main_report);
    $areaSalePrice_array = array(
        0,
        $areaSalePrice['min'],
        $areaSalePrice['median'],
        $areaSalePrice['max'],
        0
    );

    $areaPriceFoot = minMax('PricePerSQFT', 'all', $main_report);
    $areaPriceFoot_array = array(
        0,
        $areaPriceFoot['min'],
        $areaPriceFoot['median'],
        $areaPriceFoot['max'],
        0
    );

    $areaLivingArea = minMax('BuildingArea', 'all', $main_report);
    $areaLivingArea_array = array(
        0,
        $areaLivingArea['min'],
        $areaLivingArea['median'],
        $areaLivingArea['max'],
        0
    );

    $areaLotSize = minMax('LotSize', 'all', $main_report);
    $areaLotSize_array = array(
        0,
        $areaLotSize['min'],
        $areaLotSize['median'],
        $areaLotSize['max'],
        0
    );

    $bedRooms = minMax('Bedrooms', 'all', $main_report);
    $bathRooms = minMax('Baths', 'all', $main_report);
    $yearBuilt = minMax('YearBuilt', 'all', $main_report);
    $earliestDate = minMax('RecordingDate', 'min', $main_report);
    $earliestDate = formatDate($earliestDate);
    $latestDate = date('m/d/Y');
    $current_year = date('Y');
    $areaRadius = minMax('Proximity', 'all', $main_report);

?>
<page class="pdf5">
    <div class="container">
        <img src="<?php echo base_url('assets/pma/report/img/15_spanish.jpg');?>" alt="area_sales">
        <div class="main_content">
        
            <div class="d-flex">
                <div class="col-50">
                    <div class="chart_title">Precio de Venta</div>
                    <canvas id="sale_price" height="165" width="240" ></canvas>
                </div>
                <div class="col-50">
                    <div class="chart_title">Precio Por Pie Cuadrado</div>
                    <canvas id="price_sqft" height="165" width="240" ></canvas>
                </div>
            </div>
            <div class="d-flex">
                <div class="col-50">
                    <div class="chart_title">Área de Vivienda</div>
                    <canvas id="living_area" height="165" width="240" ></canvas>
                </div>
                <div class="col-50">
                    <div class="chart_title">Tamaño del Lote</div>
                    <canvas id="lot_size" height="165" width="240" ></canvas>
                </div>
            </div>
            
            <table class="table1 mt-30">
                <tr>
                    <th></th>
                    <th class="th">BAJO</th>
                    <th class="th">MEDIANúm</th>
                    <th class="th">ALTO</th>
                </tr>
                <tr>
                    <td><span>Dormitorio:</span></td>
                    <td><?php echo $bedRooms['min'] ?></td>
                    <td><?php echo $bedRooms['median'] ?></td>
                    <td><?php echo $bedRooms['max'] ?></td>
                </tr>
                <tr>
                    <td><span>Baños:</span></td>
                    <td><?php echo $bathRooms['min'] ?></td>
                    <td><?php echo $bathRooms['median'] ?></td>
                    <td><?php echo $bathRooms['max'] ?></td>
                </tr>
                <tr>
                    <td><span>Tamaño del Lote:</span></td>
                    <td>
                        <?php echo number_format($areaLotSize['min'],2); ?>
                    </td>
                    <td>
                        <?php echo number_format($areaLotSize['median'],2); ?>
                    </td>
                    <td>
                        <?php echo number_format($areaLotSize['max'],2); ?>
                    </td>
                </tr>
                <tr>
                    <td><span>Área de Vivienda :</span></td>
                    <td>
                        <?php echo number_format($areaLivingArea['min'],2); ?>
                    </td>
                    <td>
                        <?php echo number_format($areaLivingArea['median'],2); ?>
                    </td>
                    <td>
                        <?php echo number_format($areaLivingArea['max'],2); ?>
                    </td>
                </tr>
                <tr>
                    <td><span>Precio de Venta:</span></td>
                    <td>
                        <?php echo dollars(number_format($areaSalePrice['min'],2)); ?>
                    </td>
                    <td>
                        <?php echo dollars(number_format($areaSalePrice['median'],2)); ?>
                    </td>
                    <td>
                        <?php echo dollars(number_format($areaSalePrice['max'],2)); ?>
                    </td>
                </tr>
                <tr>
                    <td><span>Año de Construcción:</span></td>
                    <td><?php echo $yearBuilt['min'] ?></td>
                    <td><?php echo $yearBuilt['median'] ?></td>
                    <td><?php echo $yearBuilt['max'] ?></td>
                </tr>
                <tr>
                    <td><span>Edad:</span></td>
                    <td><?php echo ($current_year - $yearBuilt['max']); ?></td>
                    <td><?php echo ($current_year -$yearBuilt['median']); ?></td>
                    <td><?php echo ($current_year -$yearBuilt['min']); ?></td>
                </tr>
            </table>
                <div class="table_title2 pl-0">Criterios de Búsqueda</div>
            <table>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <span>Rango de Fechas: </span> <?php echo $earliestDate . ' - ' . $latestDate; ?>
                    </td>
                    <td>
                        <span>Radio de Búsqueda:</span> <?php echo  $areaRadius['min'] + ' - ' + $areaRadius['max']  + ' Miles'; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Área Habitacional Mínima:</span> <?php echo number_format($areaLivingArea['min'],2); ?>
                    </td>
                    <td>
                        <span>Área Habitacional Máxima:</span> <?php echo number_format($areaLivingArea['max'],2); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Mínimo de Dormitorios:</span> <?php echo $bedRooms['min'] ?>
                    </td>
                    <td>
                        <span>Máximo de Dormitorios:</span> <?php echo $bedRooms['max'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Mínimo de Baños: </span> <?php echo $bathRooms['min'] ?>
                    </td>
                    <td>
                        <span>Máximo de Baños:</span> <?php echo $bathRooms['max'] ?>
                    </td>
                </tr>
            </table>
            <a href="#" class="mt-30 d-block"><img src="<?php echo base_url('assets/pma/report/img/pacific_logo.png');?>" alt="pacific_logo" class="footer_logo"></a>
            <p class="copyright">Datos Considerados Fiables, Pero Núm.Garantizados. Pacific Coast Title Company. Todos los Derechos Reservados.</p>
        </div>
    </div>
</page>

<script type="text/javascript">
function create_chart(chart_id,chart_data) {

    var ctx = document.getElementById(chart_id);
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: chart_data,
        datasets: [{
          label: '# of Tomatoes',
          data: chart_data,
          backgroundColor: [
            'rgb(0,0,0)',
            'rgb(0,166,220)',
            'rgb(169, 174, 175)',
            'rgb(255, 92, 21)',
            'rgb(0,0,0)'
          ],
        }]
      },
      options: {
        animation: false,
        legend: {
            display: false
        },
        responsive: false,
        scales: {
          xAxes: [{
            categoryPercentage: 0.99,
            barPercentage: 0.99,
            display: false ,
            maintainAspectRatio : false,
            ticks: {
              display: false 
            },
              gridLines: {
              offsetGridLines: true // à rajouter
            }
          }
          ],
          yAxes: [{
            ticks: {
              beginAtZero: true,
              callback: function(value, index, values) {
                            if(parseInt(value) >= 1000){
                               return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            } else {
                               return value;
                            }
                       }  
            }
          }]
        }
      }
    });
}
create_chart('sale_price',<?php echo json_encode($areaSalePrice_array)?>);
create_chart('price_sqft',<?php echo json_encode($areaPriceFoot_array)?>);
create_chart('living_area',<?php echo json_encode($areaLivingArea_array)?>);
create_chart('lot_size',<?php echo json_encode($areaLotSize_array)?>);

  </script>
