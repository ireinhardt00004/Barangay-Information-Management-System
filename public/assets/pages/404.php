<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        :root {
    --carousel-btn-color1: rgb(255, 225, 116);
}

body {
    background-color: rgb(10, 59, 86, 0.95);
    font-family: Arial, sans-serif;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    text-align: center;
}

.error-container {
    background: linear-gradient(to top, rgba(255, 255, 255, 0.1) 50%, rgb(248, 220, 120) 50%);
    border-radius: 2px;
    box-shadow: 0px 0px 3px 1px rgba(0, 0, 0, 0.3);
    padding: 20px;
}

.error-heading {
    color: white;
    font-weight: bold;
    font-size: 2em;
    margin: 10px 0;
}

.error-message {
    font-size: 1.2em;
    margin: 10px 0;
}

.error-link {
    color: var(--carousel-btn-color1);
    font-weight: bold;
    text-decoration: none;
    border-bottom: 2px solid var(--carousel-btn-color1);
    display: inline-block;
    margin: 20px 0;
}

.error-link:hover {
    color: rgb(0, 46, 120);
}

    </style>
</head>
<body>
    <div class="container">
        <div class="error-container">
            <img src="<?=$web_logo;?>" alt="Barangay Logo" height="60">
            <h1 class="error-heading">404 - Page Not Found</h1>
            <p class="error-message">The page you are looking for does not exist.</p>
            <a href="/" class="error-link">Go back to the homepage</a>
        </div>
    </div>
</body>
</html>
