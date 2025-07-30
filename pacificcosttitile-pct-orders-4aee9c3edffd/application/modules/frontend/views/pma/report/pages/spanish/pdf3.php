<page class="pdf3">
    <div class="container">
        <img src="<?php echo base_url('assets/pma/report/img/1_spanish.jpg');?>" alt="property_profile">
        <div class="main_content">
            <div class="table_title orange_text">PROPIETARIO, DIRECCIÓN Y DESCRIPCIÓN LEGAL</div>
            <table class="border_table orange_border">
                <tr>
                    <td colspan="2">
                        <span>Propietario Principal: </span> <?php echo $main_report->PropertyProfile->PrimaryOwnerName; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><span>Propietario Secundario:</span><?php echo $main_report->PropertyProfile->SecondaryOwnerName; ?></td>
                </tr>
                <tr>
                    <td colspan="2"><span>Dirección del Sitio: </span> <?php echo $main_report->PropertyProfile->SiteAddress; ?> <?php echo $main_report->PropertyProfile->SiteUnitType; ?> <?php echo $main_report->PropertyProfile->SiteUnit; ?> <?php echo $main_report->PropertyProfile->SiteCity; ?>, <?php echo $main_report->PropertyProfile->SiteState; ?>,  <?php echo $main_report->PropertyProfile->SiteZip; ?></td>
                </tr>
                <tr>
                    <td colspan="2"><span>Dirección Postal:</span> <?php echo $main_report->PropertyProfile->MailAddress; ?> <?php echo $main_report->PropertyProfile->MailUnitType; ?> <?php echo $main_report->PropertyProfile->MailUnit; ?> <?php echo $main_report->PropertyProfile->MailCity; ?>, <?php echo $main_report->PropertyProfile->MailState; ?>,  <?php echo $main_report->PropertyProfile->MailZip; ?></td>
                </tr>
                <tr>
                    <td>
                        <span>APN :</span> <?php echo $main_report->PropertyProfile->APN; ?>
                    </td>
                    <td>
                        <span>Nombre del Condado:</span> <?php echo $main_report->SubjectValueInfo->CountyName; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Sección del Censo: </span> <?php echo $main_report->PropertyProfile->CensusTract; ?>
                    </td>
                    <td>
                        <span> Número de Área de Vivienda: </span> <?php echo $main_report->PropertyProfile->HousingTract; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Número de Lote:</span> <?php echo $main_report->PropertyProfile->HousingTract; ?>
                    </td>
                    <td>
                        <span>Cuadrícula de Página:</span><?php echo $main_report->PropertyProfile->TBMPage; ?> <?php echo $main_report->PropertyProfile->TBMGrid; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span>Descripción Legal Breve:</span> 1<?php echo $main_report->PropertyProfile->LegalDescriptionInfo->LegalBriefDescription; ?>
                    </td>
                </tr>
            </table>
            <div class="table_title orange_text">DORMITORIOS, BAÑOS Y METROS CUADRADOS</div>
            <table class="border_table sky_border">
                <tr>
                    <td><span>Dormitorios:</span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->Bedrooms; ?></td>
                    <td><span>Año de Const.:</span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->YearBuilt; ?></td>
                    <td><span>Metros Cuadrados: </span> <?php echo number_format((double)$main_report->PropertyProfile->PropertyCharacteristics->BuildingArea, 2, '.', ','); ?></td>
                </tr>
                <tr>
                    <td><span>Baños:</span>  <?php echo $main_report->PropertyProfile->PropertyCharacteristics->Baths; ?></td>
                    <td><span>Garaje: </span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->GarageNumCars; ?></td>
                    <td><span>Lote:</span> <?php 
                            $propLotSizeUnits = $main_report->PropertyProfile->PropertyCharacteristics->LotSizeUnits;
                            if ($propLotSizeUnits == 'AC') {
                                echo number_format((double)$main_report->PropertyProfile->PropertyCharacteristics->LotSize, 2, '.', ',') . ' ' . $propLotSizeUnits;
                            }
                            else {
                                echo number_format((double)$main_report->PropertyProfile->PropertyCharacteristics->LotSize) . ' ' . $propLotSizeUnits;
                            } ?></td>
                </tr>
                <tr>
                    <td><span>Baño Parcial:</span></td>
                    <td><span>Chimenea:</span><?php echo $main_report->PropertyProfile->PropertyCharacteristics->Fireplace; ?></td>
                    <td><span>Unidades: </span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->NumUnits; ?></td>
                </tr>
                <tr>
                    <td><span>Total de Habitaciones:</span><?php echo $main_report->PropertyProfile->PropertyCharacteristics->TotalRooms; ?></td>
                    <td><span>Piscina/Spa: </span><?php echo $main_report->PropertyProfile->PropertyCharacteristics->Pool; ?></td>
                    <td><span>Zonificación: </span> <?php echo $main_report->PropertyProfile->PropertyCharacteristics->Zoning; ?></td>
                </tr>
                <tr>
                    <td colspan="3"><span>Tipo de Propiedad: </span><?php echo $main_report->PropertyProfile->PropertyCharacteristics->UseCode; ?></td>
                </tr>
                <tr>
                    <td colspan="3"><span>Código de Uso:</span><?php echo $main_report->PropertyProfile->PropertyCharacteristics->UseCode; ?></td>
                </tr>
            </table>
            <div class="table_title orange_text">INFORMACIÓN DE LA TRANSFERENCIA MÁS RECIENTE</div>
            <table class="border_table gray_border">
                <tr>
                    <td><span>Fecha de Venta:</span> <?php echo formatDate($main_report->PropertyProfile->SaleLoanInfo->TransferDate); ?></td>
                    <td><span>Número de Documento:</span> <?php echo $main_report->PropertyProfile->SaleLoanInfo->DocumentNumber; ?></td>
                </tr>
                <tr>
                    <td><span>Monto de la Venta:</span> <?php echo dollars(number_format((double)$main_report->PropertyProfile->SaleLoanInfo->SalesPrice, 2, '.', ',')); ?></td>
                    <td><span>Costo/Metro Cuadrado:</span> <?php echo dollars(number_format((double)$main_report->PropertyProfile->SaleLoanInfo->PricePerSQFT, 2, '.', ','));?></td>
                </tr>
                <tr>
                    <td colspan="2"><span>Vendedor:</span> <?php echo properCase(formatName($main_report->PropertyProfile->SaleLoanInfo->SellerName)) ;?></td>
                </tr>
                <tr>
                    <td colspan="2"><span>Prestamista</span><?php echo properCase(formatName($main_report->PropertyProfile->SaleLoanInfo->LenderName)) ;?></td>
                </tr>
            </table>
            <div class="table_title orange_text">DETALLES DEL VALOR TASADO E IMPUESTOS</div>
            <table class="border_table blue_border">
                <tr>
                    <td><span>Valor Tasado:</span> <?php echo dollars(number_format((double)$main_report->PropertyProfile->AssessmentTaxInfo->AssessedValue,2, '.', ',')); ?></td>
                    <td><span>Monto de Impuestos:</span> <?php echo dollars(number_format((double)$main_report->PropertyProfile->AssessmentTaxInfo->TaxAmount,2, '.', ',')); ?></td>
                </tr>
                <tr>
                    <td><span>Valor del TerreNúm.</span>  <?php echo dollars(number_format((double)$main_report->PropertyProfile->AssessmentTaxInfo->LandValue,2, '.', ',')); ?></td>
                    <td><span>Estado de los Impuestos:</span> <?php echo $main_report->PropertyProfile->AssessmentTaxInfo->TaxStatus; ?></td>
                </tr>
                <tr>
                    <td><span>Valor de las Mejoras: </span> <?php echo dollars(number_format((double)$main_report->PropertyProfile->AssessmentTaxInfo->ImprovementValue,2, '.', ',')); ?></td>
                    <td><span>Área de Tasa de Impuestos: </span> <?php echo $main_report->PropertyProfile->AssessmentTaxInfo->TaxRateArea; ?></td>
                </tr>
                <tr>
                    <td><span>% de Mejoras:</span> <?php echo toPercent($main_report->PropertyProfile->AssessmentTaxInfo->PercentImproved); ?></td>
                    <td><span>Año Fiscal:</span><?php echo $main_report->PropertyProfile->AssessmentTaxInfo->TaxYear; ?></td>
                </tr>
            </table>
            <a href="#"><img src="<?php echo base_url('assets/pma/report/img/pacific_logo.png');?>" alt="pacific_logo" class="footer_logo"></a>
            <p class="copyright">Datos Considerados Fiables, Pero Núm.Garantizados. Pacific Coast Title Company. Todos los Derechos Reservados.</p>
        </div>
    </div>
</page>





   

