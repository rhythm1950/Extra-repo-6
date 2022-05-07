<!-- INSERT INTO `notes` (`sno`, `title`, `description`, `tstamp`) VALUES (NULL, 'go to school ', 'ok go to school ', current_timestamp()); -->
<?php
$insert = false;
// Connecting to database
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";

// Creating a connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Die if connection is not successful
if (!$conn) {
    die("Sorry, we failed to connect " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['snoEdit'])) {
        // update the record
        $sno = $_POST['snoEdit'];
        $title = $_POST['titleEdit'];
        $description = $_POST['descriptionEdit'];

        // sql query to be executed
        $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$description' WHERE `notes`.`sno` = $sno;";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "Updated the record successfully!";
        } else {
            echo "Could not update the record";
        }
    } else {
        $title = $_POST['title'];
        $description = $_POST['description'];

        // sql query to be executed
        $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
        $result = mysqli_query($conn, $sql);

        // add a new trip to the trip table in the database
        if ($result) {
            // echo "Added trip";
            $insert = true;
        } else {
            echo "The record was not inserted successfully because of this error --> " . mysqli_error($conn);
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <title>PHP CRUD Project</title>
</head>

<body>
    <!-- Edit modal -->
    <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
        Edit modal
    </button> -->

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit This Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/PHP-CRUD-Project/index.php" method="post">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="mb-3">
                            <label for="title" class="form-label">Note Title</label>
                            <input type="text" name="title" class="form-control" id="titleEdit" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="desc" class="form-label">Note Description</label>
                            <textarea class="form-control" name="description" id="descriptionEdit" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Note</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PHP CRUD</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <?php
    if ($insert) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> Your note has been added successfully!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    }
    ?>

    <!-- Form -->
    <div class="container my-4">
        <h2>Add a Note</h2>
        <form action="/PHP-CRUD-Project/index.php" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Note Title</label>
                <input type="text" name="title" class="form-control" id="title" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="desc" class="form-label">Note Description</label>
                <textarea class="form-control" name="description" id="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>

    <div class="container my-5">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">Sl No.</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $sql = "SELECT * FROM `notes`";
                $result = mysqli_query($conn, $sql);

                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno += 1;
                    echo "<tr>
                    <th scope='row'>" . $sno . "</th>
                    <td>" . $row['title'] . "</td>
                    <td>" . $row['description'] . "</td>
                    <td> 
                    <button href='/edit' id=" . $row['sno'] . " class='edit btn btn-sm btn-primary'>Edit</button> <button href='/del' class='delete btn btn-sm btn-danger'>Delete</button> 
                    </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
    <hr>

    <!-- Bootstrap js cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="//cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        edits = document.getElementsByClassName("edit");
        Array.from(edits).forEach((element) => {
            element.addEventListener('click', (e) => {
                console.log("edit", e.target.parentNode.parentNode);
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innertext;
                desc = tr.getElementsByTagName("td")[1].innertext;
                console.log(title, desc);
                titleEdit.value = title;
                descriptionEdit.value = desc;
                snoEdit.value = e.target.id;
                console.log(e.target.id);
                $('#editModal').modal('toggle');
            })
        })
    </script>
</body>

</html>