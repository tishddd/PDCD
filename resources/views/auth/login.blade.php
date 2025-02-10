<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Add CSS File -->
    <link href="../loginstyle.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <form action="" method="post">
            <h3 class="title">Login</h3>
            <div class="field">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Enter your username">
            </div>
            <div class="field">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter your password">
            </div>
            <div class="field-checkbox">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember Me</label>
            </div>


            <!-- Submit Button -->
            <!-- Submit Button -->
<!-- Submit Button -->
<a href="{{ route('admin.dashboard') }}">
    <button type="button">LOGIN</button>
</a>

        </form>
        <p>Doesn't have an account? <a href="/">Create One</a></p>
    </div>
    <footer>Created by <a href="https://github.com/Mahib222">Mahib Abrar</a></footer>
</body>
</html>