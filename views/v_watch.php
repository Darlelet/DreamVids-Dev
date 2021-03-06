<?php

?>

<div class="container">
	<div class="container" style="">
		<div class="border-top"></div>
			<h1><?php echo secure($title); ?><small> <?php echo $lang['by']; ?> <a href="./index.php?page=member&name=<?php echo secure($author->getName() ); ?>"><?php echo secure($author->getName() ); ?></a></small></h1>
		<div class="border-bottom"></div>

		<br><br>
	</div>

	
	<?php
	if(isset($session) && Watch::isModerator($session)) {
	?>

	<div id='moderatingCommands' class='container'>
		<form method='post' action='' role='form'>
			<button class='btn btn-warning' name='suspend_vid'>Suspendre</button>
			<button type='submit' class='btn btn-info' name='send_message_author'>Envoyer un message au créateur</button>
			<button type='submit' class='btn btn-info' name='send_message_admin'>Envoyer un message à un admin</button>
			<button type='submit' class='btn btn-danger' name='request_delete_vid'>Demander la suppression</button>
		</form>
	</div>

	<br>

	<?php
	}
	?>

	<?php
	if(isset($err)) {
		echo '<div class="alert alert-danger">'.$lang['error'].': '.$err.'</div>';
	}
	else {
	?>

	<div class="container" style="">
		<div id="player">
			<video autobuffer preload="auto" poster="<?php echo ($tumbnail != '') ? secure($tumbnail) : secure($path).'.jpg'; ?>" autoplay><img src="img/loadervids.gif" alt="" /><br><b><?php echo $lang['loading_video']; ?></video>
			<span id="repeat">
				<span class="icon"></span>
			</span>
			<span id="qualitySelection" class="show"></span>
			<span id="bigPlay"></span>
			<span id="bigPause"></span>
			<div id="controls">
				<span id="progress">
					<span id="buffered"></span>
					<span id="viewed"></span>
					<span id="current"></span>
				</span>
				<span id="play-pause"></span>
				<span id="time"></span>
				<span id="qualityButton">SD</span>
				<span id="volume">
					<span id="barre"></span>
					<span id="icon"></span>
				</span>
				<span id="fullscreen"></span>
			</div>
		</div>
	</div>

	<br />
	
	<div class="container">
	<?php
	if (isset($session) && $session->getId() != $author->getId() )
	{
		if (in_array($author->getId(), $session->getSubscriptions() ) )
		{
	?>
	<button id="subscribe-<?php echo secure($author->getId() ); ?>" class="btn btn-danger" data-subscribe="S'abonner" data-unsubscribe="Abonné" data-onmouseover="Se désabonner" data-subscribers="<?php echo secure($author->getSubscribers() ); ?>" onclick="unsubscribe(<?php echo secure($author->getId() ); ?>)" onmouseover="this.innerHTML=this.getAttribute('data-onmouseover')" onmouseout="this.innerHTML=this.getAttribute('data-unsubscribe')">Abonné</button>
	<?php 
		}
		else
		{
	?>
	<button id="subscribe-<?php echo secure($author->getId() ); ?>" class="btn btn-success" data-subscribe="S'abonner" data-unsubscribe="Abonné" data-onmouseover="Se désabonner"data-subscribers="<?php echo secure($author->getSubscribers() ); ?>" onclick="subscribe(<?php echo secure($author->getId() ); ?>)">S'abonner (<?php echo secure($author->getSubscribers() ); ?>)</button>
	<?php 
		}
	}
	?>

	<?php
	if(isset($GLOBALS['session'])) {
		?>
		<br /><br />
		<img src="img/videos/positive.png" onclick="like('<?php echo secure($_GET['vid']); ?>')" width="32" style="cursor:pointer" alt="Like" /> <span <?php echo $isLiked; ?> id="like-<?php echo secure($_GET['vid']); ?>"><?php echo $likes; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<img src="img/videos/negative.png" onclick="dislike('<?php echo secure($_GET['vid']); ?>')" width="32" style="cursor:pointer" alt="Dislike" /> <span <?php echo $isDisliked; ?> id="dislike-<?php echo secure($_GET['vid']); ?>"><?php echo $dislikes; ?></span>
		<br /><br />
		<?php
	}
	?>
		<a href="https://twitter.com/share" class="twitter-share-button" data-text="''<?php echo (strlen($title) > 50) ? substr($title, 0, 50).'...' : $title; ?>'' sur @DreamVids_ ! Check this out !" data-lang="fr">Tweeter</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-share-button" data-href="http://dremavids.fr/page=watch&vid=<?php echo htmlspecialchars($_GET['vid']); ?>" data-type="button_count"></div><br />
	<br />
	<div class="panel panel-primary" style="width: 56%;">
		<div class="panel-heading">
			<?php echo $lang['desc']; ?>
		</div>
		<div class="panel-body">
			<?php echo bbcode(secure($desc)); ?>
			</div>
		</div>

		<br><h2>Commentaires</h2><br>
		<?php if(isset($session)) { ?>
			<form onsubmit="comment(<?php echo '\''.$_GET['vid'].'\', \''.secure($session->getName() ).'\''; ?>, this.text_comment.value);return false" method="post" action="">
				<div class="form-group">
					<textarea id="text_comment" class="form-control" required rows="8" cols="50" placeholder="Commentaire..."></textarea>
				</div>
				<div class="form-group">
					<input class="btn btn-primary btn-success" type="submit" value="Envoyer" />
				</div>
			</form>
		<?php } ?>
		<br><br>
		<div id="new_comments"></div>
		<?php
		$comms = Watch::getComments($id);
		foreach ($comms as $comm) {
			$date = @date('d/m/Y H:i:s', $comm->getTimestamp() );
			?>

			<div class="panel panel-default" style="width: 100%;">
				<div class="panel-heading">
					<h5><?php echo User::getNameById($comm->getAuthorId()); ?> <small><?php echo $date; ?></small></h5>
				</div>
				<div class="panel-body">
					<p><?php echo secure($comm->getContent() ); ?></p>
				</div>
			</div>

			<?php
		}
		?>


	</div>

	<?php
	}
	?>
</div>

<!-- Comment post window -->
<div class="modal fade" id="postCommentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Poster un commentaire</h4>
			</div>
			<div class="modal-body">
				<form method="post" action="" role="">
					<label for="comment">Commentaire</label>
					<textarea cols="10" rows='10' name="comment" class="form-control"></textarea>
					<br>
					<input type='submit' class='btn btn-primary' value="Poster" name="addKeySubmit">
				</form>
			</div>
		</div>
	</div>
</div>

<!-- video player body-->
	<script src="dreamplayer/js/player.js"></script>
	<script src="utils/videoinfo.php?vid=<?php echo secure($id); ?>"></script>
<!-- End -->