<?php
require_once('../includes/config.php');

if(isset($_GET['delete_post'])){
	$stmt = $db->prepare('DELETE FROM blog_posts WHERE post_id = :post_id') ;
	$stmt->execute(array(':post_id' => $_GET['delete_post']));

	$stmt = $db->prepare('DELETE FROM blog_post_categories WHERE post_id = :post_id');
	$stmt->execute(array(':post_id' => $_GET['delete_post']));

	header('Location: index.php?action=verwijderd');
	exit;
}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Admin</title>
	<link rel="stylesheet" href="../style/normalize.css">
	<link rel="stylesheet" href="../style/main.css">
	<script language="JavaScript" type="text/javascript">
	function delete_post(id, title)
	{
		if (confirm("Weet u zeker dat u '" + title + "' wilt verwijderen?"))
		{
			window.location.href = 'index.php?delete_post=' + id;
		}
	}
	</script>
</head>

<body>
	<div id="container">
		<div id="content">
			<h1>Blog berichten</h1>

			<?php
			if(isset($_GET['action']))
			{
				echo '<h3>Post '.$_GET['action'].'.</h3>';
			}
			?>

			<table>
				<tr>
					<th>Titel</th>
					<th>Datum</th>
					<th>Actie</th>
				</tr>

				<?php
				try
				{
					$stmt = $db->query('SELECT post_id, post_title, post_date FROM blog_posts ORDER BY post_id DESC');

					while($row = $stmt->fetch()){
						echo '<tr>';
						echo '<td>'.$row['post_title'].'</td>';
						echo '<td>'.date('d-m-Y', strtotime($row['post_date'])).'</td>';
						?>

						<td>
							<a href="javascript:delete_post(<?php echo $row['post_id'];?>,'<?php echo $row['post_title'];?>')">Verwijderen</a>
						</td>

						<?php
						echo '</tr>';
					}
				}
				catch(PDOException $e)
				{
					echo $e->getMessage();
				}
				?>
			</table>
			<p><a href='add_post.php'>Post toevoegen</a></p>
		</div>

		<div id="sidebar">
			<?php
			require_once('menu.php');
			?>
		</div>
	</div>
</body>
</html>
