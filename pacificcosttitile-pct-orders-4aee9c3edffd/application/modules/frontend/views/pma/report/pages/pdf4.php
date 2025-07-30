    <page class="pdf4">
        <div class="container">
            <img src="<?php echo base_url('assets/pma/report/img/12.jpg');?>" alt="transfer_history">
            <div class="main_content">
                <?php
                $transfer_i = 0;
                foreach ($main_report->TransferHistory->TransferWithDefault as $key => $transferHistory) { 
                    $transfer_i++;
                    ?>
                   
                    <table>
                        <tr>
                            <td><div class="table_title2 pl-0">Transfer:</div></td>
                        </tr>
                        <tr>
    					  
                            <td>
                                <span>Recording date</span> <?php echo formatDate($transferHistory->RecordingDate);?>
                            </td>
                            <td>
                                <span>Document #</span> <?php echo $transferHistory->DocumentNumber;?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Price</span> <?php echo dollars(number_format((double)$transferHistory->SalePrice));?>
                            </td>
                            <td>
                                <span>City/Muni/Twp</span> <?php echo properCase($transferHistory->LegalDescriptionInfo->CityMuniTwp); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>First TD</span> <?php echo dollars(number_format((double)$transferHistory->Loan1Amount)); ?>
                            </td>
                            <td>
                                <span>Document Type</span> <?php echo $transferHistory->DocumentType; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Lender Name</span> <?php echo properCase(formatName($transferHistory->LenderName)); ?>
                            </td>
                            <td>
                                <span>Mortgage Doc#</span> <?php echo $transferHistory->MortDoc; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span>Buyer Name</span> <?php echo properCase(formatName($transferHistory->BuyerName)); ?>
                            </td>
                        </tr>
                       
                        <tr>
                            <td colspan="2">
                                <span>Seller Name</span> <?php echo properCase(formatName($transferHistory->SellerName)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span>Type of Sale</span> <?php echo properCase($transferHistory->SaleType); ?>
                            </td>
                        </tr>
                        
                    </table>
                <?php
                    if($transfer_i >= 3) {
                        break;
                    } 
                }
                ?>
                <a href="#"><img src="<?php echo base_url('assets/pma/report/img/pacific_logo.png');?>" alt="pacific_logo" class="footer_logo"></a>
                <p class="copyright">Data Deemed Reliable, But Not Guaranteed. Pacific Coast Title Company. All Rights Reserved.</p>
            </div>
        </div>
    </page>