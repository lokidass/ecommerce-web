<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<style>
    .badge-Accepted{
        background-color: #FFC107;
    }


    .badge-Received{
        background-color: #4154f1;
    }

    .badge-Closed{
        background-color: #198754;
    }

    .badge-Rejected{
        background-color: #DC3545;
    }

  

</style>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Complaints</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Complaints</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->



    <!-- Recent Sales -->
    <div class="col-lg-12">
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
                    Recent Complaints <span>| Today</span>
                </h5>
                <div class="table-responsive">

                    <table class="table table-bordered table-sm datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Contact Number</th>
                                <th scope="col">Email</th>
                                <th scope="col">Address</th>
                                <th scope="col">Problem</th>
                                <th scope="col">Status</th>
                                <th scope="col">Time</th>
                                <th scope="col" class="text-center" colspan="4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $selection = mysqli_query($con, "SELECT * FROM `complaints` ORDER BY `id` DESC");

                            $c = 1;
                            while ($data = mysqli_fetch_array($selection)) {
                            ?>
                                <tr>
                                    <th scope="row"><a href="#"><?= $c; ?></a></th>
                                    <td><?= $data['name'] ?></td>
                                    <td>
                                        <a href="tel:<?= $data['number'] ?>" class="text-primary"><?= $data['number'] ?></a>
                                    </td>

                                    <td>
                                        <a href="mailto:<?= $data['email'] ?>" class="text-primary"><?= $data['email'] ?></a>
                                    </td>

                                    <td><?= $data['address'] ?></td>
                                    <td><?= $data['problem'] ?></td>
                                    <td>
                                        <span class="badge badge-<?= $data['status'] ?>"><?= $data['status'] ?></span>
                                    </td>
                                    <td><?= $data['time'] ?></td>


                                    <td><a href="accept_complaint.php?id=<?= $data['id'] ?>" title="Accept the Complaint...!" class=" text-warning fs-4"><i class="bi bi-person-check-fill"></i></a></td>

                                    <td><a href="reject_complaint.php?id=<?= $data['id'] ?>" title="Reject the Complaint...!" class=" text-danger fs-4"><i class="bi bi-x-circle-fill"></i></a></td>

                                    <td><a href="done_complaint.php?id=<?= $data['id'] ?>" title="Closed the Complaint...!" class=" text-success fs-4"><i class="bi bi-check-circle-fill"></i></a></td>

                                    
                                    <td><a href="delete_complaint.php?id=<?= $data['id'] ?>" title="Delete Complaint" class="text-danger fs-4"><i class="bi bi-trash"></i></a></td>
                                    
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