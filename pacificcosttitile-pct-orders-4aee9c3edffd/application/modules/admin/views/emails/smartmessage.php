<?php  
?>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title>Smart forms - Email message template </title>
      <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700,800" rel="stylesheet">
   </head>
   <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
      <center>
         <table style="padding:30px 10px;background:#F4F4F4;width:100%;font-family:arial" cellpadding="0" cellspacing="0">
            <tbody>
               <tr>
                  <td>
                     <table style="max-width:540px;min-width:320px" align="center" cellspacing="0">
                        <tbody>
                           <tr>
                              <td style="background:#fff;border:1px solid #D8D8D8;padding:30px 30px" align="center">
                                 <table align="center">
                                    <tbody>
                                       <tr>
                                          <td style="border-bottom:1px solid #D8D8D8;color:#666;text-align:center;padding-bottom:30px">
                                             <table style="margin:auto" align="center">
                                                <tbody>
                                                   <tr>
                                                      <td style="margin-top:;font-size: 22px; font-family:Montserrat; Font-weight: 800; color: #04415D;text-transform: uppercase; text-align:center;">
                                                         NEW ORDER DETAILS  
                                                      </td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td style="color:#666;padding:15px; padding-bottom:0;font-size:14px;line-height:20px;font-family:arial;text-align:left">
                                             <div style="font-style:normal;padding-bottom:15px;font-family:arial;line-height:20px;text-align:left">
                                                <p><span style="font-weight:bold;font-size:16px">Opened By:</span> <?php echo $OpenName; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px">Open Mail:</span> <?php echo $OpenEmail; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px">Open Telephone:</span> <?php echo $Opentelephone; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px">Open Role:</span> <?php echo $OpenRole; ?></p>
                                                <br>
                                                <p style="margin-top:;font-size: 18px; font-family:Montserrat; Font-weight: 800; color: #04415D;text-transform: uppercase;">Additional Partners</p>
                                                <br>
                                                <p><span style="font-weight:bold;font-size:16px">Partner Name:</span> <?php echo $PartnerName; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px">Partner Email:</span> <?php echo $ParnterEmailaddress; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px">Partner Telephone:</span> <?php echo $PartnerTelephone; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px">Partner Role:</span> <?php echo $PartnerRole; ?></p>
                                                <br>
                                                <p style="margin-top:;font-size: 18px; font-family:Montserrat; Font-weight: 800; color: #04415D;text-transform: uppercase;">Property Details</p>
                                                <br>
                                                <p><span style="font-weight:bold;font-size:16px">Property Details:</span> <?php echo $Property; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px">Sales Rep:</span> <?php echo $SalesRep; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px">Title Officer:</span> <?php echo $TitleOfficer; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px">Sale Loan Amount:</span> <?php echo $LoanAmount; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px">Transaction Type:</span> <?php echo $Refinance; ?><?php echo $Purchase; ?><?php echo $Commercial; ?></p>
                                                <br>
                                                <p style="margin-top:;font-size: 18px; font-family:Montserrat; Font-weight: 800; color: #04415D;text-transform: uppercase;">Selling & Buying Parties</p>
                                                <br>
                                                <p><span style="font-weight:bold;font-size:16px">Partner Name:</span> <?php echo $SellingParty; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px">Partner Email:</span> <?php echo $SellerEmail; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px">Partner Telephone:</span> <?php echo $SellerTelephone; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px">Partner Role:</span> <?php echo $Role; ?></p>
                                                <br>
                                                <p style="margin-top:;font-size: 18px; font-family:Montserrat; Font-weight: 800; color: #04415D;text-transform: uppercase;">Special Instructions</p>
                                                <br>
                                                <p><span style="font-weight:bold;font-size:16px">Add-Ons:</span> <?php echo $CCR; ?>, <?php echo $Docs; ?> , <?php echo $Ease; ?><?php echo $Rush; ?></p>
                                                <p><span style="font-weight:bold;font-size:16px;">Sender message below:</span> </p>
                                                <p style="margin-bottom:0;"> <?php echo nl2br($sendermessage); ?> </p>
                                             </div>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </td>
                           </tr>
                           <tr>
                              <td style="background:#f9f9f9;border:1px solid #D8D8D8;border-top:none;padding:24px 10px" align="center">
                                 <table style="width:100%;max-width:650px" align="center">
                                    <tbody>
                                       <tr>
                                          <td style="font-size:20px;line-height:27px;text-align:center;max-width:650px">
                                             <a href="<?php echo $poweredby_url; ?>" style="text-decoration:none;color:#D35400" target="_blank">
                                             <span style="color:#04415D;font-weight:bold;max-width:180px">POWERED BY:</span> 
                                             <?php echo $poweredby_name; ?>
                                             </a>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td>
                     <table style="max-width:650px" align="center">
                        <tbody>
                           <tr>
                              <td style="color:#b4b4b4;font-size:11px;padding-top:10px;line-height:15px;font-family:arial">
                                 <span> &copy; Pacific Coast Title 2019 - <?php echo $currYear; ?> - ALL RIGHTS RESERVED </span>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </td>
               </tr>
            </tbody>
         </table>
      </center>
   </body>
</html>