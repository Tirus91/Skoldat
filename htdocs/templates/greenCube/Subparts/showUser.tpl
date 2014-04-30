<div class="page_name">Administrace / přehled uživatelů</div>
{tag:message /}
<div>
    <form action="{tag:homelink /}/Admin/showUser" method="post">
        <div class="section">
        <div class="section_name">Vyhledávání</div>
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
<table id="showRooms" width="100%">
    <tr>
        <th>Jméno a příjmení</th>
        <th class="td10">Telefon</th>
        <th class="td10">Email</th>
        <th class="td10">Role</th>

        <th class="td5">&nbsp;</th>
    </tr>
    {loop:user}
    <tr class="{tag:user[].sel /}">
        <td><a title="{tag:user[].first_name_user /} {tag:user[].surname_user /}">{tag:user[].first_name_user /} {tag:user[].surname_user /}</a></td>
        <td>{tag:user[].phone_user /} </td>
        <td>{tag:user[].email_user /}</td>
        <td>{tag:user[].role_user /}</td>
        <td>{tag:user[].edit_user /}&nbsp;{tag:user[].delete_user /}</td>
    </tr>
    {/loop:user}
</table>

<form action="{tag:homelink /}/Admin/addUser" method="post">

    {if:addItembutton}
        <input class="addbutton" name="addItem" type="submit" value="Nový uživ." />
    {else:addItembutton}
        &nbsp;
    {/if:addItembutton}
</form>
<div id="pagination">
    {tag:pagination /}
</div>