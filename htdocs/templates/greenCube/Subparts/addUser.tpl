<div class="page_name">Administrace / nový uživatel</div>
<div id="edit_page">
    <div id="edit_information">{tag:inf /}</div>
    <form action="#" method="post" class="edit_form inblock">
        <div class="section">
            <div class="section_name">Nový uživatel</div>
            <div class="formrow"><label for="login_user">Přihlašovací jméno: </label><input name="login_user" value="{tag:login_user /}" type="text" maxlength="200" tabindex="1" /></div>
            <div class="formrow"><label for="password_user">Heslo: </label><input name="password_user" value="{tag:password_user /}" type="password" maxlength="200" tabindex="2" /></div>
            <div class="formrow"><label for="password_user_2">Heslo znova: </label><input name="password_user_2" value="{tag:password_user_2 /}" type="password" maxlength="200" tabindex="3" /><span class="hint"><- Hesla se musejí shodovat</span></div>
            <div class="formrow"><label for="first_name_user">Jméno: </label><input name="first_name_user" value="{tag:first_name_user /}" type="text" maxlength="200" tabindex="4" /></div>
            <div class="formrow"><label for="surname_user">Příjmení: </label><input name="surname_user" value="{tag:surname_user /}" type="text" maxlength="200" tabindex="5" /></div>
            <div class="formrow"><label for="email_user">Email: </label><input name="email_user" value="{tag:email_user /}" type="text" maxlength="200" tabindex="6" /><span class="hint"><- Email musí být ve tvaru xxx@yyy.zz</span></div>
            <div class="formrow"><label for="phone_user">Telefon: </label><input name="phone_user" value="{tag:phone_user /}" type="text" maxlength="200" tabindex="7" /></div>
            <div class="formrow"><label for="role_user">Role: </label><input name="role_user" value="{tag:role_user /}" type="text" maxlength="200" tabindex="8" /></div>
            <div class="formrow"><label for="theme_user">Vzhled: </label>
                <select name="theme_user">
                    {loop:theme_user}
                    <option value="{tag:theme_user[].theme_folder /}" {tag:theme_user[].sel /}>{tag:theme_user[].theme_folder /}</option>
                    {/loop:theme_user}
                </select>
            </div>
        </div>
        <input type="submit" class="savebutton" name="editRequest" value="Zpracovat" id="button"  />
    </form>
</div>