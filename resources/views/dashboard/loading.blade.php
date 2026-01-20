<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading...</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .loading-container {
            text-align: center;
        }
        .spinner {
            border: 12px solid #f3f3f3; /* Light grey */
            border-top: 12px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 80px;
            height: 80px;
            animation: spin 2s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loading-text {
            margin-top: 20px;
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>

<div class="loading-container">
    <div class="spinner"></div>
    <p class="loading-text">Please wait, we're redirecting you...</p>
</div>

<script>
    setTimeout(function(){
        window.location.href = '/user/dashboard'; // Change '/dashboard' to your actual dashboard route
    }, 3000); // 3000ms = 3 seconds
</script>

</body>
</html>
