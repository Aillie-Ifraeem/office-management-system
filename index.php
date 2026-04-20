<?php
session_start();
include 'db/connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OMS - Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #007bff, #00c6ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 320px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }

        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        button:disabled {
            background: #999;
            cursor: not-allowed;
        }

        .errorMsg {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }

        .successMsg {
            color: green;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="login-box">
    <h2>Office Management System</h2>

    <form id="loginForm">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" id="loginBtn">Login</button>
    </form>

    <p id="errorMsg" class="errorMsg"></p>
</div>

<script>
document.getElementById("loginForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const email = document.querySelector("input[name='email']").value;
    const password = document.querySelector("input[name='password']").value;
    const errorMsg = document.getElementById("errorMsg");
    const btn = document.getElementById("loginBtn");

    errorMsg.innerText = "";
    btn.disabled = true;
    btn.innerText = "Logging in...";

    try {
        const response = await fetch("login_process.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ email, password })
        });

        const result = await response.json();

        if (result.status === "success") {
            window.location.href = result.redirect;
        } else {
            errorMsg.innerText = result.message;
        }
    } catch (err) {
        errorMsg.innerText = "Something went wrong. Try again.";
    }

    btn.disabled = false;
    btn.innerText = "Login";
});
</script>

</body>
</html>