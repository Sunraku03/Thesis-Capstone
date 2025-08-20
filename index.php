<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HKBC Login</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: #100058ff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .logo-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 30px;
      animation: fadeInDown 1s ease;
    }

    .logo {
      width: 180px;
      height: auto;
      margin-bottom: 2px;
    }
    .logo-background {
      width: 150px;
      height: 150px;
      background: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 10px auto;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    h1 {
      font-size: 55px;
      color: #ffffffff;
      margin-bottom: 5px;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    }

    p {
      font-size: 20px;
      color: #ffffffff;
      margin-bottom: 10px;
    }

    .login-form {
      background-color: #ffffff;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 500px;
      animation: fadeInUp 1s ease;
    }

    .login-form h2 {
      margin-bottom: 20px;
      color: #1e293b;
      text-align: center;
    }

    .login-form input {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 10px;
      border: 1px solid black;
      border-radius: 6px;
      font-size: 16px;
    }

    .login-form button {
      width: 100%;
      padding: 12px;
      background-color: #1e40af;
      color: #ffffff;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-form button:hover {
      background-color: #1e3a8a;
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>
  <div class="logo-container">
    <div class="logo-background">
      <img src="logo.png" alt="HKBC Logo" class="logo" />
    </div>
    <h1>HKBC INSURANCE SERVICES</h1>
    <p>Admin System</p>
  </div>
  <form class="login-form" method="post" action="login_process.php">
    <h2>SYSTEM LOGIN</h2>
    <input type="text" name="username" placeholder="Username" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Login</button>
  </form>
</body>
</html>
