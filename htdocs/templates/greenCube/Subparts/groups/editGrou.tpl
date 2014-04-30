<div class="page_name">Administrace / úprava skupiny</div>
<form method="post" action="#" class="addGrou">
    <div class="section">
        <div class="section_name">Úprava skupiny</div>
        {tag:status /}
        <div class="formrow"><label for="name_grou">Název skupiny: </label><input name="name_grou" value="{tag:name_grou /}" /></div>
        <div class="formrow"><label for="abbrev_grou">Zkratka skupiny: </label><input name="abbrev_grou" value="{tag:abbrev_grou /}" /></div>   
        <div class="formrow"><label for="ident_grou">Označení skupiny: </label><input name="ident_grou" value="{tag:ident_grou /}" /></div>
        <label class="hidden" for="id_grou">ID skupiny: </label><input class="hidden" name="id_grou" value="{tag:id_grou /}" /></div>  
</div>
<input name="editGrou" class="savebutton" type="submit" value="Uložit" />
</form>      
