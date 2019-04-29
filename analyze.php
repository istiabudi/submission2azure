<?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=azurebejo;AccountKey=lUAaDVuJkw4mR793S4YJxLEsu+izo9kI+t/ZCMWTfN1ArphX2UJAgHxsMp31Icpqu84M5VUpq312abNQHK6l4g==;";
$blobClient = BlobRestProxy::createBlobService($connectionString);
// $fileToUpload = "HelloWorld.txt";
// $fileToUpload = "D:\home\site\wwwroot\halaman\uploads\Jellyfish.jpg";
$files = glob("D:\home\site\wwwroot\halaman\*.{jpg,gif,png}", GLOB_BRACE);
	usort( $files, function( $a, $b ) { return filemtime($b) - filemtime($a); } );
	$fileToUpload = $files[0];
	$filePath = $files[0];
	$fileToUpload = basename($filePath);
	$filePrefix = basename($filepath, ".jpg");
	$filePrefix = basename($filepath, ".jpeg");
	$filePrefix = basename($filepath, ".png");
	$filePrefix = basename($filepath, ".gif");
if (!isset($_GET["Cleanup"])) {
    // Create container options object.
    $createContainerOptions = new CreateContainerOptions();
    // Set public access policy. Possible values are
    // PublicAccessType::CONTAINER_AND_BLOBS and PublicAccessType::BLOBS_ONLY.
    // CONTAINER_AND_BLOBS:
    // Specifies full public read access for container and blob data.
    // proxys can enumerate blobs within the container via anonymous
    // request, but cannot enumerate containers within the storage account.
    //
    // BLOBS_ONLY:
    // Specifies public read access for blobs. Blob data within this
    // container can be read via anonymous request, but container data is not
    // available. proxys cannot enumerate blobs within the container via
    // anonymous request.
    // If this value is not specified in the request, container data is
    // private to the account owner.
    $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
    // Set container metadata.
    $createContainerOptions->addMetaData("key1", "value1");
    $createContainerOptions->addMetaData("key2", "value2");
      $containerName = "blockblobs".generateRandomString();
    try {
        // Create container.
        $blobClient->createContainer($containerName, $createContainerOptions);
        // Getting local file so that we can upload it to Azure
        $myfile = fopen($filePath, "r") or die("Unable to open file!");
        fclose($myfile);
        
        # Upload file as a block blob
        echo "Uploading BlockBlob: ".PHP_EOL;
        echo $fileToUpload;
        echo "<br />";
        
        $content = fopen($filePath, "r");
        //Upload blob
        $blobClient->createBlockBlob($containerName, $fileToUpload, $content);
        // List blobs.
        $listBlobsOptions = new ListBlobsOptions();
        $listBlobsOptions->setPrefix($filePrefix);
        echo "These are the blobs present in the container: ";
        do{
            $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
            foreach ($result->getBlobs() as $blob)
            {
		$imgUrl = $blob->getUrl();
                echo $blob->getName().": ".$blob->getUrl()."<br />";
		echo "<a href='$imgUrl'>Click Here To Download</a><br /><br />";
            }
        
            $listBlobsOptions->setContinuationToken($result->getContinuationToken());
        } while($result->getContinuationToken());
        echo "<br />";
        // Get blob.
        echo "if there are characters that you cannot read,<br />";
	echo "that means this file is not a text file <br />";
	echo "This is the content of the blob uploaded: <br /><br />";
        $blob = $blobClient->getBlob($containerName, $fileToUpload);
        fpassthru($blob->getContentStream());
        echo "<br />";
    }
    catch(ServiceException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
    catch(InvalidArgumentTypeException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
} 
else 
{
    try{
        // Delete container.
        echo "Deleting Container".PHP_EOL;
        echo $_GET["containerName"].PHP_EOL;
        echo "<br />";
        $blobClient->deleteContainer($_GET["containerName"]);
    }
    catch(ServiceException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
}
//$containerName = "blobistia";
// Create blob client.
//$blobClient = BlobRestProxy::createBlobService($connectionString);
//if (isset($_POST['submit'])) {
//	$fileToUpload = strtolower($_FILES["fileToUpload"]["name"]);
//	$content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");
//	// echo fread($content, filesize($fileToUpload));
//	$blobClient->createBlockBlob($containerName, $fileToUpload, $content);
//	header("Location: analyze.php");
//}
//$listBlobsOptions = new ListBlobsOptions();
//$listBlobsOptions->setPrefix("");
//$result = $blobClient->listBlobs($containerName, $listBlobsOptions);
?>

<!DOCTYPE html>
<html>
 <head>
 <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">

    <title>Universitas Mulia</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">

    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">

	<!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One|Maven+Pro|Righteous" rel="stylesheet">

    <style>
	
		body {
            background: #cecaca14;
        }

        .navbar {
			padding-left: 170px;
            background-image: linear-gradient(to right, #56CCF2, #2F80ED);
        }

        .nav-link {
            color : white;
        }

        .starter-template {
            background-image: linear-gradient(to right, #56CCF2, #2F80ED);
            position: relative;
            z-index: 1;
        }

        h1 {
            font-family: 'Fredoka One', cursive;
        }

        .lead {
            font-family: 'Maven Pro', sans-serif;
        }

        main {
            z-index: 2;
            position: relative;
            margin-top: -59px;
            background-color: white;
			padding-top : 0!important;
            padding: 30px;
            border-radius: 9px;
        }

		.mt-4 {
			padding: 13px;
			background-image: linear-gradient(to right,#17a3de, #20BDFF);
			box-shadow: -4px 5px 3px gainsboro;
			color: white;
		}
    </style>
  </head>
<body>
	<nav class="navbar navbar-expand-md fixed-top">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarsExampleDefault">
			<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="nav-link" href="https://dicodingisb.azurewebsites.net/">Home</a>
			</li>
			<li class="nav-item active">
				<a class="nav-link" href="https://dicodingisb.azurewebsites.net/analyze.php">Analisis Pribadi<span class="sr-only">(current)</span></a>
			</li>
		</div>
		</nav>

		<div class="starter-template"> <br><br><br>
        	<div class="container">
				<h1>Analisis Pribadi</h1>
					<p class="lead">Pilih Foto Pribadi Anda.<br> Kemudian Click <b>Upload</b>, untuk menganalisa foto pilih <b>analisa</b> pada tabel.</p>
					<span class="border-top my-3"></span> <br> <br><br>
				</div>
		</div>

		<main role="main" class="container">
    		<br>
		<div class="mt-4 mb-2">
			<form class="" action="analyze.php" method="post" enctype="multipart/form-data">
				<input type="file" name="fileToUpload" accept=".jpeg,.jpg,.png" required="">
				<input type="submit" name="submit" value="Upload">
			</form>
		</div>

		<br>
		<br>
		<h4>Total Files : <?php echo sizeof($result->getBlobs())?></h4>
		<table class='table table-hover table-striped'>
			<thead>
				<tr>
					<th>File Name</th>
					<th>File URL</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				do {
					foreach ($result->getBlobs() as $blob)
					{
						?>
						<tr>
							<td><?php echo $blob->getName() ?></td>
							<td><?php echo $blob->getUrl() ?></td>
							<td>
								<form action="computervision.php" method="post">
									<input type="hidden" name="url" value="<?php echo $blob->getUrl()?>">
									<input type="submit" name="submit" value="Analisa!" class="btn btn-primary">
								</form>
							</td>
						</tr>

						<?php
					}
					$listBlobsOptions->setContinuationToken($result->getContinuationToken());
				} while($result->getContinuationToken());
				?>
			</tbody>
		</table>

	</div>

<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
    <script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>
  </body>
</html>
