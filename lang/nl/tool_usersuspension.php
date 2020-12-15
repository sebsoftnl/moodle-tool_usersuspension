<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language file for tool_usersuspension, NL
 *
 * File         tool_usersuspension.php
 * Encoding     UTF-8
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'Gebruikersschorsing';

$string['promo'] = 'Gebruikersschorsing plugin voor Moodle';
$string['promodesc'] = 'Deze plugin is ontwikkeld door Sebsoft Managed Hosting & Software Development
    (<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://www.sebsoft.nl</a>).<br /><br />
    {$a}<br /><br />';
$string['link:upload'] = 'Upload schorsingsbestand';
$string['link:viewstatus'] = 'Statuslijst tonen';
$string['link:exclude:overview'] = 'Overzicht uitsluitingen';
$string['link:log:overview'] = 'Statuswijzigingen inzien';
$string['link:currentstatus:overview'] = 'Huidige statussen inzien';

$string['suspensionsettings'] = 'Instellingen Gebruikersschorsing';
$string['suspensionsettingsdesc'] = '';
$string['setting:enabled'] = 'Inschakelen';
$string['setting:desc:enabled'] = 'Schakelt plugin gebruikersschorsing aan of uit';
$string['setting:enablecleanlogs'] = 'Inschakelen logopschoning';
$string['setting:desc:enablecleanlogs'] = 'Schakelt automatisch opschonen van historische logs aan of uit.';
$string['setting:cleanlogsafter'] = 'Frequentie logopschoning';
$string['setting:desc:cleanlogsafter'] = 'Configureer de frequentie waarop historische logs worden opgeschoond. Alle logs ouder dan de ingegeven waarde zullen fysiek verwijderd worden.';
$string['config:cleanlogs:disabled'] = 'Automatisch opschonen van logs is uitgeschakeld in de globale configuratie';
$string['suspensionsettingsfolder'] = 'Schorsen vanuit folder';
$string['suspensionsettingsfolderdesc'] = 'Configureer de \'schorsen vanuit folder\' instellingen.<br/>
Met behulp van deze instellingen kun je gebruikers automatisch laten schorsen door het uploaden van een CSV bestand naar een
willekeurige locatie op de server (bijvoorbeeld een dedicated FTP folder). Deze zal conform onderstaande instellingen
worden verwerkt. Let op: het CSV bestand zal na verwerking worden verwijderd!';
$string['setting:enablefromfolder'] = 'Automatisch schorsen vanuit opgeslagen CSV bestand inschakelen';
$string['setting:desc:enablefromfolder'] = 'Schakelt verwerken van schorsingsbestand vanuit upload folder voor plugin gebruikersschorsing aan of uit';
$string['setting:uploadfolder'] = 'Upload folder';
$string['setting:desc:uploadfolder'] = 'Stel folder in waar bestanden voor gebruikersschorsing geuploaded wordt via b.v. FTP';
$string['setting:uploadfilename'] = 'Upload bestandsnaam (schorsen)';
$string['setting:desc:uploadfilename'] = 'Stel bestandsnaam in voor gebruikersschorsing';
$string['setting:uploaddetect_interval'] = 'Upload folder verwerkingsinterval';
$string['setting:desc:uploaddetect_interval'] = 'Stel interval in hoe vaak de upload folder wordt gecontroleerd op bestanden';
$string['suspensionsettingsupload'] = 'Schorsen door bestandsupload';
$string['suspensionsettingsuploaddesc'] = 'Configureer de \'schorsen door bestandsupload\' instellingen';
$string['setting:enablefromupload'] = 'Bestandsupload inschakelen';
$string['setting:desc:enablefromupload'] = 'Schakelt automatische verwerking van bestanden middels bestandsupload aan of uit';
$string['suspensionsettingssmartdetect'] = 'Slimme detectie';
$string['suspensionsettingssmartdetectdesc'] = 'Configureer instellingen voor slimme detectie.<br/>
Slimme detectie betekent in feite dat gebruikersaccounts die als inactief worden gezien volgens onderstaande instellingen,
automatisch zullen worden geschorst. Enkel draaiend op een geconfigureerd interval, zal \'slimme detectie\' achterhalen of een gebruikersaccount
actief is volgens de configuratie van de instelling \'Inactivity suspension interval\', en schorst alle gebruikersaccounts die als inactief worden gemarkeerd';
$string['setting:enablesmartdetect'] = 'Slimme detectie inschakelen';
$string['setting:desc:enablesmartdetect'] = 'Schakelt de slimme detectie aan of uit voor deze plugin..';
$string['setting:smartdetect_interval'] = 'Interval voor slimme detectie';
$string['setting:desc:smartdetect_interval'] = 'Stel interval in hoe vaak slimme detectie draait';
$string['setting:smartdetect_suspendafter'] = 'Inactivity suspension interval';
$string['setting:desc:smartdetect_suspendafter'] = 'Stel interval in waarop gebruikers als inactief worden aangemerkt en automatisch worden geschorst';
$string['suspensionsettingscleanup'] = 'Opschonen';
$string['suspensionsettingscleanupdesc'] = 'Configureer opschoningsinstellingen.<br/>
Het opschoningsproces dient ter verdere automatische opschoning van gebruikersaccounts, en betekent dat geschorste gebruikersaccounts zullen worden verwijderd
indien deze optie is ingeschakeld. Wanneer gebruikersaccounts automatisch mogen worden opgeschoond na zekere periode zou je deze optie moeten inschakelen.
Wanneer geautomatiseerde verwijdering van gebruikers absoluut niet gewenst is, schakel deze optie dan uit.';
$string['setting:enablecleanup'] = 'Opschonen inschakelen';
$string['setting:desc:enablecleanup'] = 'Schakel opschoning van gebruikers in';
$string['setting:cleanup_interval'] = 'Opschoningsinterval';
$string['setting:desc:cleanup_interval'] = 'Stel interval in hoe vaak opschonen wordt verwerkt';
$string['setting:cleanup_deleteafter'] = 'Interval voor verwijderen';
$string['setting:desc:cleanup_deleteafter'] = 'Stel het interval in dat detecteert wanneer gebruikers verwijderd worden nadat ze zijn geschorst';
$string['setting:sendsuspendemail'] = 'Verzend e-mail bij schorsen?';
$string['setting:desc:sendsuspendemail'] = 'Verzend een e-mail die de gebruiker informeert dat het account geschorst is?';
$string['setting:senddeleteemail'] = 'Verzend e-mail bij verwijdering?';
$string['setting:desc:senddeleteemail'] = 'Verzend een e-mail die de gebruiker informeert dat het account verwijderd is?';
$string['csv:delimiter'] = 'Delimiter';
$string['csv:enclosure'] = 'Enclosure';
$string['csv:upload:continue'] = 'Doorgaan';

$string['page:view:statuslist.php:introduction:status'] = '<p>Dit overzicht toont de actief gemonitoorde gebruikers.<br/>
Actief gemonitoorde gebruikers zijn gebruikers die daadwerkelijk worden gemonitoord (dit betekent dat ze niet zijn geconfigureerd om uitgesloten te zijn voor verwerking).<br/>
Dit overzicht wijkt dus in die zin af van het standaard gebruikersbeheer overzicht dat het <i>geen</igebruikers toont die uitgesloten zijn van verwerking
door de mogelijkheden die dit blok bied om gebruikers en volledige cohorten uit te sluiten.</p>';
$string['page:view:statuslist.php:introduction:delete'] = '<p>Dit overzicht toont de gebruikeraccounts die zullen worden verwijderd binnen
de voor dit blok geconfigureerde periode.</p>';
$string['page:view:statuslist.php:introduction:suspended'] = '<p>Dit overzicht toont de gebruikeraccounts die zijn geschorst.</p>';
$string['page:view:statuslist.php:introduction:tosuspend'] = '<p>Dit overzicht toont de gebruikeraccounts die zullen worden geschorst binnen
de voor dit blok geconfigureerde periode</p>';
$string['page:view:log.php:introduction'] = 'De tabel hieronder toont het historisch overzicht van statussen die aan accounts zijn gekoppeld als
resultaat van (automatische) verwerking binnen deze plugin. Het toont, afhankelijk van de instellingen, de schorsingsstatus of verwijderingsstatus van
de moodle accounts en het moment waarop deze statussen zijn toegewezen.';
$string['page:view:exclude.php:introduction'] = '<p>Deze pagina toont de geconfigureerde uitsluitingen.<br/>
Uitsluitingen zijn ofwel sitegorpen ofwel gebruikers die volledig zijn uitgesloten van automatische verwerking door deze plugin.<br/>
Wanneer een sitegroep is uitgesloten, betekent dit dat geen enkele gebruiker uit de sitegroep wordt verwerkt.
Gebruik de opties op deze pagina om uitsluitingen te configureren.</p>';
$string['config:tool:disabled'] = 'Gebruikersschorsing plugin is uitgezet via de globale blokinstellingen';
$string['config:smartdetect:disabled'] = 'Gebruikersschorsing optie \'slimme detectie\' is uitgezet via de globale blokinstellingen';
$string['config:fromfolder:disabled'] = 'Gebruikersschorsing optie \'schorsen door bestandsupload\' is uitgezet via de globale blokinstellingen';
$string['config:cleanup:disabled'] = 'Gebruikersschorsing optie \'opschoning van gebruikers\' is uitgezet via de globale blokinstellingen';
$string['err:statustable:set_sql'] = 'set_sql() is uitgeschakeld. Deze tabel definieert zijn eigen queries';
$string['notify:load-exclude-list'] = 'Gebruikersuitsluitingen laden';
$string['notify:load-file'] = 'Bestand \'{$a}\' openen';
$string['notify:load-file-fail'] = 'Kon bestand \'{$a}\' niet openen om te lezen';
$string['notify:suspend-excluded-user'] = 'gebruiker: {$a->username} (id={$a->id}) is gevonden in uitsluitingslijst: niet schorsen';
$string['notify:suspend-user'] = 'gebruiker schorsen: {$a->username} (id={$a->id})';
$string['notify:unknown-suspend-type'] = 'Onbekend schorstype \'{$a}\'; kan gebruiker niet schorsen';
$string['action:delete-exclusion'] = 'Verwijder item uit uitsluitingslijst';
$string['action:confirm-delete-exclusion'] = 'Weet je zeker dat je dit item wilt verwijderen uit de uitsluitingslijst?';
$string['info:no-exclusion-cohorts'] = 'Geen gebruikersgroepen meer gevinden voor uitsluiting. Alle sitegroepen zijn reeds uitgesloten';
$string['button:continue'] = 'Doorgaan';
$string['action:exclude:add:cohort'] = 'Voeg sitegroep uitsluiting toe';
$string['action:exclude:add:user'] = 'Voeg gebruikersuitsluiting toe';
$string['label:users:excluded'] = 'Uitgesloten gebruikers';
$string['label:users:potential'] = 'Potentiele gebruikers';
$string['status:suspended'] = 'geschorst';
$string['status:unsuspended'] = 'ontschorst';
$string['status:deleted'] = 'verwijderd';
$string['table:status:status'] = 'Actief gemonitoorde gebruikers';
$string['table:status:suspended'] = 'Geschorste gebruikers';
$string['table:status:tosuspend'] = 'Te schorsen gebruikers';
$string['table:status:delete'] = 'Te verwijderen gebruikers';
$string['excludeuser'] = 'Uit te sluiten gebruiker';

$string['email:user:suspend:subject'] = 'Je account is geschorst';
$string['email:user:suspend:auto:body'] = '<p>Beste {$a->name}</p>
<p>Je account is geschorst nadat je {$a->timeinactive} inactief bent geweest</p>
<p>Als je denkt dat dit ongewenst is of je wilt de schorsing ongedaan laten maken,
neem dan contact op met {$a->contact}</p>
<p>Met vriendelijke groet,<br/>{$a->signature}</p>';
$string['email:user:suspend:manual:body'] = '<p>Beste {$a->name}</p>
<p>Je account is geschorst.</p>
<p>Als je denkt dat dit ongewenst is of je wilt de schorsing ongedaan laten maken,
neem dan contact op met {$a->contact}</p>
<p>Regards<br/>{$a->signature}</p>';
$string['email:user:unsuspend:subject'] = 'Je account is opnieuw activeerd';
$string['email:user:unsuspend:body'] = '<p>Beste {$a->name}</p>
<p>Je account is opnieuw geactiveerd.</p>
<p>Als je denkt dat dit ongewenst is of je wilt de activering ongedaan laten maken,
neem dan contact op met {$a->contact}</p>
<p>Met vriendelijke groet,<br/>{$a->signature}</p>';
$string['email:user:delete:subject'] = 'Je account is verwijderd';
$string['email:user:delete:body'] = '<p>Beste {$a->name}</p>
<p>Je account is verwijderd nadat je {$a->timesuspended} geschorst bent geweest</p>
<p>Met vriendelijke groet,<br/>{$a->signature}</p>';
$string['form:static:uploadfile:desc'] = 'Upload hier je schorsingsbestand<br/>
De geuploade CSV kan als volgt geconfigureerd worden:<br/>
<ol>
<li>\'simpel\' bestand met ENKEL e-mail adressen, 1 per regel</li>
<li>\'slim\' bestand met 2 kolommen, welke type en waarde voorstellen.<br/>
Mogelijke waarden voor het type zijn
<ul><li>email: tweede kolom impliceert e-mail adres van de gebruikersaccount</li>
<li>idnumber: tweede kolom impliceert idnumber van de gebruikersaccount</li>
<li>username: tweede kolom impliceert gebruikersnaam van de gebruikersaccount</li>
</ul></ol>';
$string['msg:exclusion:cohort:none-selected'] = 'Geen sitegroepen geselecteerd voor uitsluiting';
$string['msg:exclusion:records:user:deleted'] = 'Uitsluitingen voor gebruikers succesvol verwijderd';
$string['msg:exclusion:record:user:inserted'] = 'Uitsluiting voor gebruiker \'{$a->fullname}\' succesvol toegevoegd';
$string['msg:exclusion:record:user:deleted'] = 'Uitsluiting voor gebruiker \'{$a->fullname}\' succesvol verwijderd';
$string['msg:exclusion:records:cohort:deleted'] = 'Exclusion entries voor sitegroepen succesvol verwijderd';
$string['msg:exclusion:record:cohort:inserted'] = 'Uitsluitingen voor sitegroep \'{$a->name}\' succesvol toegevoegd';
$string['msg:exclusion:records:deleted'] = 'Uitsluitingen succesvol verwijderd';
$string['msg:exclusion:record:inserted'] = 'Uitsluiting succesvol toegevoegd';
$string['msg:exclusion:record:exists'] = 'Uitsluiting bestaat al (geen gegevens toegevoegd)';
$string['msg:file:upload:fail'] = 'Uploaded bestand kon niet succesvol worden opgeslagen. Verwerking onderbroken.';
$string['msg:user:suspend:success'] = 'Gebruiker \'{$a->username}\' succesvol geschorst';
$string['msg:user:suspend:failed'] = 'Gebruiker \'{$a->username}\' kon niet worden geschorst';
$string['msg:user:unsuspend:success'] = 'Schorsing voor gebruiker \'{$a->username}\' succesvol opgeheven';
$string['msg:user:unsuspend:failed'] = 'Schorsing voor gebruiker \'{$a->username}\' kon niet worden opgeheven';
$string['msg:user:not-found'] = 'gebruiker niet gevonden';
$string['msg:file-not-readable'] = 'Geupload bestand \'{$a}\' is niet leesbaar';
$string['msg:file-not-writeable'] = 'Geupload bestand \'{$a}\' is niet schrijfbaar or verwijderbaar';
$string['button:backtocourse'] = 'Terug naar cursus';
$string['button:backtouploadform'] = 'Terug naar upload formulier';
$string['button:backtoexclusions'] = 'Back to uitsluitingsoverzicht';
$string['table:exclusions'] = 'Uitsluitingen';
$string['table:logs'] = 'Logs';
$string['table:log:all'] = 'Historie schorsingslog';
$string['table:log:latest'] = 'Laatste schorsing logs';
$string['task:mark'] = 'Gebruikersschorsing taak: geautomatiseerde schorsing van gebruikers';
$string['task:fromfolder'] = 'Gebruikersschorsing taak: geautomatiseerde schorsing vanuit geupload bestand';
$string['task:delete'] = 'Gebruikersschorsing taak: geautomatiseerd verwijderen van geschorste gebruikers';
$string['task:logclean'] = 'Opschonen logs voor gebruikersschorsing';
$string['thead:type'] = 'Type';
$string['thead:name'] = 'Naam';
$string['thead:timecreated'] = 'Aangemaakt';
$string['thead:action'] = 'Actie(s)';
$string['thead:userid'] = 'Gebruikers ID';
$string['thead:status'] = 'Status';
$string['thead:mailsent'] = 'E-mail verzonden';
$string['thead:mailedto'] = 'E-mail veronden naar';
$string['thead:username'] = 'Gebruikersnaam';
$string['thead:lastlogin'] = 'Laatste login';
$string['thead:timemodified'] = 'Gewijzigd';

$string['privacy:metadata:tool_usersuspension:type'] = 'Suspension exclusion type (always \'user\').';
$string['privacy:metadata:tool_usersuspension:userid'] = 'De primaire database sleutel van de Moodle gebruiker voor wie herstel is gedaan.';
$string['privacy:metadata:tool_usersuspension:status'] = 'Schorsingsstatus';
$string['privacy:metadata:tool_usersuspension:mailsent'] = 'Of een email is verzonden naar de gebruiker';
$string['privacy:metadata:tool_usersuspension:mailedto'] = 'E-mailadres van de herstelde gebruiker';
$string['privacy:metadata:tool_usersuspension:timecreated'] = 'Tijdstip waarop de gegevens zijn aangemaakt.';
$string['privacy:metadata:tool_usersuspension_excl'] = 'De usersuspension uitzonderingen slaan gegevens op over gebruikers die uitgesloten zijn van automatische schorsing';
$string['privacy:metadata:tool_usersuspension_status'] = 'De usersuspension status slaat gegevens op over de schorsingsstatus van gebruikers';
$string['privacy:metadata:tool_usersuspension_log'] = 'De usersuspension log slaat historische/log gegevens op over de schorsingsstatus van gebruikers';

$string['csvdelimiter'] = 'CSV scheidingsteken';
$string['csvencoding'] = 'CSV encoding';
$string['task:unsuspendfromfolder'] = 'Usersuspension taak: automatisch gebruikers activeren via geupload bestand';
$string['suspendmode'] = 'Verwerkingsmodus';
$string['suspend'] = 'Schorsen';
$string['unsuspend'] = 'Ontschorsen (activeren)';
$string['download-sample-csv'] = 'Download voorbeeld CSV bestand';
$string['config:unsuspendfromfolder:disabled'] = 'Gebruikersschorsing optie \'ontschorsen door bestandsupload\' is uitgezet via de globale blokinstellingen';
$string['setting:enableunsuspendfromfolder'] = 'Automatisch ontschorsen vanuit opgeslagen CSV bestand inschakelen';
$string['setting:desc:enableunsuspendfromfolder'] = 'Schakelt verwerken van ontschorsingsbestand vanuit upload folder voor plugin gebruikersontschorsing aan of uit';
$string['setting:unsuspenduploadfilename'] = 'Upload bestandsnaam (ontschorsen)';
$string['setting:desc:unsuspenduploadfilename'] = 'Stel bestandsnaam in voor gebruikersontschorsing';
$string['page:view:notifications.php:introduction'] = 'Dit tabblad toont mogelijke problemen met de configuratie van deze tool.';
$string['tab:notifications'] = 'Instellingencontrole';
$string['notifications:allok'] = 'Je configuratie lijkt volledig op orde, er konden geen problemen worden ontdekt voor wat betreft de globale instellingen van deze tool.';
