<div class="page_name">Administrace / základní nastavení</div>
<div id="main_settings">
    <form action="#" method="POST" class="inblock"><div class="section">
            <div class="section_name">Základní nastavení</div>
            <div class="formrow"><label for="school_name_syst">Jméno školy: </label><input value="{tag:school_name_syst /}" name="school_name_syst" /></div>
            <div class="formrow"><label for="school_address_syst">Adresa školy: </label><input value="{tag:school_address_syst /}" name="school_address_syst" /></div>
            <div class="formrow"><label for="school_contact_user_syst">Jméno kontaktní osoby: </label><input value="{tag:school_contact_user_syst /}" name="school_contact_user_syst" /></div>
            <div class="formrow"><label for="row_show_syst">Počet zobrazovaných řádků: </label>
                <select name="row_show_syst" >
                    <option value="10" {tag:row_show_syst_10 /}>10</option>
                    <option value="15" {tag:row_show_syst_15 /}>15</option>
                    <option value="20" {tag:row_show_syst_20 /}>20</option>
                    <option value="25" {tag:row_show_syst_25 /}>25</option>
                    <option value="30" {tag:row_show_syst_30 /}>30</option>
                    <option value="35" {tag:row_show_syst_35 /}>35</option>
                </select></div>
        </div>
        <div class="section">
            <div class="section_name">Nastavení emailů</div>
            <div class="formrow"><label for="type_send_mail_syst">Typ odesílání: </label><select name="type_send_mail_syst" id="smtp_changer">
                    <option value="1" {tag:type_send_mail_syst_1 /}>Výchozí</option>
                    <option value="2" {tag:type_send_mail_syst_2 /}>Vlastní (SMTP)</option>
                </select></div>
            <div class="formrow"><label for="mail_format_syst">Formát obsahu: </label><select name="mail_format_syst">
                    <option value="html" {tag:mail_format_syst_html /}>HTML</option>
                    <option value="text" {tag:mail_format_syst_text /}>text</option>
                </select></div>
            <div class="formrow"><label for="smtp_auth_email_syst">Email pro SMTP: </label><input value="{tag:smtp_auth_email_syst /}" name="smtp_auth_email_syst" /></div>
            <div class="formrow"><label for="mail_sender_name_syst">Jméno odesílatele: </label><input value="{tag:mail_sender_name_syst /}" name="mail_sender_name_syst" /></div>
            <div class="formrow"><label for="mail_wordwrap_syst">Počet znaků na řádek: </label><input value="{tag:mail_wordwrap_syst /}" name="mail_wordwrap_syst" /></div>
            <div class="only_smtp">
                <div class="formrow"><label for="smtp_auth_pwd_syst">Heslo pro SMTP: </label><input value="{tag:smtp_auth_pwd_syst /}" type="password" name="smtp_auth_pwd_syst" /> <span class="hint"><- musí být vyplněno zda je zadáno SMTP ověření</span></div>
                <div class="formrow"><label for="smtp_port_syst">Port pro SMTP: </label><input value="{tag:smtp_port_syst /}" name="smtp_port_syst" /></div>
                <div class="formrow"><label for="smtp_secure_syst">Typ zabezpečení: </label><select name="smtp_secure_syst">
                        <option value="tls" {tag:smtp_secure_syst_tls /}>TLS</option>
                        <option value="ssl" {tag:smtp_secure_syst_ssl /}>SSL</option>
                        <option value="" {tag:smtp_secure_syst_none /}>Žádné</option>
                    </select></div>
                <div class="formrow"><label for="smtp_auth_syst">SMTP ověření: </label><select name="smtp_auth_syst">
                        <option value="1" {tag:smtp_auth_syst_1 /}>Ano</option>
                        <option value="0" {tag:smtp_auth_syst_0 /}>Ne</option>
                    </select></div>
                <div class="formrow"><label for="smtp_server_syst">SMTP server: </label><input value="{tag:smtp_server_syst /}" name="smtp_server_syst" /></div>
            </div>
        </div>

        <input type="submit" class="savebutton" name="saveSetting" value="Uložit nastavení" id="button"  />
    </form>
</div>