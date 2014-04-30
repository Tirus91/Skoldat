<div class="page_name">Moje nastavení / informace o uživateli</div>
<div id="edit_page">
    <div id="edit_information">{tag:inf /}</div>
    
    <form action="{tag:homelink /}/User/myInfo" method="post" class="edit_form inblock">
        <div class="section">
            <div class="section_name">Informace o uživateli</div>
            <div class="formrow">
        <label for="login_user">Přihlašovací jméno: </label><input name="login_user" value="{tag:login_user /}" type="text" readonly="readonly" maxlength="200" tabindex="1" />
        </div>
        <div class="formrow"><label for="first_name_user">Jméno: </label><input name="first_name_user" value="{tag:first_name_user /}" type="text" maxlength="200" tabindex="1" /></div>
        <div class="formrow"><label for="surname_user">Příjmení: </label><input name="surname_user" value="{tag:surname_user /}" type="text" maxlength="200" tabindex="2" /></div>
        <div class="formrow"><label for="email_user">Email: </label><input name="email_user" value="{tag:email_user /}" type="text" maxlength="200" tabindex="3" /></div>
        <div class="formrow"><label for="phone_user">Telefon: </label><input name="phone_user" value="{tag:phone_user /}" type="text" maxlength="200" tabindex="4" /></div>
        <div class="formrow"><label for="role_user">Role: </label><input name="role_user" value="{tag:role_user /}" type="text" maxlength="200" tabindex="4" /></div>
        <input type="submit" class="savebutton" name="editRequest" value="Zpracovat" id="button"  />
        </div>
    </form>

</div>