<div id="edit_page">
    <div id="edit_information">{tag:inf /}</div>
    <form action="#" method="post" class="edit_form">
        <label>Přihlašovací jméno: <input name="login_user" value="{tag:login_user /}" type="text" readonly="readonly" maxlength="200" tabindex="1" /></label>
        <label>Jméno: <input name="first_name_user" value="{tag:first_name_user /}" type="text" maxlength="200" tabindex="1" /></label>
        <label>Příjmení: <input name="surname_user" value="{tag:surname_user /}" type="text" maxlength="200" tabindex="2" /></label>
        <label>Email: <input name="email_user" value="{tag:email_user /}" type="text" maxlength="200" tabindex="3" /></label>
        <label>Telefon: <input name="phone_user" value="{tag:phone_user /}" type="text" maxlength="200" tabindex="4" /></label>
        <label>Role: <input name="role_user" value="{tag:role_user /}" type="text" maxlength="200" tabindex="4" /></label>
        <input type="submit" class="savebutton" name="editRequest" value="Zpracovat" id="button"  />
    </form>
    <span>Oprávnění bylo přesunuto. Pro nastavení klikněte <a href="/Admin/showGrou"> zde</a></span>
</div>