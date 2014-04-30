<div class="page_name">Administrace / zařazení uživatele</div>
{tag:message /}
<div>
    <form action="#" method="post">
        <div class="section">
            <div class="section_name">Úprava skupiny</div>
            <label>Jméno: <input name="first_name_user" value="{tag:first_name_user /}" type="text" /></label>
            <label>Příjmení: <input name="surname_user" value="{tag:surname_user /}" type="text" /></label>

            <div style="float:right">
                <input type="submit" class="filter" name="filter" value="Filtrovat" />
                <input type="submit" class="reset_filter" name="reset_filter" value="Reset filtru" />
            </div>
        </div>
    </form>
    <br />
</div>
<form action="#" method="post" name="setUsgr">
    <table id="showRooms" width="100%">
        <tr>
            <th>Jméno a příjmení</th>
            <th class="td10">Telefon</th>
            <th class="td15">Email</th>
            <th class="td10">Role</th>
            <th class="td10">Oprávnění skupiny</th>
        </tr>
        {loop:user}
        <tr class="{tag:user[].sel /}">
            <td><a title="{tag:user[].first_name_user /} {tag:user[].surname_user /}">{tag:user[].first_name_user /} {tag:user[].surname_user /}</a></td>
            <td>{tag:user[].phone_user /} </td>
            <td>{tag:user[].email_user /}</td>
            <td>{tag:user[].role_user /}</td>
            <td><select {tag:user[].disabled /} name="usgr_{tag:user[].id_user /}">{loop:user[].perm_list}
                    <option value="{tag:user[].perm_list[].value /}" {tag:user[].perm_list[].sel /}>{tag:user[].perm_list[].name /}</option>
                    {/loop:user[].perm_list}</select></td>
        </tr>
        {/loop:user}
    </table>
    <input name="setUsgr" class="savebutton" type="submit" value="Uložit" />
</form>
<div id="pagination">
    {tag:pagination /}
</div>