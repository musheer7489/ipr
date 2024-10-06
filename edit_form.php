<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
$user_id = $_SESSION['user_id'];
// Include database connection
include 'db_connection.php';

$query = "SELECT status FROM payments WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($status);
$stmt->fetch();

if ($status == 'success') {
    header("Location: preview.php");
    exit();
}
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo FAVICON_URL ?>">
    <title>Application | <?php echo SITE_TITLE ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hidden {
            display: none;
        }

        .img-preview {
            max-width: 100px;
            max-height: 100px;
            display: none;
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
                    <a href="logout.php" class="btn btn-danger mx-2">Logout</a>
                </div>
            </div>
        </nav>
    </header>
    <div class="container my-3">
        <h2 class="text-center">Application Form</h2>       
        <div id="message" class="text-danger text-center mb-3"></div>
        <div class="form-group">
            <label for="applicationNumber">Application Number</label>
            <input type="text" id="applicationNumber" class="form-control" readonly>
        </div>
        <form id="multiStepForm" enctype="multipart/form-data">
            <!-- Step 1: Personal Details -->
            <div class="form-step">
                <h4>Personal Details</h4>
                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" required readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" required readonly>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="mobileNumber" class="form-label">Mobile Number</label>
                        <input type="number" class="form-control" id="mobileNumber" required readonly>
                    </div>
                </div>
                <div class="row g-3">
                    <!-- Mother's Name -->
                    <div class="col-md-6 mb-3">
                        <label for="mothersName" class="form-label">Mother's Name</label>
                        <input type="text" class="form-control" id="mothersName" placeholder="Enter your mother's name" required>
                    </div>
                    <!-- Father's Name -->
                    <div class="col-md-6 mb-3">
                        <label for="fathersName" class="form-label">Father's Name</label>
                        <input type="text" class="form-control" id="fathersName" placeholder="Enter your father's name" required>
                    </div>
                </div>
                <div class="row g-3">
                    <!-- Gender -->
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" id="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <!-- Category -->
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-control" id="category" required>
                            <option value="General">General</option>
                            <option value="OBC">OBC</option>
                            <option value="SC">SC</option>
                            <option value="ST">ST</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3">
                    <!-- Marital Status -->
                    <div class="col-md-6 mb-3">
                        <label for="maritalStatus" class="form-label">Marital Status</label>
                        <select class="form-control" id="maritalStatus" required>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                    </div>
                    <!-- PWD Candidate -->
                    <div class="col-md-6 mb-3">
                        <label for="pwdCandidate" class="form-label">Are you a PWD candidate?</label>
                        <select class="form-control" id="pwdCandidate" onchange="togglePwdField()" required>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div id="pwdInput" style="display:none;">
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="percentageOfDisability" class="form-label">Percentage of Disability:</label>
                            <input type="text" class="form-control" id="percentageOfDisability" name="percentageOfDisability" value="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="typesOfDisability" class="form-label">Type of Disabilty:</label>
                            <input type="text" class="form-control" id="typesOfDisability" name="typesOfDisability" value="vision Impairment" required>
                        </div>
                    </div>
                </div>
                <!-- Ex-Serviceman -->
                <div class="form-group">
                    <label for="exServiceman">Are you an ex-serviceman?</label>
                    <select class="form-control" id="exServiceman" onchange="toggleExmField()" required>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div id="exmInput" style="display:none;">
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="yearOfLeaving" class="form-label">Year of Leaving:</label>
                            <input type="text" class="form-control" id="yearOfLeaving" name="yearOfLeaving" value="2000" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="exmIdNumber" class="form-label">ID Number:</label>
                            <input type="text" class="form-control" id="exmIdNumber" name="exmIdNumber" value="00" required>
                        </div>
                    </div>
                </div>
                <!-- Central Government Employee -->
                <div class="form-group">
                    <label for="centralGovtEmployee">Are you an employee of the central government?</label>
                    <select class="form-control" id="centralGovtEmployee" onchange="toggleEmpField()" required>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div id="empInput" style="display:none;">
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="currentWorking" class="form-label">Are you Currently Working:</label>
                            <select name="currentworking" id="currentWorking" class="form-control" required>
                                <option value="Yes">Yes</option>
                                <option value="No" selected>No</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="centralId" class="form-label">ID Number:</label>
                            <input type="text" class="form-control" id="centralId" name="centralId" value="00" required>
                        </div>
                    </div>
                </div>
                <!-- Religious Minority -->
                <div class="form-group">
                    <label for="religiousMinority">Do you belong to a religious minority?</label>
                    <select class="form-control" id="religiousMinority" required>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <button type="button" class="btn btn-primary next-btn">Save & Next</button>
            </div>
            <!-- Step 2: Educational Details -->
            <div class="form-step hidden">
                <h4>Educational Details</h4>
                <!-- Educational Qualification Section -->
                <div class="card mb-3">
                    <div class="card-header">
                        Highschool
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="course">10th or Equivalent</label>
                            <select name="course" id="course" class="form-control" required>
                                <option>Select Course</option>
                                <option value="10th">10th</option>
                                <option value="Equivalent of 10th">Equivalent of 10th</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="school">School/College Name</label>
                            <input type="text" class="form-control" id="school" placeholder="Enter the name of the School/College" required>
                        </div>
                        <div class="form-group">
                            <label for="board">Board/University Name</label>
                            <input type="text" class="form-control" id="board" placeholder="Enter the name of the Board/University" required>
                        </div>
                        <div class="form-group">
                            <label for="passingYear">Passing Year</label>
                            <input type="number" class="form-control" id="passingYear" placeholder="Enter Passing Year" required>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        Highest Qualification
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="highestQualification">Highest Qualification</label>
                            <input type="text" class="form-control" id="highestQualification" placeholder="Enter Name of the Course/Degree" required>
                        </div>
                        <div class="form-group">
                            <label for="university">University/College</label>
                            <input type="text" class="form-control" id="university" placeholder="Enter your college or university name" required>
                        </div>
                        <div class="form-group">
                            <label for="year_of_passing">Year of Passing</label>
                            <input type="number" class="form-control" id="year_of_passing" placeholder="Enter your Passing Year" required>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                <button type="button" class="btn btn-primary next-btn">Save & Next</button>
            </div>

            <!-- Step 3: Applying Post -->
            <div class="form-step hidden">
                <h4>Applying Post</h4>
                <div class="form-group">
                    <label for="post">Post Applied For</label>
                    <select class="form-control" id="post" onchange="updateEligibility()" required>
                        <option value="">Select Post</option>
                        <option value="Multi-Tasking Staff (MTS)">Multi-Tasking Staff (MTS)</option>
                        <option value="Stenographer Grade 2">Stenographer Grade 2</option>
                        <option value="Senior Technical Assiatant Grade 1">Senior Technical Assiatant Grade 1</option>
                    </select>
                </div>

                <!-- Eligibility -->
                <div class="form-group">
                    <label for="eligibility">Eligibility</label>
                    <textarea class="form-control" id="eligibility" rows="2" readonly></textarea>
                </div>

                <!-- Examination Center -->
                <div class="form-group">
                    <label for="examCenter">Examination Center</label>
                    <select class="form-control" id="examCenter">
                        <option value="">Select Examination Center</option>
                        <option value="Ahmedabad">Ahmedabad</option>
                        <option value="Allahabad">Allahabad</option>
                        <option value="Agra">Agra</option>
                        <option value="Amritsar">Amritsar</option>
                        <option value="Aurangabad">Aurangabad</option>
                        <option value="Bangalore">Bangalore</option>
                        <option value="Bhopal">Bhopal</option>
                        <option value="Chennai">Chennai</option>
                        <option value="Chandigarh">Chandigarh</option>
                        <option value="Coimbatore">Coimbatore</option>
                        <option value="Delhi">Delhi</option>
                        <option value="Dhanbad">Dhanbad</option>
                        <option value="Faridabad">Faridabad</option>
                        <option value="Ghaziabad">Ghaziabad</option>
                        <option value="Gwalior">Gwalior</option>
                        <option value="Guwahati">Guwahati</option>
                        <option value="Hyderabad">Hyderabad</option>
                        <option value="Howrah">Howrah</option>
                        <option value="Indore">Indore</option>
                        <option value="Jabalpur">Jabalpur</option>
                        <option value="Jaipur">Jaipur</option>
                        <option value="Jodhpur">Jodhpur</option>
                        <option value="Kanpur">Kanpur</option>
                        <option value="Kolkata">Kolkata</option>
                        <option value="Kota">Kota</option>
                        <option value="Kalyan-Dombivli">Kalyan-Dombivli</option>
                        <option value="Ludhiana">Ludhiana</option>
                        <option value="Lucknow">Lucknow</option>
                        <option value="Madurai">Madurai</option>
                        <option value="Meerut">Meerut</option>
                        <option value="Mumbai">Mumbai</option>
                        <option value="Nagpur">Nagpur</option>
                        <option value="Nashik">Nashik</option>
                        <option value="Navi Mumbai">Navi Mumbai</option>
                        <option value="Patna">Patna</option>
                        <option value="Pune">Pune</option>
                        <option value="Pimpri-Chinchwad">Pimpri-Chinchwad</option>
                        <option value="Raipur">Raipur</option>
                        <option value="Rajkot">Rajkot</option>
                        <option value="Ranchi">Ranchi</option>
                        <option value="Surat">Surat</option>
                        <option value="Srinagar">Srinagar</option>
                        <option value="Solapur">Solapur</option>
                        <option value="Thane">Thane</option>
                        <option value="Visakhapatnam">Visakhapatnam</option>
                        <option value="Vadodara">Vadodara</option>
                        <option value="Vasai-Virar">Vasai-Virar</option>
                        <option value="Varanasi">Varanasi</option>
                        <option value="Vijayawada">Vijayawada</option>
                    </select>
                </div>
                <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                <button type="button" class="btn btn-primary next-btn">Save & Next</button>
            </div>

            <!-- Step 4: Address -->
            <div class="form-step hidden">
                <h4>Address</h4>
                <!-- Address Section -->
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" placeholder="Enter your address" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" placeholder="Enter your city" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="state">State</label>
                        <select class="form-control" id="state" name="state" required>
                            <option value="">Select a State</option>
                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                            <option value="Assam">Assam</option>
                            <option value="Bihar">Bihar</option>
                            <option value="Chhattisgarh">Chhattisgarh</option>
                            <option value="Goa">Goa</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Haryana">Haryana</option>
                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                            <option value="Jharkhand">Jharkhand</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Kerala">Kerala</option>
                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Manipur">Manipur</option>
                            <option value="Meghalaya">Meghalaya</option>
                            <option value="Mizoram">Mizoram</option>
                            <option value="Nagaland">Nagaland</option>
                            <option value="Odisha">Odisha</option>
                            <option value="Punjab">Punjab</option>
                            <option value="Rajasthan">Rajasthan</option>
                            <option value="Sikkim">Sikkim</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Telangana">Telangana</option>
                            <option value="Tripura">Tripura</option>
                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                            <option value="Uttarakhand">Uttarakhand</option>
                            <option value="West Bengal">West Bengal</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="pinCode">Pin Code</label>
                        <input type="text" class="form-control" id="pinCode" placeholder="Enter your pin code" required>
                    </div>
                </div>
                <!-- Nationality -->
                <div class="form-group">
                    <label for="country">Nationality</label>
                    <select name="country" id="country" class="form-control" required>
                        <option value="Indian">Indian</option>
                        <option value="Other">Others</option>
                    </select>
                </div>
                <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                <button type="button" class="btn btn-primary next-btn">Save & Next</button>
            </div>

            <!-- Step 5: Photo and Signature -->
            <div class="form-step hidden">
                <h4>Photo and Signature</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="photo">Photo (Max 50 KB)</label>
                            <input type="text" class="form-control" name="photo" id="photo" readonly style="visibility: hidden;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img id="photoPreview" class="img-preview mt-2">
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="signature">Signature (Max 20 KB)</label>
                            <input type="text" class="form-control" name="signature" id="signature" readonly style="visibility: hidden;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img id="signaturePreview" class="img-preview mt-2">
                    </div>
                </div>
                <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                <button type="submit" class="btn btn-success next-btn">Final Submit</button>
            </div>
        </form>
    </div>
     <!-- Footer -->
     <footer class="bg-secondary text-white text-center py-3">
        <p>&copy; 2024 <?php echo COMPANY_NAME ?>. All rights reserved.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
        $('input').not('[type="email"]').on('input',function(){
            $(this).val($(this).val().toUpperCase());
        });
            var currentStep = 0;
            var steps = $('.form-step');
            var nextBtn = $('.next-btn');
            var prevBtn = $('.prev-btn');
            var message = $('#message');

            function showStep(index) {
                steps.addClass('hidden');
                $(steps[index]).removeClass('hidden');
            }

            function validateStep(step) {
                var isValid = true;
                $(steps[step]).find('input[required], textarea[required], select[required]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                        showStep(currentStep);
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                return isValid;
            }

            function validateFileSize(input, maxSizeKB) {
                if (input.files && input.files[0] && input.files[0].size > maxSizeKB * 1024) {
                    return false;
                }
                return true;
            }

            nextBtn.click(function() {
                if (validateStep(currentStep)) {
                    var step = currentStep + 1;
                    var formData = new FormData();
                    formData.append('step', step);
                } else {
                    message.text('Please fill out all required fields.');
                }
                switch (step) {
                    case 1:
                        formData.append('fullName', $('#fullName').val());
                        formData.append('dob', $('#dob').val());
                        formData.append('email', $('#email').val());
                        formData.append('mobileNumber', $('#mobileNumber').val());
                        formData.append('mothersName', $('#mothersName').val());
                        formData.append('fathersName', $('#fathersName').val());
                        formData.append('gender', $('#gender').val());
                        formData.append('category', $('#category').val());
                        formData.append('pwdCandidate', $('#pwdCandidate').val());
                        formData.append('exServiceman', $('#exServiceman').val());
                        formData.append('religiousMinority', $('#religiousMinority').val());
                        formData.append('centralGovtEmployee', $('#centralGovtEmployee').val());
                        formData.append('maritalStatus', $('#maritalStatus').val());
                        break;
                    case 2:
                        formData.append('course', $('#course').val());
                        formData.append('school', $('#school').val());
                        formData.append('board', $('#board').val());
                        formData.append('passingYear', $('#passingYear').val());
                        formData.append('highestQualification', $('#highestQualification').val());
                        formData.append('university', $('#university').val());
                        formData.append('year_of_passing', $('#year_of_passing').val());
                        break;
                    case 3:
                        formData.append('post', $('#post').val());
                        formData.append('eligibility', $('#eligibility').val());
                        formData.append('examCenter', $('#examCenter').val());
                        break;
                    case 4:
                        formData.append('address', $('#address').val());
                        formData.append('city', $('#city').val());
                        formData.append('state', $('#state').val());
                        formData.append('pinCode', $('#pinCode').val());
                        formData.append('country', $('#country').val());
                        break;
                    case 5:
                        formData.append('photo', $('#photo').val());
                        formData.append('signature', $('#signature').val());
                        break;
                }

                if (confirm('Do you want to save this step?')) {
                    $.ajax({
                        url: 'preview_edit.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var res = JSON.parse(response);
                            if (res.status === 'success') {
                                if (currentStep < steps.length - 1) {
                                    currentStep++;
                                    showStep(currentStep);
                                }
                            } else {
                                alert(res.message);
                            }
                        }
                    });
                }
            });

            prevBtn.click(function() {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
            $('#multiStepForm').on('submit', function(e) {
                if (!validateStep(currentStep)) {
                    e.preventDefault();
                    message.text('Please fill out all required fields.');
                    return;
                }
                if (!validateFileSize($('#photo')[0], 50)) {
                    e.preventDefault();
                    message.text('Photo size should not exceed 50 KB.');
                    return;
                }
                if (!validateFileSize($('#signature')[0], 20)) {
                    e.preventDefault();
                    message.text('Signature size should not exceed 20 KB.');
                    return;
                }
                e.preventDefault(); // Prevent form submission
                // Redirect to preview.php
                window.location.href = 'preview.php';
            });

            function previewImage(input, previewElement) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(previewElement).attr('src', e.target.result).show();
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    $(previewElement).hide();
                }
            }

            $('#photo').change(function() {
                if (validateFileSize(this, 50)) {
                    previewImage(this, '#photoPreview');
                } else {
                    message.text('Photo size should not exceed 50 KB.');
                    this.value = "";
                    $('#photoPreview').hide();
                }
            });

            $('#signature').change(function() {
                if (validateFileSize(this, 20)) {
                    previewImage(this, '#signaturePreview');
                } else {
                    message.text('Signature size should not exceed 20 KB.');
                    this.value = "";
                    $('#signaturePreview').hide();
                }
            });
            showStep(currentStep);
        });
    </script>
    <script>
        $(document).ready(function() {
            function setInitialPreview(previewElement, filePath) {
                if (filePath) {
                    $(previewElement).attr('src', filePath).show();
                } else {
                    $(previewElement).hide();
                }
            }
            $.ajax({
                url: 'fetch_user_data.php',
                type: 'POST',
                data: {
                    user_id: '<?php echo $user_id; ?>'
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.status === 'success') {
                        $('#applicationNumber').val(res.data.id);
                        $('#fullName').val(res.data.full_name);
                        $('#dob').val(res.data.dob);
                        $('#mobileNumber').val(res.data.mobile_number);
                        $('#email').val(res.data.email);
                        $('#mothersName').val(res.data.mothers_name);
                        $('#fathersName').val(res.data.fathers_name);
                        $('#gender').val(res.data.gender);
                        $('#category').val(res.data.category);
                        $('#pwdCandidate').val(res.data.pwd);
                        $('#exServiceman').val(res.data.is_ex_serviceman);
                        $('#religiousMinority').val(res.data.is_religious_minority);
                        $('#centralGovtEmployee').val(res.data.is_central_govt_employee);
                        $('#maritalStatus').val(res.data.marital_status);
                        $('#course').val(res.data.degree);
                        $('#school').val(res.data.institution_name);
                        $('#board').val(res.data.board);
                        $('#passingYear').val(res.data.year_of_graduation);
                        $('#highestQualification').val(res.data.highest_qualification);
                        $('#university').val(res.data.university);
                        $('#year_of_passing').val(res.data.year_of_passing);
                        $('#post').val(res.data.post);
                        $('#eligibility').val(res.data.eligibility);
                        $('#examCenter').val(res.data.exam_center);
                        $('#address').val(res.data.address);
                        $('#city').val(res.data.city);
                        $('#state').val(res.data.state);
                        $('#pinCode').val(res.data.pin_code);
                        $('#country').val(res.data.country);
                        $('#photo').val(res.data.photo_path);
                        $('#signature').val(res.data.signature_path);
                        // Set initial preview images
                        setInitialPreview('#photoPreview', res.data.photo_path);
                        setInitialPreview('#signaturePreview', res.data.signature_path);
                        /* $("#photoPreview").click(function() {
                            $("#photo").attr("value", res.data.photo_path.split('/').pop());
                        });
                        $("#signaturePreview").click(function() {
                            $("#signature").attr("value", res.data.signature_path.split('/').pop());
                        }); */

                    } else {
                        alert(res.message);
                    }
                }
            });

        });
    </script>
    <script>
        function updateEligibility() {
            const post = document.getElementById('post').value;
            let eligibility = '';
            switch (post) {
                case 'Multi-Tasking Staff (MTS)':
                    eligibility = '(10+2) or its equivalent with PCM. Requires working knowledge in computer (MS word, MS Excel, etc.) and ability to do routine correspondence in Hindi/English.';
                    break;
                case 'Stenographer Grade 2':
                    eligibility = 'Graduate Form Any Recognized University with 35WPS typing Speed in English Language';
                    break;
                case 'Senior Technical Assiatant Grade 1':
                    eligibility = 'BE/BTech/Diploma from Any Recognized University with Electrical/Electronics/Mechanical/Computer Science';
                    break;
                default:
                    eligibility = '';
            }
            document.getElementById('eligibility').value = eligibility;
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#userForm').on('submit', function(e) {
                e.preventDefault();

                var formData = {
                    name: $('#fullName').val(),
                    post: $('#post').val(),
                    mobile: $('#mobileNumber').val(),
                    email: $('#email').val()
                };

                $.ajax({
                    type: 'POST',
                    url: 'send_email.php',
                    data: formData,
                    success: function(response) {
                        alert(response);
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            });
        });
    </script>
    <script>
        function togglePwdField() {
            var selectElement = document.getElementById("pwdCandidate");
            var inputField = document.getElementById("pwdInput");

            if (selectElement.value === "Yes") {
                inputField.style.display = "block";
            } else {
                inputField.style.display = "none";
            }
        }

        function toggleExmField() {
            var selectElement = document.getElementById("exServiceman");
            var inputField = document.getElementById("exmInput");

            if (selectElement.value === "Yes") {
                inputField.style.display = "block";
            } else {
                inputField.style.display = "none";
            }
        }

        function toggleEmpField() {
            var selectElement = document.getElementById("centralGovtEmployee");
            var inputField = document.getElementById("empInput");

            if (selectElement.value === "Yes") {
                inputField.style.display = "block";
            } else {
                inputField.style.display = "none";
            }
        }
    </script>
</body>

</html>