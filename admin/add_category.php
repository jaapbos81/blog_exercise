<?php
require_once('../includes/config.php');
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - categorie toevoegen</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
</head>

<body>
  <div id="container">
    <div id="content">
      <p><a href="categories.php">Categorieen Index</a></p>
      <h2>Categorie toevoegen</h2>

      <?php
      if(isset($_POST['submit']))
      {
        $_POST = array_map('stripslashes', $_POST);

        extract($_POST);

        if($category_title == "")
        {
          $error[] = "Geef a.u.b. een categorie op";
        }

        if(!isset($error))
        {
          try
          {
            $stmt = $db->prepare('INSERT INTO blog_categories (category_title) VALUES (:category_title)') ;
            $stmt->execute(array(
              ':category_title' => $category_title
            ));

            header('Location: categories.php?action=toegevoegd');
            exit;
          }
          catch(PDOException $e)
          {
            echo $e->getMessage();
          }
        }
      }

      if(isset($error))
      {
        foreach($error as $error)
        {
          echo "<p class='error'>$error</p>";
        }
      }
      ?>

      <form action="" method="POST">
        <p><label>Titel</label><br />
          <input type="text" name="category_title" value="<?php if(isset($error)){ echo $_POST['category_title'];}?>"></p>
          <p><input type="submit" name="submit" value="Bevestigen"></p>
        </form>
      </div>

      <div id="sidebar">
        <?php
        require_once('./menu.php');
        ?>
      </div>
    </div>
  </body>
  </html>
