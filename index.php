<?php
    require_once('./includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Blog</title>
        <link rel="stylesheet" href="style/normalize.css">
        <link rel="stylesheet" href="style/main.css">
    </head>

    <body>
      <div id="container">
          <div id="content">
              <h1>Blog</h1>
              <hr />

              <?php
                try
                {
                    $stmt = $db->query('SELECT post_id, post_title, post_description, post_date
                      FROM blog_posts
                      ORDER BY post_id DESC');

                    while($row = $stmt->fetch())
                    {
                        echo '<div>';
                        echo '<h1><a href="'.$row['post_id'].'">'.$row['post_title'].'</a></h1>';
                        echo '<p>Geplaatst op '.date('d-m-Y H:i:s', strtotime($row['post_date'])).' in ';

                        $stmt2 = $db->prepare('SELECT blog_post_categories.category_id, blog_categories.category_title
                          FROM blog_categories, blog_post_categories
                          WHERE blog_categories.category_id = blog_post_categories.category_id
                          AND blog_post_categories.post_id = :post_id');

                        $stmt2->execute(array(':post_id' => $row['post_id']));
                        $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                        $links = array();

                        foreach ($catRow as $cat)
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
