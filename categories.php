<?php
require_once('./includes/config.php');

if(!isset($_GET['category_id']))
{
	header('Location: ./');
	exit;
}

$stmt = $db->prepare('SELECT category_id, category_title FROM blog_categories WHERE category_id = :category_id');
$stmt->execute(array(':category_id' => $_GET['category_id']));
$row = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Blog - <?php echo $row['category_title'];?></title>
	<link rel="stylesheet" href="style/normalize.css">
	<link rel="stylesheet" href="style/main.css">
</head>

<body>
	<div id="container">
		<div id="content">
			<h1>Blog</h1>
			<p>Posts in categorie: <?php echo $row['category_title'];?></p>
			<hr />
			<p><a href="./">Blog index</a></p>

			<?php
			try
			{
				$stmt = $db->prepare('
				SELECT
				blog_posts.post_id, blog_posts.post_title, blog_posts.post_description, blog_posts.post_date
				FROM
				blog_posts,
				blog_post_categories
				WHERE
				blog_posts.post_id = blog_post_categories.post_id
				AND blog_post_categories.category_id = :category_id
				ORDER BY
				post_id DESC
				');

				$stmt->execute(array(':category_id' => $row['category_id']));

				while($row = $stmt->fetch())
				{
					echo '<div>';
					echo '<h1><a href="'.$row['post_id'].'">'.$row['post_title'].'</a></h1>';
					echo '<p>Geplaatst op '.date('d-m-Y H:i:s', strtotime($row['post_date'])).' in ';

					$stmt2 = $db->prepare('SELECT blog_categories.category_id, blog_categories.category_title
						FROM blog_categories, blog_post_categories
						WHERE blog_categories.category_id = blog_post_categories.category_id
						AND blog_post_categories.post_id = :post_id');

						$stmt2->execute(array(':post_id' => $row['post_id']));
						$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
						$links = array();

						foreach($catRow as $cat)
						{
							$links[] = "<a href='categories.php?category_id=".$cat['category_id']."'>".$cat['category_title']."</a>";
						}

						echo implode(", ", $links);
						echo '</p>';
						echo '<p>'.$row['post_description'].'</p>';
						echo '<p><a href="view_post.php?post_id='.$row['post_id'].'">Lees meer</a></p>';
						echo '</div>';
					}
				}
				catch(PDOException $e)
				{
					echo $e->getMessage();
				}
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
