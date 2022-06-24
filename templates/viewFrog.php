<?php include "templates/include/header.php" ?>

    <h1 style="width: 75%;">
        <?php echo htmlspecialchars( $results['frog']->species )?>
    </h1>
    <div style="width: 75%; font-style: italic;">
        <?php echo htmlspecialchars( $results['frog']->color )?>
    </div>
    <div style="width: 75%;">
        <?php echo $results['frog']->weight_kg?>
    </div>
    <p class="pubDate">Created on <?php echo date('j F Y', $results['frog']->created_at)?></p>

    <p><a href="./">Return to Homepage</a></p>

<?php include "templates/include/footer.php" ?>