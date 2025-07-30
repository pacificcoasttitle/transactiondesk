<page class="pdf4">
    <div class="container">
        <img src="<?php echo base_url('assets/pma/report/img/12_spanish.jpg');?>" alt="transfer_history">
        <div class="main_content">
            <?php
            $transfer_i = 0;
            foreach ($main_report->TransferHistory->TransferWithDefault as $key => $transferHistory) { 
                $transfer_i++;
                ?>
            <table>
                <tr>
                    <td><div class="table_title2 pl-0">TRANSFERENCIA:</div></td>
                </tr>
                <tr>
                    
                    <td>
                        <span>Datos de Registro de Prueba</span> <?php echo formatDate($transferHistory->RecordingDate);?>
                    </td>
                    <td>
                        <span> Número de Documento</span> <?php echo $transferHistory->DocumentNumber;?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Precio</span> <?php echo dollars(number_format((double)$transferHistory->SalePrice));?>
                    </td>
                    <td>
                        <span>Ciudad/Municipio/Distrito</span> <?php echo properCase($transferHistory->LegalDescriptionInfo->CityMuniTwp); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Primer TD</span> <?php echo dollars(number_format((double)$transferHistory->Loan1Amount)); ?>
                    </td>
                    <td>
                        <span>Tipo de Documento</span> <?php echo $transferHistory->DocumentType; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Nombre del Prestamista</span> <?php echo properCase(formatName($transferHistory->LenderName)); ?>
                    </td>
                    <td>
                        <span>Número de Documento</span> <?php echo $transferHistory->MortDoc; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span>Nombre del Comprador </span> <?php echo properCase(formatName($transferHistory->BuyerName)); ?>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <span>Nombre del Vendedor</span> <?php echo properCase(formatName($transferHistory->SellerName)); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span>Tipo de Venta </span> <?php echo properCase($transferHistory->SaleType); ?>
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
            <p class="copyright">Datos Considerados Fiables, Pero Núm.Garantizados. Pacific Coast Title Company. Todos los Derechos Reservados.</p>
        </div>
    </div>
</page>