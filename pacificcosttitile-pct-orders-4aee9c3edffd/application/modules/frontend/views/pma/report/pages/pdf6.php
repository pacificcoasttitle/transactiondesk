    <page class="pdf6">
        <div class="container">
            <img src="<?php echo base_url('assets/pma/report/img/14.jpg');
            ?>" alt="sales_comparable">
            <div class="main_content">
                <!-- <img src="<?php echo base_url('assets/pma/report/img/map.png');?>" alt="map"> -->
                <img src="<?php echo createMap($main_report);?>" alt="map">

                <?php
                $compare_i = 0;
                foreach($comparableSales as $comparableSale) { ?>
                        <table class="table2 mt-30 bt-30">
                            <tr>
                                <th>No.</th>
                                <th>Date Sold</th>
                                <th>Sales Price</th>
                                <th>SqFt</th>
                                <th>Lot Size</th>
                                <th>Beds</th>
                                <th>Baths</th>
                                <th>Yr Built</th>
                                <th>Pool</th>
                            </tr>
                            <tr>
                                <td class="number" rowspan="2"><?php echo ($compare_i+1) ?></td>
                                <td><?php echo formatDate($comparableSale->RecordingDate); ?></td>
                                <td><?php echo dollars(number_format((double)$comparableSale->SalePrice)); ?></td>
                                <td><?php echo number_format((double)$comparableSale->BuildingArea); ?></td>
                                <td><?php echo number_format((double)$comparableSale->LotSize); ?></td>
                                <td><?php echo properCase(cleanLegal($comparableSale->Bedrooms)); ?></td>
                                <td><?php echo properCase(cleanLegal($comparableSale->Baths)); ?></td>
                                <td><?php echo $comparableSale->YearBuilt; ?></td>
                                <td><?php echo $comparableSale->Pool; ?></td>
                            </tr>
                            <tr>
                                <td colspan="9" class="p-0">
                                    <table class="mb-0">
                                        <tr>
                                            <?php
                                            $comparableStreet = $comparableSale->SiteAddress;
                                            $comparableUnitType = $comparableSale->SiteUnitType;
                                            $comparableUnitNum = $comparableSale->SiteUnit;
                                            $comparableAddress = $comparableStreet . ' ' . $comparableUnitType . ' ' . $comparableUnitNum; 
                                            ?>
                                            <td><span>Address:</span> <?php echo properCase($comparableAddress); ?></td>
                                            <td><span>APN:</span> <?php echo $comparableSale->APN; ?></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $sellerFName = $comparableSale->Seller1FName;
                                            $sellerLName = $comparableSale->Seller1LName;
                                            $sellerName = $sellerFName . ' ' . $sellerLName;
                                            ?>
                                            <td colspan="3"><span>Seller:</span> <?php echo properCase($sellerName); ?></td>
                                        </tr>
                                        
                                        <tr>
                                            <td><span>Land Use:</span><?php echo $comparableSale->UseCodeDescription; ?></td>
                                            <td><span>$/Sqft:</span><?php echo number_format((double)$comparableSale->BuildingArea) ?></td>
                                            <td><span>Proxim:</span><?php echo $comparableSale->Proximity; ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                        </table>

                        <?php
                        $compare_i++;

                        if($compare_i >=3) {
                            break;
                        }
                    }
                 ?>
                
                <a href="#" class="mt-30 d-block"><img src="<?php echo base_url('assets/pma/report/img/pacific_logo.png');?>" alt="pacific_logo" class="footer_logo"></a>
                <p class="copyright">Data Deemed Reliable, But Not Guaranteed. Pacific Coast Title Company. All Rights Reserved.</p>
            </div>
        </div>
    </page>