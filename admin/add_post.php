<?php
    require_once('../includes/config.php');
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Admin - post toevoegen</title>
        <link rel="stylesheet" href="../style/normalize.css">
        <link rel="stylesheet" href="../style/main.css">
        <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
        <script>
            tinymce.init({
                selector: "textarea",
                plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            });
        </script>
    </head>

    <body>
        <div id="container">
            <div id="content">
            	<h2>Post toevoegen</h2>

            	<?php
                	if(isset($_POST['submit']))
                  {
                  		extract($_POST);

                  		if($post_title == '')
                      {
                  			   $error[] = 'Voer a.u.b. een titel in';
                  		}

                  		if($post_description =='')
                      {
                  			   $error[] = 'Voer a.u.b. een beschrijving in';
                  		}

                  		if($post_content =='')
                      {
                  			   $error[] = 'Voer a.u.b. een bericht in';
                  		}

                  		if(!isset($error))
                      {
                    			try
                          {
                      				$stmt = $db->prepare('INSERT INTO blog_posts (post_title,post_description,post_content,post_date)
                                  VALUES (:post_title, :post_description, :post_content, :post_date)') ;
                      				$stmt->execute(array(
                        					':post_title' => $post_title,
                        					':post_description' => $post_description,
                        					':post_content' => $post_content,
                        					':post_date' => date('Y-m-d H:i:s')
                      				));

                      				$post_id = $db->lastInsertId();

                      				if(is_array($category_id)){
                        					foreach($_POST['category_id'] as $category_id){
                          						$stmt = $db->prepare('INSERT INTO blog_post_categories (post_id,category_id)VALUES(:post_id,:category_id)');
                          						$stmt->execute(array(
                          							':post_id' => $post_id,
                          							':category_id' => $category_id
                          						));
                        					}
                      				}

                      				header('Location: index.php?action=toegevoegd');
                      				exit;
                    			}
                          catch(PDOException $e)
                          {
                    			    echo $e->getMessage();
                    			}
                  		}
                	}

                	//check for any errors
                	if(isset($error))
                  {
                  		foreach($error as $error)
                      {
                    			echo '<p class="error">'.$error.'</p>';
                  		}
                	}
            	?>

            	<form action='' method='post'>
            		<p><label>Titel</label><br />
            		<input type='text' name='post_title' value='<?php if(isset($error)){ echo $_POST['post_title'];}?>'></p>

            		<p><label>Omschrijving</label><br />
            		<textarea name='post_description' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['post_description'];}?></textarea></p>

            		<p><label>Inhoud</label><br />
            		<textarea name='post_content' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['post_content'];}?></textarea></p>

            		<fieldset>
              			<legend>Categorieen</legend>

              			<?php
                			$stmt2 = $db->query('SELECT category_id, category_title FROM blog_categories ORDER BY category_title');
                      $checked = "";

                			while($row2 = $stmt2->fetch())
                      {
                  				if(isset($_POST['category_id']))
                          {
                    					if(in_array($row2['category_id'], $_POST['category_id']))
                              {
                                  $checked = "checked='checked'";
                              }
                              else
                              {
                                  $checked = "";
                              }
                  				}

                          echo "<input type='checkbox' name='category_id[]' value='".$row2['category_id']."' $checked> ".$row2['category_title']."<br />";
                  			}
              			?>
            		</fieldset>

            		<p><input type='submit' name='submit' value='Bevestigen'></p>
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
