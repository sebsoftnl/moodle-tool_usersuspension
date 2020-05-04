
SEBSOFT USERSUSPENSION PLUGIN

The Sebsoft User Suspension Plugin offers you the possibility to automate the process
of suspending users and/or removing user accounts (using moodle's default methods to delete users).

Suspending users is provided in three different ways:
- Using a folder
- Using a file upload
- Manually through a status table

Deleting users is, if configured to be enabled, provided by comparing the date of suspending to
the configured period before removal. Once the configured interval has past and the user is deemed
suspended for the configured period, the useraccount will be removed using moodle's standard methods.

Furthermore, there's extensive settings that can be configured to turn every feature on or off,
as well as disabling the whole tool's features.

This tool also provides a way of excluding users to be suspended or processed in case of automated
processing. There are two methods provided at the moment: single user exclusion and cohort exclusion.
During all three different ways of automated suspending / deleting users, only users that have not
been configured to be excluded, either by cohort or by single user, will NOT be processed.

Important note: site administrators and the default guest account are completely left out of this tool.
For safety measures, it is not recommended to put any form of automisation on suspending or deleting
administrator accounts or the default guest account, hence the decision to exclude them in any processing.

INSTALLATION

- Copy the usersuspension folder to your admin/tool directory
- Configure your tool
- IMPORTANT WARNING: you should disable the whole tool's feature at first install!
  Configure all your exclusions before turning the tool on. Failing to do so
  MAY result in immediate suspension or removal of inactive users!
- We're ready to run!
