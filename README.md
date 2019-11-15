# ThiDom

# Installation 
### Exécuter la commande suivante dans votre terminal :
 `cd /tmp && wget https://raw.githubusercontent.com/tguillaume02/ThiDom/master/Get_ThiDom.sh && chmod +x Get_ThiDom.sh && ./Get_ThiDom.sh`

### Puis laissez vous guider :)
    
# SSL ( Let's Encrypt )
### Pour ajouter un certificat SSL reconnu à votre site il vous faut :
* Autoriser le port https (443) sur votre box
* un nom de domaine
    Si vous n'en avez pas vous pouvez utiliser le service de https://www.noip.com/
    
* Une fois que vous avez votre nom de domaine et qu'il est configuré sur votre box vous pouvez utiliser les commandes suivantes:
    `sudo apt-get install python-certbot-apache`
    `certbot --apache`
    `certbot certonly --standalone -d example.com -d www.example.com` ( remplacer example.com par votre nom de domaine )
    * Après avoir exécuté ces commandes, vous serez invité à entrer certains détails, tels que votre adresse e-mail. Cela est nécessaire pour que Let's Encrypt garde une trace des certificats qu'il fournit et lui permette également de vous contacter si des problèmes arrivent avec le certificat.
    Une fois que vous aurez rempli les informations requises, vous pourrez récupérer le certificat de Let's Encrypt.
    * Si vous rencontrez des problèmes, assurez-vous d'avoir un nom de domaine valide pointant sur votre adresse IP, **assurez-vous que le port 80 et le port 443 sont débloqués**.
    * Les certificats qui sont saisis par le client certbot seront stockés dans le dossier suivant. Bien sûr, permuter example.com avec votre propre nom de domaine.
    `/etc/letsencrypt/live/example.com/`


### Envoie d'email à la connection ssh
    * Ajouter dans /etc/bash.bashrc
        ``echo 'Acces SSH en '`id | cut -d "(" -f2 | cut -d ")" -f1`' sur '`hostname`' le: ' `date` `who` | mail -s "NOTIFICATION - Connexion en "`id | cut -d '(' -f2 | cut -d ')' -f1`" via SSH depuis: `echo $SSH_CONNECTION | cut -d " " -f 1`" ``**EMAILDUDESTINATAIRE**
    * Editer le fichier (ou le créer si besoin) /etc/msmtprc 
        `# Set default values for all following accounts.
        account defaults
        port 587
        tls on
        tls_trust_file /etc/ssl/certs/ca-certificates.crt

        account gmail
        host smtp.gmail.com
        from <user>@gmail.com
        auth on
        user <user>
        password <password>

        # Set a default account
        account default : gmail`       
            
# SSL
 ### Générer les clés publique et privée
      ssh-keygen -t rsa -b 4096
 ### Renommer le rsa_id.pub en authorized_keys
      cat id_rsa.pub >> authorized_keys
 ### Transformation de la clé privée pour être utilisable avec putty/Mobaxterm ...
 &nbsp;&nbsp;&nbsp;Copier le fichier id_rsa (la clé privée) sous Windows</br>
 &nbsp;&nbsp;&nbsp;Utiliser Puttygen pour importer la clé </br>
 &nbsp;&nbsp;&nbsp;Tuto => https://my.bluehost.com/hosting/help/putty
