<?php
require_once('./includes/config.php');

$stmt = $db->prepare('SELECT post_id, post_title, post_content, post_date
	FROM blog_posts
	WHERE post_id = :post_id');
	$stmt->execute(array(':post_id' => $_GET['post_id']));
	$row = $stmt->fetch();

	if($row['post_id'] == ''){
		header('Location: ./');
		exit;
	}
	?>

	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Blog - <?php echo $row['post_title'];?></title>
		<link rel="stylesheet" href="style/normalize.css">
		<link rel="stylesheet" href="style/main.css">
	</head>
	<body>
		<div id="container">
			<div id="content">
				<h1>Blog</h1>
				<hr />
				<p><a href="./">Blog index</a></p>

				<?php
				echo '<div>';
				echo '<h1>'.$row['post_title'].'</h1>';
				echo '<p>Geplaatst op '.date('d-m-Y H:i:s', strtotime($row['post_date'])).' in ';

				$stmt2 = $db->prepare('SELECT * FROM blog_categories, blog_post_categories
					WHERE blog_categories.category_id = blog_post_categories.category_id AND blog_post_categories.post_id = :post_id');
					$stmt2->execute(array(':post_id' => $row['post_id']));
					$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
					$links = array();

					foreach ($catRow as $cat)
					{
						$links[] = "<a href='".$cat['category_id']."'>".$cat['category_title']."</a>";
					}

					echo implode(", ", $links);
					echo '</p>';
					echo '<p>'.$row['post_content'].'</p>';
					echo '</div>';
					?>
				</div>

				<div id="sidebar">
					<?php
					require_once('./includes/sidebar.php');
					?>
				</div>
			</div>
		</body>
		</html>
