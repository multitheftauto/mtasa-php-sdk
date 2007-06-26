<?php 
include 'mta.php';

$port = $_GET['server'];

$server = "";

if ( $port == "" )
	$port = 4;

if ( $port >= 4 && $port <= 7 )
	$server = "bastage.student.utwente.nl:3300" . $port;

if ( $server != "" ) {
	?>
	<html>
		<head>
			<style type="text/css">
				#playerTable {
					width: 60%;
					border-collapse: collapse;
					margin-left: 20%;
					margin-right: 20%;
					border: 1px solid black;
				}
				
				#playerTable thead td {
					background: green;
				}

				#playerTable td {
					padding: 3px;
				}

				body {
					font-family: tahoma, arial, helvetica, sans-serif;
					font-size: 0.75em;
				}
				
			</style>
		</head>
		<body>
			<?
			$players = callFunction ( $server, "echobot", "getPlayers" );
			if ( is_array($players) )
			{ ?>
				Players on <?=$server ?>.
				<table id="playerTable">
					<thead>
						<tr>
							<td>
								Player Name
							</td>
							<td>
								Ping
							</td>
						</tr>
					</thead>
					<tbody>
					<?
					foreach ($players[0] as $player) 
					{
					?>
						<tr>
							<td>
								<?= $player["name"] ?>
							</td>
							<td>
								<?= $player["ping"] ?>
							</td>
						</tr>
					<? 
					}
					?>
					</tbody>
				</table>
				<? 
			}
			else 
			{
				?>
				Server is not running the 'echobot' resource.
				<? 
			}
			?>
		</body>
	</html>
<?
}
else
{
?>
	You must specify a port between 4 and 7 (inclusive)!
<?
}
?>