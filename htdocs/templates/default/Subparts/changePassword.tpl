<div id="changepwd">
    <div id="edit_information">{tag:inf /}</div>
    <form action="#" method="post" class="changepwd_form"><label class="hidden">Id uživatele: <input type="text" readonly="readonly" name="id_user" value="{tag:id_user /}" hidden="hidden"  /></label>
        <label>Staré heslo: <input type="password" name="password_old"  /></label>
        <label>Nové heslo: <input type="password" name="password_new"  /></label>
        <label>Nové heslo znovu: <input type="password" name="password_new_2"  /></label>
        <input type="submit" class="savebutton" name="changePassword" value="Uložit" id="button"  />
    </form>
</div>