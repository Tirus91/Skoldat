<div id="main_settings">
    <form action="#" method="POST">
        <fieldset>
            <legend>Základní nastavení</legend>
            <label>Jméno školy: <input value="{tag:school_name_syst /}" name="school_name_syst" /></label>
            <label>Adresa školy: <input value="{tag:school_address_syst /}" name="school_address_syst" /></label>
            <label>Jméno kontaktní osoby: <input value="{tag:school_contact_user_syst /}" name="school_contact_user_syst" /></label>
            <label>Počet zobrazovaných řádků: <select name="row_show_syst" >
                    <option value="10" {tag:row_show_syst_10 /}>10</option>
                    <option value="15" {tag:row_show_syst_15 /}>15</option>
                    <option value="20" {tag:row_show_syst_20 /}>20</option>
                    <option value="25" {tag:row_show_syst_25 /}>25</option>
                    <option value="30" {tag:row_show_syst_30 /}>30</option>
                    <option value="35" {tag:row_show_syst_35 /}>35</option>
                </select></label>
        </fieldset>
        <fieldset>
            <legend>Nastavení emailů</legend>
            <label>Typ odesílání: <select name="type_send_mail_syst" id="smtp_changer">
                    <option value="1" {tag:type_send_mail_syst_1 /}>Výchozí</option>
                    <option value="2" {tag:type_send_mail_syst_2 /}>Vlastní (SMTP)</option>
                </select></label>
            <label>Formát obsahu: <select name="mail_format_syst">
                    <option value="html" {tag:mail_format_syst_html /}>HTML</option>
                    <option value="text" {tag:mail_format_syst_text /}>text</option>
                </select></label>
            <label>Email pro SMTP: <input value="{tag:smtp_auth_email_syst /}" name="smtp_auth_email_syst" /></label>
            <label>Jméno odesílatele: <input value="{tag:mail_sender_name_syst /}" name="mail_sender_name_syst" /></label>
            <label>Počet znaků na řádek: <input value="{tag:mail_wordwrap_syst /}" name="mail_wordwrap_syst" /></label>
            <p class="only_smtp">
                <label>Heslo pro SMTP: <input value="{tag:smtp_auth_pwd_syst /}" type="password" name="smtp_auth_pwd_syst" /></label>
                <label>Port pro SMTP: <input value="{tag:smtp_port_syst /}" name="smtp_port_syst" /></label>
                <label>Typ zabezpečení: <select name="smtp_secure_syst">
                        <option value="tls" {tag:smtp_secure_syst_tls /}>TLS</option>
                        <option value="ssl" {tag:smtp_secure_syst_ssl /}>SSL</option>
                        <option value="" {tag:smtp_secure_syst_none /}>Žádné</option>
                    </select></label>
                <label>SMTP ověření: <select name="smtp_auth_syst">
                        <option value="1" {tag:smtp_auth_syst_1 /}>Ano</option>
                        <option value="0" {tag:smtp_auth_syst_0 /}>Ne</option>
                    </select></label>
                <label>SMTP server: <input value="{tag:smtp_server_syst /}" name="smtp_server_syst" /></label>
            </p>
        </fieldset>


        <input type="submit" class="savebutton" name="saveSetting" value="Uložit nastavení" id="button"  />
    </form>
</div>