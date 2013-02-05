<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=datastory.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border='1' widtd="70%">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Endorsed Person</th>
        <th>Suggest Place</th>
        <th>Reason</th>
        <th>Total Like</th>
        <th>Date Insert</th>
    </tr>
    <?php
    $i = 1;
    foreach($datastorys as $datastory) {
        ?>
        <tr>
            <td><?= $i?></td>
            <td><?= $datastory['name']?></td>
            <td><?= $datastory['endorsed_person']?></td>
            <td><?= $datastory['suggest_places']?></td>
            <td><?= $datastory['reason']?></td>
            <td><?= $datastory['total_like']?></td>
            <td><?= $datastory['date_insert']?></td>
        </tr>
        <?php $i++; } ?>
</table>