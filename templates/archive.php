<?php include "templates/include/header.php" ?>

    <h1>Frog Archive</h1>

    <ul id="headlines" class="archive">
        <?php foreach ( $results['frogs'] as $frog ) { ?>
            <li>
                <h2>
                    <span class="pubDate">
                        <?php echo date('j F Y', $frog->created_at)?>
                    </span>
                    <a href=".?action=viewFrog&amp;frogId=<?php echo $frog->id?>">
                        <?php echo htmlspecialchars( $frog->species )?>
                    </a>
                </h2>
                <p class="summary"><?php echo htmlspecialchars( $frog->color )?></p>
            </li>
        <?php } ?>
    </ul>

    <p><?php echo $results['totalRows']?> frog<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>

    <p><a href="./">Return to Homepage</a></p>

<?php include "templates/include/footer.php" ?>