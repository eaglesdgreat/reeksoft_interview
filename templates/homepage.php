<?php include "templates/include/header.php" ?>

    <ul id="headlines">
        <?php foreach ( $results['frogs'] as $frog ) { ?>
            <li>
                <h2>
                    <span class="pubDate">
                        <?php echo date('j F', $frog->created_at)?>
                    </span>
                    <a href=".?action=viewFrog&amp;frogId=<?php echo $frog->id?>">
                        <?php echo htmlspecialchars( $frog->species )?>
                    </a>
                </h2>
                <p class="summary"><?php echo htmlspecialchars( $frog->color )?></p>
            </li>
        <?php } ?>
    </ul>

    <p><a href="./?action=archive">Frog Archive</a></p>

<?php include "templates/include/footer.php" ?>