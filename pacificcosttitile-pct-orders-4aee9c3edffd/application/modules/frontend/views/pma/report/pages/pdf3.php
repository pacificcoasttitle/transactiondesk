    <page class="pdf3">
        <div class="container">
            <img src="<?php echo base_url('assets/pma/report/img/1.jpg');?>" alt="property_profile">
            <div class="main_content">
                <div class="table_title orange_text">OWNER, ADDRESS & LEGAL DESCRIPTION</div>
                <table class="border_table orange_border">
                    <tr>
                        <td colspan="2">
                            <span>Primary Owner:</span> <?php echo $main_report->PropertyProfile->PrimaryOwnerName; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><span>Secondary Owner:</span> <?php echo $main_report->PropertyProfile->SecondaryOwnerName; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><span>Site Address:</span> <?php echo $main_report->PropertyProfile->SiteAddress; ?> <?php echo $main_report->PropertyProfile->SiteUnitType; ?> <?php echo $main_report->PropertyProfile->SiteUnit; ?> <?php echo $main_report->PropertyProfile->SiteCity; ?>, <?php echo $main_report->PropertyProfile->SiteState; ?>,  <?php echo $main_report->PropertyProfile->SiteZip; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><span>Mailing Address:</span> <?php echo $main_report->PropertyProfile->MailAddress; ?> <?php echo $main_report->PropertyProfile->MailUnitType; ?> <?php echo $main_report->PropertyProfile->MailUnit; ?> <?php echo $main_report->PropertyProfile->MailCity; ?>, <?php echo $main_report->PropertyProfile->MailState; ?>,  <?php echo $main_report->PropertyProfile->MailZip; ?></td>
                    </tr>
                    <tr>
                        <td>
                            <span>APN :</span> <?php echo $main_report->PropertyProfile->APN; ?>
                        </td>
                        <td>
                            <span>County Name:</span> <?php echo $main_report->SubjectValueInfo->CountyName; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Census Tract:</span> <?php echo $main_report->PropertyProfile->CensusTract; ?>
                        </td>
                        <td>
                            <span>Housing Tract #</span> <?php echo $main_report->PropertyProfile->HousingTract; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Lot Number:</span> <?php echo $main_report->PropertyProfile->LotNumber; ?>
                        </td>
                        <td>
                            <span>Page Grid:</span> <?php echo $main_report->PropertyProfile->TBMPage; ?> <?php echo $main_report->PropertyProfile->TBMGrid; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span>Brief Legal Description:</span><?php echo $main_report->PropertyProfile->LegalDescriptionInfo->LegalBriefDescription; ?>
                        </td>
                    </tr>
                </table>
                <div class="table_title orange_text">BEDS, BATHS, & SQUARE FOOTAGE</div>
                <table class="border_table sky_border">
                    <tr>
                        <td><span>Bedrooms:</span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->Bedrooms; ?></td>
                        <td><span>Year Built:</span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->YearBuilt; ?></td>
                        <td><span>Square Feet:</span> <?php echo number_format((double)$main_report->PropertyProfile->PropertyCharacteristics->BuildingArea, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td><span>Bathrooms:</span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->Baths; ?></td>
                        <td><span>Garage:</span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->GarageNumCars; ?></td>
                        <td><span>Lot Size:</span> <?php 
                            $propLotSizeUnits = $main_report->PropertyProfile->PropertyCharacteristics->LotSizeUnits;
                            if ($propLotSizeUnits == 'AC') {
                                echo number_format((double)$main_report->PropertyProfile->PropertyCharacteristics->LotSize, 2, '.', ',') . ' ' . $propLotSizeUnits;
                            }
                            else {
                                echo number_format((double)$main_report->PropertyProfile->PropertyCharacteristics->LotSize) . ' ' . $propLotSizeUnits;
                            } ?></td>
                    </tr>
                    <tr>
                        <td><span>Partial Bath:</span></td>
                        <td><span>Fireplace:</span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->Fireplace; ?> </td>
                        <td><span># of Units:</span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->NumUnits; ?></td>
                    </tr>
                    <tr>
                        <td><span>Total Rooms:</span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->TotalRooms; ?> </td>
                        <td><span>Pool/Spa:</span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->Pool; ?> </td>
                        <td><span>Zoning:</span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->Zoning; ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><span>Property Type:</span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->UseCode; ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><span>Use Code:</span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->UseCode; ?></td>
                    </tr>
                </table>
                <div class="table_title orange_text">MOST RECENT TRANSFER INFORMATION</div>
                <table class="border_table gray_border">
                    <tr>
                        <td><span>Sale Date:</span> <?php echo formatDate($main_report->PropertyProfile->SaleLoanInfo->TransferDate); ?></td>
                        <td><span>Document #:</span> <?php echo $main_report->PropertyProfile->SaleLoanInfo->DocumentNumber; ?></td>
                    </tr>
                    <tr>
                        <td><span>Sale Amount:</span> <?php echo dollars(number_format((double)$main_report->PropertyProfile->SaleLoanInfo->SalesPrice, 2, '.', ',')); ?></td>
                        <td><span>Cost /SqFt:</span> <?php echo dollars(number_format((double)$main_report->PropertyProfile->SaleLoanInfo->PricePerSQFT, 2, '.', ','));?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><span>Seller:</span> <?php echo properCase(formatName($main_report->PropertyProfile->SaleLoanInfo->SellerName)) ;?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><span>Lender</span> <?php echo properCase(formatName($main_report->PropertyProfile->SaleLoanInfo->LenderName)) ;?> </td>
                    </tr>
                </table>
                <div class="table_title orange_text">ASSESSED VALUE & TAX DETAILS</div>
                <table class="border_table blue_border">
                    <tr>
                        <td><span>Assessed Value:</span> <?php echo dollars(number_format((double)$main_report->PropertyProfile->AssessmentTaxInfo->AssessedValue,2, '.', ',')); ?></td>
                        <td><span>Tax Amount:</span> <?php echo dollars(number_format((double)$main_report->PropertyProfile->AssessmentTaxInfo->TaxAmount,2, '.', ',')); ?></td>
                    </tr>
                    <tr>
                        <td><span>Land Value:</span> <?php echo dollars(number_format((double)$main_report->PropertyProfile->AssessmentTaxInfo->LandValue,2, '.', ',')); ?></td>
                        <td><span>Tax Status:</span> <?php echo $main_report->PropertyProfile->AssessmentTaxInfo->TaxStatus; ?></td>
                    </tr>
                    <tr>
                        <td><span>Improvement Value:</span> <?php echo dollars(number_format((double)$main_report->PropertyProfile->AssessmentTaxInfo->ImprovementValue,2, '.', ',')); ?></td>
                        <td><span>Tax Rate Area:</span> <?php echo $main_report->PropertyProfile->AssessmentTaxInfo->TaxRateArea; ?></td>
                    </tr>
                    <tr>
                        <td><span>% Improvement:</span> <?php echo toPercent($main_report->PropertyProfile->AssessmentTaxInfo->PercentImproved); ?></td>
                        <td><span>Tax Year:</span><?php echo $main_report->PropertyProfile->AssessmentTaxInfo->TaxYear; ?></td>
                    </tr>
                </table>
                <a href="#"><img src="<?php echo base_url('assets/pma/report/img/pacific_logo.png');?>" alt="pacific_logo" class="footer_logo"></a>
                <p class="copyright">Data Deemed Reliable, But Not Guaranteed. Pacific Coast Title Company. All Rights Reserved.</p>
            </div>
        </div>
    </page>