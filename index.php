<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo FAVICON_URL ?>">
    <title><?php echo SITE_TITLE ?> - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        #errorMessage {
            display: none;
        }

        .focused-link {
            color: #fd8d0d;
            padding: 5px;
            border-radius: 5px;
        }

        .focused-link:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 115, 0, 0.932);
        }

        .captcha {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        @media only screen and (max-width: 480px) {
            header img {
                width: 18rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <nav class="navbar bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand"><img src="<?php echo LOGO_URL ?>" alt="ipr-logo"></a>
                <div class="d-flex">
                    <a href="register_form.php" class="btn btn-outline-secondary mx-2">Register</a>
                    <a href="index.php" class="btn btn-outline-secondary">Login</a>
                </div>
            </div>
        </nav>
    </header>
    <div class="d-flex justify-content-center align-items-center">
        <div class="container row my-3 shadow p-3 rounded justify-content-center align-items-center">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header border-primary">
                        Important Information
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        GENERAL LINKS
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <span class="d-inline">Download Advertisement ADVT No. <?PHP echo ADVT_NUMBER; ?></span>
                                                <span class="d-inline float-end"><a href="<?php echo ADVT_PDF_LINK; ?>"
                                                        class="focused-link">Click
                                                        Here</a></span>
                                            </li>
                                            <li class="list-group-item">
                                                <span class="d-inline">Image Upload Instructions</span>
                                                <span class="d-inline float-end"><a href="instructions.html"
                                                        class="focused-link">Click
                                                        Here</a></span>
                                            </li>
                                            <li class="list-group-item">
                                                <span class="d-inline">To Register</span>
                                                <span class="d-inline float-end"><a href="register_form.php"
                                                        class="focused-link">Click Here</a></span>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        KEY DATES
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <span class="d-inline">Starting date for application</span>
                                                <span class="d-inline float-end"><?php echo START_DATE; ?></span>
                                            </li>
                                            <li class="list-group-item">
                                                <span class="d-inline">Last date for online submission</span>
                                                <span class="d-inline float-end"><?php echo LAST_DATE; ?></span>
                                            </li>
                                            <li class="list-group-item">
                                                <span class="d-inline">Last Date for online Payment</span>
                                                <span class="d-inline float-end"><?php echo PAYMENT_DATE; ?></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        HELPDESK
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            <li class="list-group-item">Application form related query -
                                                <?php echo COMPANY_EMAIL ?></li>
                                            <li class="list-group-item">For Any query to visit - <?php echo COMPANY_ADDRESS ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-success mt-3">
                    <div class="card-header bg-success border-success text-white">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <form id="loginForm" action="login.php" method="POST">
                            <div class="alert alert-warning" id="errorMessage" role="alert"></div>
                            <div class="form-group">
                                <label for="mobileNumber">Mobile Number</label>
                                <input type="text" class="input-custom" id="mobileNumber" name="mobileNumber"
                                    placeholder="Enter Mobile Number" required>
                            </div>
                            <div class="form-group">
                                <label for="dob">Date of Birth</label>
                                <input type="date" class="input-custom" id="dob" name="dob" required>
                            </div>
                            <div class="form-group">
                                <label for="captcha">Enter CAPTCHA:</label>
                                <input type="text" name="captcha" id="captcha" class="input-custom"
                                    placeholder="Enter Text Shown Below" required>
                                <img src="captcha.php" class="captcha" alt="CAPTCHA Image">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success btn-lg mt-3 px-5"
                                    id="submitBtn">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="message" class="mt-3"></div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="bg-secondary text-white text-center py-3">
        <p>&copy; 2024 <?php echo COMPANY_NAME ?>. All rights reserved.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#captcha').on('input', function () {
                $(this).val($(this).val().toUpperCase());
            });
            // Function to get URL parameter by name
            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            };
            // Check if error parameter exists in URL
            var error = getUrlParameter('error');
            var message = getUrlParameter('message');
            if (error) {
                $('#errorMessage').text(error);
                $('#errorMessage').css('display', 'block');
            }
            if (message) {
                $('#errorMessage').text(message);
                $('#errorMessage').css('display', 'block');
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#submitBtn').click(function (e) {
                e.preventDefault();
                var captchaInput = $('#captcha').val();
                $.ajax({
                    url: 'validate_captcha.php',
                    type: 'POST',
                    data: { captcha: captchaInput },
                    success: function (response) {
                        if (response == 'success') {
                            $('#loginForm').submit();
                        } else {
                            alert('CAPTCHA is wrong. Try again.');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>