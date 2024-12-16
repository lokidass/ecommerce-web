<?php include('header.php') ?>
<?php include('sidebar.php') ?>
<?php
if (isset($_GET['id'])) {
    // Include your database connection file
    include('db_connection.php'); // Replace with your database connection file

    // Get the ID from the URL and sanitize it
    $id = intval($_GET['id']);

    // Prepare the SQL statement
    $stmt = $con->prepare("DELETE FROM `contact_form` WHERE `id` = ?");
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Enquiry deleted successfully!'); window.location.href='contact.php';</script>";
    } else {
        echo "<script>alert('Error deleting enquiry. Please try again.'); window.location.href='contact.php';</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
}
?>


<style>
    .badge-Accepted {
        background-color: #FFC107;
    }

    .badge-Received {
        background-color: #4154f1;
    }

    .badge-Closed {
        background-color: #198754;
    }

    .badge-Rejected {
        background-color: #DC3545;
    }

    /* Increase table width */
    .table-responsive {
        width: 100%; /* Ensures the table takes full width */
        overflow-x: auto; /* Allows horizontal scroll if necessary */
    }

    /* Optional: Increase the width of each column for better spacing */
    .datatable th,
    .datatable td {
        white-space: nowrap; /* Prevents text from wrapping */
    }
</style>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Contact</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Contact</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <!-- Recent Sales -->
    <div class="col-lg-12"> <!-- Changed to col-lg-12 to use full width -->
        <div class="card recent-sales">
            <div class="filter p-2">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
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
                    Recent Enquiries <span>| Today</span>
                </h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Contact Number</th>
                                <th scope="col">Email</th>
                                <th scope="col">Message</th>
                                <th scope="col">Date</th>
                                <th scope="col" class="text-center" colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetching enquiries from the contact_form table
                            $selection = mysqli_query($con, "SELECT * FROM `contact_form` ORDER BY `submitted_at` DESC");

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
                                    <td><?= $data['message'] ?></td>
                                    <td><?= $data['submitted_at'] ?></td>
                                    <td><a href="contact.php?id=<?= $data['id'] ?>" title="Delete Enquiry" class="text-danger fs-4"><i class="bi bi-trash"></i></a></td>

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
