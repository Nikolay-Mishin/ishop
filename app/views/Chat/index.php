<?=$breadcrumbs;?>

<div id="chat-action">
	<input type="button" id="chat-start" value="start" >
	<input type="button" id="chat-stop" value="stop" >
	<input type="button" id="chat-clean" value="clean" >
</div>

<form id="chat" action="">
	<div class="chat-result" id="chat-result">
		<input type="text" name="chat-user" id="chat-user" placeholder="Name">
		<input type="text" name="chat-message" id="chat-message"  placeholder="Message">
		<input type="submit" value="Send" >
	</div>
</form>

<div id="result"></div>

<? require_once CONF.'/chat.php'; ?>
