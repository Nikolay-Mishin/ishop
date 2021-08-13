<?php

$consts = getConsts();
$chat = "<script>
	const Chat = $consts;
	Object.freeze(Chat); // замораживает объект
</script>";

return $chat;
