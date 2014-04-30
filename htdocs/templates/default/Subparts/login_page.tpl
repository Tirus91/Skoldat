<div id="login_page">
    <div id="login_information"><h2>Přihlášení</h2></div>
    <form action="{tag:homelink /}/User/Login" method="post" class="login_form">
        <label>Login: <input name="login_user" value="{tag:login_user /}" type="text" maxlength="200" tabindex="1" /></label>
        <label>Heslo: <input name="password_user" type="password" maxlength="250" tabindex="2" /></label>
        <input type="submit" name="loginRequest" value="Přihlásit se" id="button"  />
    </form>

</div>