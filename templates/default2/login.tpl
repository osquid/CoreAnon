{IF ERROR}<div class="attention">{ERROR}</div>{/IF}
{IF OKMSG}<div class="information">{OKMSG}</div>{/IF}

{IF LOGGEDIN}
    {IF NOT FULLY_LOGGEDIN}
        <div class="information">{LANG->PeriodicLogin}</div>
    {/IF}
{/IF}

{IF NOT LOGGEDIN}
    <div class="information"><a href="{URL->REGISTER}">{LANG->NotRegistered}</a></div>
{/IF}

<div class="generic">
    <table border="0" cellspacing="0" class="form-table">
        <tr>
            <td width="50%" valign="top">

                <h4>{LANG->LoginTitle}</h4>
                <form action="{URL->ACTION}" method="post">
                    {POST_VARS}
                    <input type="hidden" name="forum_id" value="{LOGIN->forum_id}" />
                    <input type="hidden" name="redir" value="{LOGIN->redir}" />
                    {LANG->Username}:<br />
                    <input type="text" id="username" name="username" size="30" value="{LOGIN->username}" /><br />
                    <br />
                    {LANG->Password}:<br />
                    <input type="password" id="password" name="password" size="30" value="" /><br />
                    <br />
                    <input type="submit" value="{LANG->Submit}" />
                </form>

            </td>
            <td width="50%" valign="top">
                <h4>{LANG->LostPassword}</h4>
                <form action="{URL->ACTION}" method="post">
                    {POST_VARS}
                    <input type="hidden" name="lostpass" value="1" />
                    <input type="hidden" name="forum_id" value="{LOGIN->forum_id}" />
                    <input type="hidden" name="redir" value="{LOGIN->redir}" />
                    {LANG->LostPassInfo}<br />
                    <br />
                    <input type="text" name="lostpass" size="30" value="" /><br />
                    <br />
                    <input type="submit" value="{LANG->Submit}" />
                </form>            
            </td>
        </tr>
    </table>
</div>

