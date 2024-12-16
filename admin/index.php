<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<main id="main" class="main"> 

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->


    <section class="section dashboard">
    <div class="row">

        <!-- Single Row for Cards -->
        <div class="col-lg-12">
            <div class="row">

                <!-- Complaints Card -->
                <div class="col-lg-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li><a class="dropdown-item" href="complaints.php">View All</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Total Complaints</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-laptop"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>
                                        <?php
                                        include('config.php');
                                        $queryy = "SELECT `id` FROM `complaints` ORDER BY id";
                                        $runn = mysqli_query($con, $queryy);
                                        $count = mysqli_num_rows($runn);
                                        echo '<h6>' . $count . '</h6>';
                                        ?>
                                    </h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Complaints Card -->

                <!-- Enquiries Card -->
                <div class="col-lg-4 col-md-6">
                    <div class="card info-card revenue-card">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li><a class="dropdown-item" href="enquiries.php">View All</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Total Enquiries</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>
                                        <?php
                                        include('config.php');
                                        $queryy = "SELECT `id` FROM `enquiries` ORDER BY id";
                                        $runn = mysqli_query($con, $queryy);
                                        $count = mysqli_num_rows($runn);
                                        echo '<h6>' . $count . '</h6>';
                                        ?>
                                    </h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Enquiries Card -->

                <!-- Reviews Card -->
                <div class="col-lg-4 col-md-6">
                    <div class="card info-card customers-card">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li><a class="dropdown-item" href="reviews.php">View All</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Reviews</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-layout-text-window-reverse"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>
                                        <?php
                                        include('config.php');
                                        $queryy = "SELECT `id` FROM `review` ORDER BY id";
                                        $runn = mysqli_query($con, $queryy);
                                        $count = mysqli_num_rows($runn);
                                        echo '<h6>' . $count . '</h6>';
                                        ?>
                                    </h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Reviews Card -->


                <!-- Job application Card -->
                <div class="col-lg-4 col-md-6">
                    <div class="card info-card revenue-card">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li><a class="dropdown-item" href="application.php">View All</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Job Application</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-video2"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>
                                        <?php
                                        include('config.php');
                                        $queryy = "SELECT `id` FROM `job_applications` ORDER BY id";
                                        $runn = mysqli_query($con, $queryy);
                                        $count = mysqli_num_rows($runn);
                                        echo '<h6>' . $count . '</h6>';
                                        ?>
                                    </h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- Job application  Card -->


            </div>
        </div>
    </div>
</section>
</br>
</br></br></br>
</br></br>
    <?php include('footer.php') ?>