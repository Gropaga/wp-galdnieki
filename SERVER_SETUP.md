# SERVER SETUP

1. Buy https://www.ovh.com/world/vps/vps-ssd.xml
2. Login via SSH using provided password e.g. `ssh root@vps1111111.ovh.net`. If password was not provided then change it by rebooting server into 'rescue mode' and reseting the password (url: https://docs.ovh.com/gb/en/vps/root-password/)
3. Generate SSH key at local machine via `ssh-keygen -t rsa -b 4096` (or 8192)
4. Then copy key to server via `ssh-copy-id -i id_rsa_new_key.pub root@vps1111111.ovh.net`
5. Login to server again via `ssh root@vps1111111.ovh.net`
6. Edit `vim /etc/ssh/sshd_config` and change set `PasswordAuthentication no` (change yes to no)
7. Restart sshd (OpenSSH server) by `sudo systemctl restart ssh`
8. Create new user `sudo adduser newusername`
9. You may add new user to sudoers list via `sudo usermod -aG sudo newusername`
10. Test user by loggin via `su - james`. Then `sudo dpkg-reconfigure tzdata` to test if the user is in sudo list.
11. Set proper permissions to users folders (you should be logged in as root):
	* `chown -R mynewuser:mynewuser /home/newusername/`
	* `chown root:root /home/newusername`
	* `chmod 700 /home/newusername/.ssh` (create if not available)
	* `chmod 644 /home/newusername/.ssh/authorized_keys` (create if not available)
12. Generate new key for new user via `ssh-keygen -t rsa -b 4096` (or 8192)
13. Add new public key to `/home/newusername/.ssh/authorized_keys` via copy-pasting.
14. On your local machine add `~/Users/user/.ssh/config` (command `touch ~/Users/user/.ssh/config`)
15. Add something similar to be able to login now you can login using `ssh root@somehost`:
```
Host somehost
HostName vps1111111.ovh.net
    Port 22
    User root
    IdentityFile ~/.ssh/id_rsa
```