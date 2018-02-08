<?php
    echo "<p>Categorieen</p>";
    echo "<ul>";
    echo "<li><a href='index.php'>Alle categorieen</a></li>";

    try
    {
        $stmt = $db->query('SELECT category_id, category_title FROM blog_categories ORDER BY category_title DESC');

        while($row = $stmt->fetch())
        {
            echo "<li>";
            echo "<a href='categories.php?category_id=" . $row['category_id'] . "'>";
            echo $row['category_title'];
            echo "</a>";
            echo "</li>";
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
?>
