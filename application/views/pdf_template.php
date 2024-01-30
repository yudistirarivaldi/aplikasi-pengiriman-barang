<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $headerTitle ?></title>
</head>
<body>
    <h2><?= $headerTitle ?></h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID Pengiriman</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>No. PO</th>
                <th>Kurir</th>
                <th>No. Kendaraan</th>
                <th>Penerima</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $row['ID Pengiriman'] ?></td>
                    <td><?= $row['Tanggal'] ?></td>
                    <td><?= $row['Pelanggan'] ?></td>
                    <td><?= $row['No. PO'] ?></td>
                    <td><?= $row['Kurir'] ?></td>
                    <td><?= $row['No. Kendaraan'] ?></td>
                    <td><?= $row['Penerima'] ?></td>
                    <td><?= $row['Status'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
