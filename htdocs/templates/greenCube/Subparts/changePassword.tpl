<div class="page_name">Moje nastavení / změna hesla</div>
<div id="changepwd">
    <div id="edit_information">{tag:inf /}</div>
    <form action="#" method="post" class="changepwd_form inblock"><label class="hidden">Id uživatele: <input type="text" readonly="readonly" name="id_user" value="{tag:id_user /}" hidden="hidden"  /></label>
        <div class="section">
            <div class="section_name">Změna hesla uživatele</div>
        <div class="formrow"><label for="password_old">Staré heslo: </label><input type="password" name="password_old"  /></div>
        <div class="formrow"><label for="password_new">Nové heslo: </label><input type="password" name="password_new"  /></div>
        <div class="formrow"><label for="password_new_2">Nové heslo znovu: </label><input type="password" name="password_new_2"  /></div>
        </div>
        <input type="submit" class="savebutton" name="changePassword" value="Uložit" id="button"  />
    </form>
</div>