<html>
	<head>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.css" rel="stylesheet" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.js" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
		</script>
	</head>
	<body>
<?php
include("config.php");
$dbh  = new PDO($dir) or die("cannot open the database");

?>
	
<div class="container">
	<div class="row">
		<div class="col">
			<h2>StreamInfo</h2>
		<?php
			/*Stream Info*/
			foreach ($dbh->query("SELECT * FROM phantombot_streamInfo WHERE variable = 'game'") as $row => $value)
			{
				echo "Game: ".$value['2']."<br>";
			}
			foreach ($dbh->query("SELECT * FROM phantombot_streamInfo WHERE variable = 'title'") as $row => $value)
			{
				echo "Stream Title: ".$value['2']."<br>";
			}
			foreach ($dbh->query("SELECT * FROM phantombot_streamInfo WHERE variable = 'lastSub'") as $row => $value)
			{
				echo "Last Sub: ".$value['2']."<br>";
			}
			foreach ($dbh->query("SELECT * FROM phantombot_streamInfo WHERE variable = 'last_clip_url'") as $row => $value)
			{
				echo "Last Clip: <a href=".$value['2']." target='_blank'>".$value['2']."</a><br>";
			}
			?>
		</div>
	</div>


  <div class="row">
    <div class="col">
		<h2>Audio Befehle</h2>
		<table class="table table-success table-striped">
			<thead>
				<tr>
					<th scope="col">Commands</th>
					<th scope="col">Audio sample</th>
				</tr>
			</thead>
			<tbody>
				  <?php
				  /*Audio*/
					$query =  "SELECT * FROM phantombot_audioCommands ";
					foreach ($dbh->query($query) as $row)
					{
						if($handle = opendir($verzeichnis))
						{
							while (($file = readdir($handle)) !== false)
							{
								if($file == $row['value'].".mp3"){
									
									$oldfile = $verzeichnis.$file;
									$newfile = 'tmp/tmp.'.$file;
									if(file_exists($newfile) == false){
										copy($oldfile, $newfile);
									}
								}
							}
							closedir($handle);
						}
						echo "<tr><td>!".$row['variable'].'</td> 
							  <td><audio controls src="tmp/tmp.'.$row['value'].'.mp3" type="audio/mp3"></audio></td></tr>';
					}
					?>
			</tbody>
		</table>
    </div>
    <div class="col">
	<h2>Normale Befehle</h2>
		<table class="table table-success table-striped">
			<thead>
				<tr>
					<th scope="col">Commands</th>
					<th scope="col">Output</th>
				</tr>
			</thead>
			<tbody>
				
				
	
				<?php
				/*Commands*/
				$query =  "SELECT * FROM phantombot_command ";
				$query2 =  "SELECT * FROM phantombot_aliases ";
				$query3 =  "SELECT * FROM phantombot_externalCommands ";

				foreach ($dbh->query($query) as $row)
				{
					echo "<tr><td>!".$row['variable']."</td>
						  <td>".$row['value']."</td></tr>";
				}
				foreach ($dbh->query($query2) as $row)
				{
					echo "<tr><td>!".$row['variable']."</td>
						  <td>Alias zu: !".$row['value']."</td></tr>";
				}
				?>
			</tbody>
		</table>
		
				<?php
				echo "<h2>External Commands:</h2>";
				foreach ($dbh->query($query3) as $row)
				{
					echo "<span class='badge bg-primary text-wrap'>!".$row['variable']."</span> | ";
				}

				/*Discord*/
				echo "<h2>Discord Commands</h2>";
				$query =  "SELECT * FROM phantombot_discordCommands ";
				foreach ($dbh->query($query) as $row)
				{
					echo "<span class='badge bg-primary text-wrap'>!".$row['variable']."</span> | ";
				}
				?>
    </div>
  </div>
</div>

<?php
/*Close DB*/
$dbh = null; //This is how you close a PDO connection
?>
	</body>
</html>
