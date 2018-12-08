<?php function draw_stories($stories) {

?>
    <script src="../javascript/main.js" defer></script>
        
    <section id="stories_content">
        
        <?php foreach ($stories as $story){ ?>
        <div>
            <a href="../pages/story.php?id=<?=$story['id']?>"> <?=$story['title']?> <br></a>
            <p><?=$story['textC']?><br></p>
            <p>Published: <?=$story['dateC']?><br></p>
            <p><?=$story['N_Comments']?> Comments<br></p>
            <img class="up-vote"  data-id="<?=$story['id']?>" src = "../img/upvote.png" alt="Upvote" width="20" height="20">
            <img class="down-vote"  data-id="<?=$story['id']?>" src = "../img/downvote.png" alt="Downvote" width="20" height="20">
            <br>
        </div>      
        <?php } ?>
    </section>
<?php } ?>



<?php function draw_story($story) {

?>
    <section id="stories_sec">    
        <section id="stories_content">
            <div>
                <h1><?=$story['title']?> <br></h1>
                <p><?=$story['textC']?><br></p>
                <p>Published: <?=$story['dateC']?><br></p>
                <p><?=$story['N_Comments']?> Comments<br></p>
                <br>
            </div>      
        </section>
<?php } ?>


<?php function draw_comments($comments) {

?>
        <section id="comments_content">
            <a>Comments: </a>
            <?php foreach ($comments as $comment){ ?>
                <div>
                    <p><?=$comment['textC']?><br></p>
                    <p>Published: <?=$comment['dateC']?><br></p>
                </div>      
            <?php } ?>
        </section>
    </section>
<?php } ?>