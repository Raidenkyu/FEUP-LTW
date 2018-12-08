<?php
  include_once('../includes/database.php');
  /**
   * Verifies if a certain username, password combination
   * exists in the database. Use the sha1 hashing function.
   */
  function recentStories() {
    $db = Database::instance()->db();
    $cmd = 'SELECT Story.idStory as id, Story.title as title, c1.textC as textC, c1.dateC as dateC, count(Comment.idComment) AS N_Comments, (c1.n_upvotes - c1.n_downvotes) as votes
            FROM Story 
            LEFT JOIN Commentable as c1 ON Story.idStory = c1.idCommentable 
            LEFT JOIN Comment ON Comment.idParent = Story.idStory 
            LEFT JOIN Commentable as c2 ON Comment.idComment = c2.idCommentable
            GROUP BY Story.idStory
            ORDER BY c1.dateC DESC';
    $stmt = $db->prepare($cmd);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  function hotStories() {
    $db = Database::instance()->db();
    $cmd = 'SELECT Story.idStory as id, Story.title as title, c1.textC as textC, c1.dateC as dateC, count(Comment.idComment) AS N_Comments, (c1.n_upvotes - c1.n_downvotes) as votes
    FROM Story 
    LEFT JOIN Commentable as c1 ON Story.idStory = c1.idCommentable 
    LEFT JOIN Comment ON Comment.idParent = Story.idStory 
    LEFT JOIN Commentable as c2 ON Comment.idComment = c2.idCommentable
    GROUP BY Story.idStory
    ORDER BY votes DESC';
    $stmt = $db->prepare($cmd);
    $stmt->execute();
    return $stmt->fetchAll();
  }


  function controversialStories() {
    $db = Database::instance()->db();
    $cmd = 'SELECT Story.idStory as id, Story.title as title, Commentable.textC as textC, Commentable.dateC as dateC, count(Comment.idComment) AS N_Comments
    FROM Story,Commentable, Comment
    WHERE Commentable.idCommentable = Story.idStory AND Comment.idParent = Commentable.idCommentable
    GROUP BY Story.idStory
    ORDER BY Commentable.dateC DESC';
    $stmt = $db->prepare($cmd);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  function getStory($id) {
    $db = Database::instance()->db();
    $cmd = 'SELECT Story.idStory as id, Story.title as title, c1.textC as textC, c1.dateC as dateC, count(Comment.idComment) AS N_Comments, (c1.n_upvotes - c1.n_downvotes) as votes
    FROM Story 
    LEFT JOIN Commentable as c1 ON Story.idStory = c1.idCommentable 
    LEFT JOIN Comment ON Comment.idParent = Story.idStory 
    LEFT JOIN Commentable as c2 ON Comment.idComment = c2.idCommentable
    WHERE Story.idStory = ?
    GROUP BY Story.idStory';
    $stmt = $db->prepare($cmd);
    $stmt->execute(array($id));
    return $stmt->fetch();
  }

  function getComments($storyId) {
    $db = Database::instance()->db();
    $cmd = 'SELECT Comment.idComment as id, Commentable.textC as textC, Commentable.dateC as dateC
    FROM Comment LEFT JOIN Commentable ON Comment.idComment = Commentable.idCommentable
    WHERE Comment.idParent = ? 
    GROUP BY Comment.idComment
    ORDER BY Commentable.dateC DESC';
    $stmt = $db->prepare($cmd);
    $stmt->execute(array($storyId));
    return $stmt->fetchAll();
  }

  function insertStory($title, $text,$userId) {
    $db = Database::instance()->db();
    $date = date("Y-m-d H:i");
    $stmt = $db->prepare('INSERT INTO Commentable(textC,dateC,idUser,n_upvotes,n_downvotes) VALUES(?, Date(?), ?, 0,0)');
    $value = $stmt->execute(array($text,$date,$userId));
    if($value == false){
      return $value;
    }
    $stmt2 = $db->prepare('SELECT last_insert_rowid()');
    $value2 = $stmt2->execute();
    $storyId = $stmt2->fetchColumn();
    var_dump($storyId);
    if($value2 == false){
      return $value2;
    }
    $stmt3 = $db->prepare('INSERT INTO Story(idStory,title) VALUES(?, ?)');
    $value3 = $stmt3->execute(array($storyId,$title));
    return $value3;
    
  }

  function insertVote($id,$userId,$value) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('INSERT INTO UserVote(voteVal,idUser,IdCommentable) VALUES(?,?,?)');
    $ret = $stmt->execute(array($value,$userId,$id));
    return array($id,$value);
  }

?>