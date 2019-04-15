<html>
 <head>
 <Title>FORM PENDAFTARAN</Title>
 <style type="text/css">
 	body { background-color: #fff; border-top: solid 10px #000;
 	    color: #333; font-size: .85em; margin: 20; padding: 20;
 	    font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
 	}
 	h1, h2, h3,{ color: #000; margin-bottom: 0; padding-bottom: 0; }
 	h1 { font-size: 2em; }
 	h2 { font-size: 1.75em; }
 	h3 { font-size: 1.2em; }
 	table { margin-top: 0.75em; }
 	th { font-size: 1.2em; text-align: left; border: none; padding-left: 0; }
 	td { padding: 0.25em 2em 0.25em 0em; border: 0 none; }
 </style>
 </head>
 <body>
 <h1>UNIVERSITAS MULIA ABSENSI!</h1>
 <p>Isi Data yang Nama, NIM and NIK, then click <strong>Submit</strong> to register.</p>
 <form method="post" action="register.php" enctype="multipart/form-data" >
       Nama  <input type="text" name="name" id="name"/></br></br>
       NIM <input type="text" name="nim" id="nim"/></br></br>
       NIK <input type="text" name="nik" id="nik"/></br></br>
       <input type="submit" name="submit" value="Submit" />
       <input type="submit" name="load_data" value="Load Data" />
 </form>
 <?php
// PHP Data Objects(PDO) Sample Code:
try {
    $conn = new PDO("sqlsrv:server = tcp:um-absensi.database.windows.net,1433; Database = absensi", "absensi", "Abs3ns!2019");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

// SQL Server Extension Sample Code:
//$connectionInfo = array("UID" => "dicodingbpn@dicodingkotabpn", "pwd" => "B4l!kp4p4n", "Database" => "dicodingbpn", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
//$serverName = "tcp:dicodingkotabpn.database.windows.net,1433";
//$conn = sqlsrv_connect($serverName, $connectionInfo);



//    $host = "<Nama server database Anda>";
//    $user = "<Nama admin database Anda>";
//    $pass = "<Password admin database Anda>";
//    $db = "<Nama database Anda>";
//    try {
//        $conn = new PDO("sqlsrv:server = $host; Database = $db", $user, $pass);
//        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
//    } catch(Exception $e) {
//        echo "Failed: " . $e;
    }
    if (isset($_POST['submit'])) {
        try {
            $name = $_POST['name'];
            $nim = $_POST['nim'];
            $nik = $_POST['nik'];
            $date = date("Y-m-d");
            // Insert data
            $sql_insert = "INSERT INTO Absence (name, nim, nik, date) 
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
            $sql_select = "SELECT * FROM Absence";
            $stmt = $conn->query($sql_select);
            $registrants = $stmt->fetchAll(); 
            if(count($registrants) > 0) {
                echo "<h2>People who are registered:</h2>";
                echo "<table>";
                echo "<tr><th>Name</th>";
                echo "<th>NIM</th>";
                echo "<th>NIK</th>";
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