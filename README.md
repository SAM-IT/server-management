This repository contains server management scripts.

# Google Authenticator over SSH
When using Google Authenticator for 2 factor (public key and token) SSH authentication, a token is required for every login.
Since you have configured SSH to force both authentications, pubkey and keyboard-interactive, the PAM stack is always called.
If you prefer to only enter your token every 24 hours this library can help (`/etc/pam.d/sshd`):

````
# Before running pam_access remove outdated entries from the configuration file.
auth optional pam_exec.so log=/var/log/pam_exec.log debug /root/server-management/manage pam/filter

# Check if a user / IP combination is allowed access, note this is secure for SSH since you have configured it to always require public key authentication.
auth sufficient pam_access.so accessfile=/etc/security/access-known-ips.conf

### PICK ONE OF THESE

#1 If pam_access doesn't allow access, try google authenticator. If a user hasn't configured his authenticator he / she is allowed access.
auth [success=ok default=1] pam_google_authenticator.so

#2 If pam_access doesn't allow access, try google authenticator. If a user hasn't configured his authenticator he / she is NOT allowed access.
auth requisite pam_google_authenticator.so


# After google authenticator is successfull add the IP to the file.
auth optional pam_exec.so log=/var/log/pam_exec.log debug /root/server-management/manage pam/add
````

By default the script will create an access file in `/etc/security/access-known-ips.conf`, optionally add `--file=FILE` to change this. Be sure to update the `pam_access` config as well.

