{include cc_pm_max_messagecount}
<div class="PhorumLargeFont">{LANG->PrivateMessages} : {LANG->PMRead}</div>
<br />

<form action="{ACTION}" method="post">
{POST_VARS}
<input type="hidden" name="panel" value="pm" />
<input type="hidden" name="action" value="list" />
<input type="hidden" name="folder_id" value="{FOLDER_ID}" />
<input type="hidden" name="forum_id" value="{FORUM_ID}" />
<input type="hidden" name="pm_id" value="{MESSAGE->pm_message_id}" />
  
<div class="PhorumStdBlock">

<div class="PhorumReadBodySubject">{MESSAGE->subject}</div>
<div class="PhorumReadBodyHead">{LANG->From}: <strong><a href="{MESSAGE->from_profile_url}">{MESSAGE->from_username}</a></strong></div>
<div class="PhorumReadBodyHead">{LANG->To}: <strong><a href="{MESSAGE->to_profile_url}">{MESSAGE->to_username}</a></strong></div>
<div class="PhorumReadBodyHead">{LANG->Date}: {MESSAGE->date}</div>
<br />
<div class="PhorumReadBodyText">{MESSAGE->message}</div><br />
</div>

<div class="PhorumStdBlock" style="border-top:none">
{IF FOLDER_IS_INCOMING}
  {var MOVE_SUBMIT_NAME move_message}
  {include cc_pm_moveselect}
{/IF}
<input type="submit" name="close_message" class="PhorumSubmit" value="{LANG->PMCloseMessage}" />
{IF NOT MESSAGE->from_user_id USERINFO->user_id}
  <input type="submit" name="reply_message" class="PhorumSubmit" value="{LANG->PMReplyMessage}" />
{/IF}
<input type="submit" name="delete_message" class="PhorumSubmit" value="{LANG->Delete}" 
 onclick="return confirm('<?php print addslashes($PHORUM["DATA"]["LANG"]["AreYouSure"])?>')"/>
</div>
</form>
