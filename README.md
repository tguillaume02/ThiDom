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
    * Si vous rencontrez des problèmes, assurez-vous d'avoir un nom de domaine valide pointant sur votre adresse IP, assurez-vous que le port 80 et le port 443 sont débloqués.
    * Les certificats qui sont saisis par le client certbot seront stockés dans le dossier suivant. Bien sûr, permuter example.com avec votre propre nom de domaine.
    `/etc/letsencrypt/live/example.com/`
