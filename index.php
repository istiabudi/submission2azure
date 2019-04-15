<html>
 <head>
 <Title>FORM PENDAFTARAN</Title>
 <!-- UIkit CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/css/uikit.min.css" />

<!-- UIkit JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/js/uikit-icons.min.js"></script>
 <style type="text/css">
 	body { background-color: #fff; 
 	    color: #333; font-size: .85em; margin: 0 20 20 20; padding: 10 20 20 20;
 	    font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
 	}
 	h1, h2, h3,{ color: #000; margin-bottom: 0; padding-bottom: 0; }
 	h1 { font-size: 2.2em; font-weight: 500;}
 	h2 { font-size: 1.75em; }
 	h3 { font-size: 1.2em; }
 	table { margin-top: 0.75em; }
 	th { font-size: 1.2em; text-align: left; border: none; padding-left: 0; }
 	td { padding: 0.25em 2em 0.25em 0em; border: 0 none; }
     .uk-container-small {background-color: #ffff; border: 1px solid #78BCC4;  border-radius: 5px; margin: 0 auto; box-shadow :7px 5px 1px #78BCC4; position: relative; margin-top: 22px; margin-bottom: 50px;}
     .row { margin: 20px;}
     .red {position: absolute; background-color: #F7444E; width: 50%; height: 35em; border-radius: 7px;}
 </style>
 </head>
 <body>
 <div class="red"></div>
<div class="uk-container-small ">
    <div class="row ">
        <h1>UNIVERSITAS MULIA ABSENSI!</h1>
        <p>Isi Data yang Nama, NIM and NIK, then click <strong>Submit</strong> to register.</p>
        <form method="post" action="index.php" enctype="multipart/form-data" class="uk-form-stacked">
            <div class="uk-margin uk-width-1-2@s">
                <label class="uk-form-label" for="form-stacked-text">Name</label>
                <div class="uk-form-control">
                    <input class="uk-input" id="form-stacked-text" type="text" name="name" id="name"/>
                </div>
            </div>

            <div class="uk-margin uk-width-1-2@s">
                <label class="uk-form-label" for="form-stacked-text">Email</label>
                <div class="uk-form-control">
                    <input class="uk-input" id="form-stacked-text" type="text" name="nim" id="nim"/>
                </div>
            </div>

            <div class="uk-margin uk-width-1-2@s">
                <label class="uk-form-label" for="form-stacked-text">Job</label>
                <div class="uk-form-control">
                    <input class="uk-input" id="form-stacked-text" type="text" name="nik" id="nik"/>
                </div>
            </div>
            
            <input class="uk-button uk-button-primary" type="submit" name="submit" value="Submit" />
            <input class="uk-button" type="submit" name="load_data" value="Load Data" />
        </form>
    </div>
</div>
 <?php
// PHP Data Objects(PDO) Sample Code:
try {
    $conn = new PDO("sqlsrv:server = tcp:dicodingkotabpn.database.windows.net,1433; Database = dicodingbpn", "dicodingbpn", "B4l!kp4p4n");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

    if (isset($_POST['submit'])) {
        try {
            $name = $_POST['name'];
            $nim = $_POST['nim'];
            $nik = $_POST['nik'];
            $date = date("Y-m-d");
            // Insert data
            $sql_insert = "INSERT INTO Absensi (name, nim, nik, date) 
                        VALUES (?,?,?,?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bindValue(1, $name);
            $stmt->bindValue(2, $nim);
            $stmt->bindValue(3, $nik);
            $stmt->bindValue(4, $date);
            $stmt->execute();
        } catch(Exception $e) {
            echo "Failed: " . $e;
        }
        echo "<h3>Your're registered!</h3>";
    } else if (isset($_POST['load_data'])) {
        try {
            $sql_select = "SELECT * FROM Absensi";
            $stmt = $conn->query($sql_select);
            $registrants = $stmt->fetchAll(); 
            if(count($registrants) > 0) {
                echo "<h2>People who are registered:</h2>";
                echo "<table>";
                echo "<tr><th>Name</th>";
                echo "<th>Nim</th>";
                echo "<th>Nik</th>";
                echo "<th>Date</th></tr>";
                foreach($registrants as $registrant) {
                    echo "<tr><td>".$registrant['name']."</td>";
                    echo "<td>".$registrant['nim']."</td>";
                    echo "<td>".$registrant['nik']."</td>";
                    echo "<td>".$registrant['date']."</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<h3>No one is currently registered.</h3>";
            }
        } catch(Exception $e) {
            echo "Failed: " . $e;
        }
    }
 ?>
 </body>
 </html>
