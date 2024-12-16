<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<style>
   

    .text-plus-circle-fill{
        color: #198754;
    }

    .text-check-circle-fill{
        color: #DC3545;
    }

</style>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Reviews</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Reviews</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->



    <!-- Recent Sales -->
    <div class="col-lg-10">
        <div class="card recent-sales">
            <div class="filter p-2">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="
                        dropdown-menu dropdown-menu-end dropdown-menu-arrow
                      ">
                    <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                </ul>
            </div>

            <div class="card-body">
                <h5 class="card-title">
                    Recent Reviews <span>| Today</span>
                </h5>
                <div class="table-responsive">

                    <table class="table table-bordered table-sm datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Contact Number</th>
                                <th scope="col">Review</th>
                                <th scope="col">Time</th>
                                <th scope="col" class="text-center" colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $selection = mysqli_query($con, "SELECT * FROM `review` ORDER BY `id` DESC");

                            $c = 1;
                            while ($data = mysqli_fetch_array($selection)) {
                            ?>
                                <tr>
                                    <th scope="row"><a href="#"><?= $c; ?></a></th>
                                    <td><?= $data['name'] ?></td>
                                    <td>
                                        <a href="tel:<?= $data['number'] ?>" class="text-primary"><?= $data['number'] ?></a>
                                    </td>

                                    <td><?= $data['review'] ?></td>

                                    <td><?= $data['time'] ?></td>
             
                                    <td><a href="add_to_website.php?id=<?= $data['id'] ?>" title="Add On Website" class="text-center text-<?= $data['status'] ?> fs-4"><i class="bi bi-<?= $data['status'] ?>"></i></a></td>
                                    <td><a href="delete_review.php?id=<?= $data['id'] ?>" title="Delete Enquiry" class="text-center text-danger fs-4"><i class="bi bi-trash"></i></a></td>
                                    
                                </tr>
                            <?php
                                $c++;
                            }

                            ?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Recent Sales -->
    <?php include('footer.php') ?>