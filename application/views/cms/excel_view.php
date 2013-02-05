<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=datauser.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border='1' widtd="70%">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Twitter</th>
        <th>Telp</th>
        <th>Email</th>
        <th>Alamat</th>
        <th>Date Register</th>
    </tr>
    <?php
    $i = 1;
    foreach($datausers as $item) {
        ?>
        <tr>
            <td><?= $i?></td>
            <td><?= $item['name']?></td>
            <td><?= $item['twitter_account']?></td>
            <td><?= $item['phone']?></td>
            <td><?= $item['email']?></td>
            <td><?= $item['address']?></td>
            <td><?= $item['date_registered']?></td>
        </tr>
        <?php $i++; } ?>
</table>