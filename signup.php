<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SIGN UP</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-6 mx-auto shadow p-5">
                    <form action="process_signup.php" method="POST">
                        <h6 class="display-4 text-center text-muted">Sign Up</h6>
                        <?php
                            session_start();

                            if (isset($_SESSION['message'])) {
                              echo "<div class='alert alert-danger text-center'>".$_SESSION['message'] ."</div>";
                            }

                            session_unset();
                
                        ?>
                        <input type="text" name="username" placeholder="username" class="form-control mb-3">
                        <input type="text" name="email" placeholder="Email" class="form-control mb-3">
                        <input type="text" name="password" placeholder="Enter password" class="form-control mb-3">
                        <input class="btn btn-success w-100" name="submit" type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>




