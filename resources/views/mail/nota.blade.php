<table width="100%">
  <tbody><tr>
    <td>
      
      <table align="center" style="width:450px;border:1px solid #ddd;background:#f6f6f6" cellpadding="0" cellspacing="0">
        <tbody><tr style="background:#e87d34;color:#fff;text-align:center">
          <td>
            <table width="100%" style="border-top:20px solid #e87d34;">
              <tbody>
                <?php if(isset($data['url_logo']) && $data['url_logo'] != "" && !is_null($data['url_logo'])){ ?>
              <tr>
                <td style="text-align:center">
                  <div>
                    <img width="100px" height="auto" style="border-radius:9px" src="<?= $data['url_logo'] ?>">
                  </div>
                </td>
              </tr>
            <?php } ?>
              <tr>
                <td style="text-align:center;color:#ffffff;padding-top:10px">
                  <h2 style=" text-align:center; font-weight:600;font-size:20px;padding:0 15px;"><?= $data['nama_outlet'] ?></h2>
                  <p style="text-align:center; padding:0 30px;"><?= $data['alamat'] ?? '-' ?></p>
                  <p style="text-align:center; margin-bottom: 15px;">
                    <?= $data['telpon'] ?>
                  </p>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
        <tr>
          <td>
            <table width="100%" align="center" style="border-left:20px solid #f6f6f6;border-right:20px solid #f6f6f6" cellpadding="0" cellspacing="0">
              <tbody>
              <tr>
                <td>
                  
                  <table align="center" width="100%" style="border-top:20px solid #f6f6f6;text-align:center" cellpadding="0" cellspacing="0">
                    <tbody><tr>
                      <td style="background:#fff;padding:20px;font-size:35px;color:#2c3e50">
                        <?= $data['total'] ?>
                      </td>
                    </tr>
                  </tbody></table>
                  
                </td>
              </tr>
              <tr>
                <td>
                  
                  <table align="center" width="100%" style="text-align:center;background:#fff" cellpadding="0" cellspacing="0">
                    <tbody><tr>
                      <td>
                        <table align="center" width="100%" cellpadding="0" cellspacing="0" style="border-top:0px;padding-left:15px;padding-right:15px;padding-top:0">
                          <tbody>
                            <tr>
                              <td colspan="3" style="border-bottom:1px dotted #ddd;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td style="color:#6e6b6b;text-align:left;font-size:13px;padding-bottom:10px;padding-top:15px;" colspan="2">
                    <?= $data['tanggal_proses'] ?>
                                
                              </td>
                              <td style="color:#6e6b6b;text-align:right;font-size:13px;padding-bottom:10px">
                    <?= $data['waktu_proses'] ?>
                              
                              </td>
                            </tr>

                            <tr>
                              <td style="color:#6e6b6b;text-align:left;font-size:13px;padding-bottom:10px" colspan="2">
                                Receipt Number
                              </td>
                              <td style="color:#6e6b6b;text-align:right;font-size:13px;padding-bottom:10px">
                              <?= $data['kode_pemesanan'] ?>
                              </td>
                            </tr>

                            <?php if(isset($data['email_pelanggan']) && $data['email_pelanggan'] != "" && !is_null($data['email_pelanggan'])){ ?>
                            <tr>
                              <td style="color:#6e6b6b;text-align:left;font-size:13px;padding-bottom:10px">
                                Customer
                              </td>
                              <td style="color:#6e6b6b;text-align:right;font-size:13px;padding-bottom:10px" colspan="2">
                                <a href="mailto:<?= $data['email_pelanggan']?>" target="_blank"><?= $data['email_pelanggan']?></a>
                              </td>
                            </tr>
                          <?php } ?>

                            <?php if(isset($data['nama_user']) && $data['nama_user'] != "" && !is_null($data['nama_user'])){ ?>
                            <tr>
                              <td style="color:#6e6b6b;text-align:left;font-size:13px;padding-bottom:10px">
                                Collected By
                              </td>
                              <td style="color:#6e6b6b;text-align:right;font-size:13px;padding-bottom:10px" colspan="2">
                              <?= $data['nama_user'] ?>
                              </td>
                            </tr>
                          <?php } ?>

                            <tr>
                              <td colspan="3" style="border-bottom:1px dotted #ddd;padding-top:10px"></td>
                            </tr>

                            <!-- INI LIST PESANANNYA -->
                            <?php foreach($data['pesanan'] as $d){ ?>
                            <tr>
                              <td style="padding:10px;text-align:center;" colspan="3">*<?= $d['nama_tipe_penjualan'] ?>*</td>
                            </tr>
                            <?php foreach($d['menu'] as $v) { ?>
                            <tr>
                              <td style="text-align:left;padding-top:10px;font-size:16px" colspan="3">
                                <div style="margin-bottom:4px;"><b><?= $v['nama_menu'] ?></b></div> 
                                <?php if(isset($v['nama_variasi_menu']) && $v['nama_variasi_menu'] != "" && !is_null($v['nama_variasi_menu'])){ ?>
                                  <span style="padding-top:10px">
                                    <?= $v['nama_variasi_menu'] ?>
                                  </span>
                                  <?php } ?>
                              </td>
                            </tr>

                            <tr>
                              <td style="text-align:left;padding-top:8px" colspan="2">
                                <?= $v['jumlah'] ?>x&nbsp;&nbsp; <?= $v['harga'] ?>
                              </td>
                              <td style="text-align: right;"><?= $v['total'] ?></td>
                            </tr>

                          <?php } } ?>




                          <!-- INI BATAS LIST PESANANNYA -->
                            <tr>
                              <td colspan="3" style="border-bottom:1px dotted #ddd;padding-top:15px"></td>
                            </tr>
                            <tr>
                              <td style="text-align:left;padding-top:10px">Subtotal</td>
                              <td>&nbsp;</td>
                              <td style="text-align:right;padding-top:10px">
                              <?= $data['subtotal'] ?>
                                
                              </td>
                            </tr>
                            <!-- DISKON -->
                            <?php if(isset($data['nama_diskon']) && $data['nama_diskon'] != "" && !is_null($data['nama_diskon'])){ ?>
                            <tr>
                              <td style="text-align:left;padding-top:10px">
                              <?= $data['nama_diskon'] ?>
                                
                                
                              (<?= $data['jumlah_diskon'] ?>)
                                
                              </td>
                              <td>&nbsp;</td>
                              <td style="text-align:right;padding-top:10px">
                              <?= $data['total_diskon'] ?>
                                  
                              </td>
                            </tr>
                            <?php } ?>
                            <!-- BIAYA TAMBAHAN -->
                            <?php if(isset($data['nama_biaya_tambahan']) && $data['nama_biaya_tambahan'] != "" && !is_null($data['nama_biaya_tambahan'])){ ?>
                            <tr>
                              <td style="text-align:left;padding-top:10px">
                              <?= $data['nama_biaya_tambahan'] ?>
                                
                                (<?= $data['jumlah_biaya_tambahan'] ?>)
                              </td>
                              <td>&nbsp;</td>
                              <td style="text-align:right;padding-top:10px">
                                  <?= $data['total_biaya_tambahan'] ?>
                              </td>
                            </tr>
                            <?php } ?>
                            <!-- PAJAK -->
                            <?php if(isset($data['nama_pajak']) && $data['nama_pajak'] != "" && !is_null($data['nama_pajak'])){ ?>
                            <tr>
                              <td style="text-align:left;padding-top:10px">
                                <?= $data['nama_pajak'] ?>
                                
                                (<?= $data['jumlah_pajak'] ?>)
                              </td>
                              <td>&nbsp;</td>
                              <td style="text-align:right;padding-top:10px">
                                  <?= $data['total_pajak'] ?>
                              </td>
                            </tr>
                            <?php } ?>
                            <tr>
                              <td colspan="3" style="border-bottom:1px dotted #ddd;padding-top:15px"></td>
                            </tr>
                            <tr>
                              <td style="text-align:left;padding-top:10px;font-size:18px"><b>Total</b></td>
                              <td style="text-align:right;padding-top:10px;font-size:18px" colspan="2">
                                <b><?= $data['total'] ?></b>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="3" style="border-bottom:1px dotted #ddd;padding-top:10px"></td>
                            </tr>
                            <tr>
                              <td style="text-align:left;padding-top:10px">
                                <div style="float:left;">
                                <?= $data['metode_pembayaran'] ?>
                                </div>
                              </td>
                              <td>&nbsp;</td>
                              <td style="text-align:right;padding-top:10px;">
                                <?= $data['jumlah_pembayaran'] ?>
                              </td>
                            </tr>
                            <?php if($data['metode_pembayaran']  == 'Tunai') { ?>
                            <tr>
                              <td style="text-align:left;padding-top:10px;">Kembalian</td>
                              <td>&nbsp;</td>
                              <td style="text-align:right;padding-top:10px;"><?= $data['kembalian'] ?></td>
                            </tr>
                          <?php } ?>
                            
                            <tr>
                              <td colspan="3" style="padding-top:10px;text-align:center;padding-bottom: 20px;"><?= $data['catatan'] ?></td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>

                   
                  </tbody></table>
                    
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
         
        <tr>
          <td style="font-size:13px;color:#6e6b6b;text-align:center">
            <p style="color:#b7b7b7">
              <br><br>
              © 2019 CV Kapcake. All Right Reserved
            </p>
          </td>
        </tr>
        <tr>
          <td style="text-align:center">
            <a href="https://www.kapcake.com" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.kapcake.com&amp;source=gmail&amp;ust=1569756591611000&amp;usg=AFQjCNHTNl_ajneeiXT-DjDIujNUxMbUgQ"><img src="https://kapcake.com/assets/icons/logo_28x28.png" width="28px" height="auto" style="margin-top:10px;margin-bottom:10px" class="CToWUd"></a>
          </td>
        </tr>
      </tbody></table>
      
    </td>
  </tr>
</tbody></table>