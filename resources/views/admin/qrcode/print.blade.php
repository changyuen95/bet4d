<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coaching Chat Details</title>
</head>

<body>
    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="qr_status">
        QR Code {{ $qrcode->name }}
      </label>
      <p>
        <a class="trigger-modal" url="http://api.qrserver.com/v1/create-qr-code/?data={{ $qrcode->id }}&size=1000x1000" title="QR Code">
          <img src="http://api.qrserver.com/v1/create-qr-code/?data={{ $qrcode->id }}&size=100x100" width="500px" alt="">
        </a>
      </p>
</body>

</html>