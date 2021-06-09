Version 3.5.7 (build 2020121502)
* Added download option + filtering options for status table.
* Pretty substantial code overhaul to use NAMED parameters (due to added filters).
* Merge changes from Catalyst IT's Totara specific branch.
* Added OPTION to send warnings instead of relying on the smartdetect option.
* Added more informative messages.
* Code overhaul because codechecker keeps whining about visibility declaration (pffft).

----------

Version 3.5.6 (build 2020121501)
* Added tab and script to test configuration of unattended suspension/unsuspension ("from folder" option).

----------

Version 3.5.5 (build 2020121500)
* Replaced "self::" with "static::" in \tool_usersuspension\util.
* Fixed wrong language string.
* Removed notifications from every page and moved to it's own tab (due to it being annoying)

----------

Version 3.5.4 (build 2018050304)
* Added delimiter setting.
* Added "unsuspend from folder".
* Added new mode selection to upload interface (to distinguish between suspending and unsuspending).
* Added sample CSV (global settings as well as upload form).
* Added option to enable/disable UNsuspending users from uploaded file in folder.
* Added option to configure filename for file used for UNsuspending users from uploaded file in folder.

----------

Version 3.5.3 (build 2018050303)
* Removed double output of "suspendin".
* Fixed sorting by "suspendin"

----------

Version 3.5.2 (build 2018050302)
* Updated query for "users to suspend" count.
* Updated query for "users to delete" count.
* Updated query for "users to delete" overview.
* Updated query for "users to delete" overview.
* The aforementioned now also look in the past instead of only those "to come".
* Fixed "Exception - Argument 4 passed to table_sql::set_sql() must be of the type array, null given"

----------

Version 3.5.1 (build 2018050301)
* Updated privacy provider.

----------

Version 3.5.0 (build 2018050300)
* Added privacy provider
* Validated functionality for Moodle 3.5 onwards
* Minimum required Moodle version: 3.5

----------

Version 3.3.0 (build 2017092500)
* Fixed deprecated pix_url references (replaced y image_url)
* Validated functionality for Moodle 3.3 onwards
* Minimum required Moodle version: 3.3

----------

Version 3.0.0 (build 2017050100)
* Code overhaul to comply to Moodle standards
* Validated functionality for Moodle 3.0 onwards
* Minimum required Moodle version: 3.0
