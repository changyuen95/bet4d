<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Download APK</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background: linear-gradient(to bottom, #a1c4fd, #c2e9fb);
      color: #333;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      text-align: center;
    }
    .container {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 40px 20px;
      width: 90%;
      max-width: 500px;
    }
    h1 {
      font-size: 2em;
      margin-bottom: 10px;
      color: #2c3e50;
    }
    p {
      font-size: 1em;
      margin-bottom: 20px;
      color: #555;
    }
    .download-button {
      background-color: #28a745;
      color: #fff;
      font-size: 1em;
      padding: 15px 30px;
      text-decoration: none;
      border-radius: 5px;
      display: inline-block;
      transition: background-color 0.3s ease;
    }
    .download-button:hover {
      background-color: #218838;
    }
    .icon {
      margin-right: 8px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Download Our App</h1>
    <p>Click the button below to download the latest version of our APK.</p>
    <a href="your-apk-file-path.apk" class="download-button" download>
      <span class="icon">📥</span> Download APK
    </a>
  </div>
</body>
</html>
