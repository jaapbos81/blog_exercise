<?php
		require_once('../includes/config.php');

		if(isset($_GET['delete_category'])){
				$stmt = $db->prepare('DELETE FROM blog_categories WHERE category_id = :category_id') ;
				$stmt->execute(array(':category_id' => $_GET['delete_category']));

				header('Location: categories.php?action=verwijderd');
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
					  function delete_category(id, title)
						{
							  if (confirm("Weet u zeker dat u '" + title + "' wilt verwijderen?"))
							  {
							  		window.location.href = 'categories.php?delete_category=' + id;
							  }
						}
			  </script>
		</head>

		<body>
				<div id="container">
						<div id="content">
							<h1>Categorieen</h1>

							<?php
								if(isset($_GET['action'])){
									echo '<h3>Categorie '.$_GET['action'].'.</h3>';
								}
							?>

							<table>
								<tr>
										<th>Titel</th>
										<th>Aktie</th>
								</tr>

								<?php
										try
										{
												$stmt = $db->query('SELECT category_id, category_title FROM blog_categories ORDER BY category_title DESC');

												while($row = $stmt->fetch())
												{
														echo '<tr>';
														echo '<td>'.$row['category_title'].'</td>';
								?>

														<td>
																<a href="javascript:delete_category(<?php echo $row['category_id'];?>, '<?php echo $row['category_title'];?>')">Verwijderen</a>
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

							<p><a href='add_category.php'>Categorie toevoegen</a></p>
						</div>

						<div id="sidebar">
			          <?php
			            require_once('./menu.php');
			          ?>
		        </div>
					</div>
		</body>
</html>
