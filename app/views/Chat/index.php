<div id="chat-action">
	<input type="button" id="chat-start" value="start" >
	<input type="button" id="chat-stop" value="stop" >
</div>
<div id="result"></div>

<form id="chat" action="">
	<div class="chat-result" id="chat-result">
		<input type="text" name="chat-user" id="chat-user" placeholder="Name">
		<input type="text" name="chat-message" id="chat-message"  placeholder="Message">
		<input type="submit" value="Send" >
	</div>
</form>

<? require_once CONF.'/chat.php'; ?>
